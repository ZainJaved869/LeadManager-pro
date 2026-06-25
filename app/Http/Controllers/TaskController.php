<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Lead;
use App\Models\Company;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskController extends Controller
{
 public function index(Request $request)
{
    $query = Task::where('tenant_id', Auth::user()->tenant_id);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    $tasks = $query->with(['assignedTo', 'taskable'])
        ->latest()
        ->paginate(15);

    // Stats (remain global, not affected by search)
    $pendingCount = Task::where('tenant_id', Auth::user()->tenant_id)
        ->where('status', 'pending')->count();
    $inProgressCount = Task::where('tenant_id', Auth::user()->tenant_id)
        ->where('status', 'in_progress')->count();
    $completedCount = Task::where('tenant_id', Auth::user()->tenant_id)
        ->where('status', 'completed')->count();
    $overdueCount = Task::where('tenant_id', Auth::user()->tenant_id)
        ->where('due_date', '<', now())
        ->whereIn('status', ['pending', 'in_progress'])
        ->count();

    return view('tasks.index', compact(
        'tasks', 'pendingCount', 'inProgressCount',
        'completedCount', 'overdueCount'
    ));
}

    public function create()
    {
        $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
        $leads = Lead::where('tenant_id', Auth::user()->tenant_id)->get();
        $companies = Company::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('tasks.create', compact('users', 'leads', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'taskable_type' => 'nullable|in:lead,company',
            'taskable_id' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'reminder_at' => 'nullable|date',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        if ($request->taskable_type && $request->taskable_id) {
            $map = [
                'lead' => Lead::class,
                'company' => Company::class,
            ];
            $validated['taskable_type'] = $map[$request->taskable_type] ?? null;
        } else {
            $validated['taskable_type'] = null;
            $validated['taskable_id'] = null;
        }

        $task = Task::create($validated);

        // --- NOTIFICATION: Task assigned ---
        if ($task->assigned_to) {
            $this->createNotification(
                $task->assigned_to,
                'New task assigned: ' . $task->title,
                'Task "' . $task->title . '" has been assigned to you.' . ($task->due_date ? ' Due: ' . $task->due_date->format('M d, Y') : ''),
                route('tasks.show', $task)
            );
        }

        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function show(Task $task)
    {
        $this->authorizeTenant($task);
        $task->load(['assignedTo', 'taskable']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorizeTenant($task);
        $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
        $leads = Lead::where('tenant_id', Auth::user()->tenant_id)->get();
        $companies = Company::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('tasks.edit', compact('task', 'users', 'leads', 'companies'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTenant($task);

        $oldAssignedId = $task->assigned_to;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'taskable_type' => 'nullable|in:lead,company',
            'taskable_id' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'reminder_at' => 'nullable|date',
        ]);

        if ($request->taskable_type && $request->taskable_id) {
            $map = [
                'lead' => Lead::class,
                'company' => Company::class,
            ];
            $validated['taskable_type'] = $map[$request->taskable_type] ?? null;
        } else {
            $validated['taskable_type'] = null;
            $validated['taskable_id'] = null;
        }

        $task->update($validated);

        // --- NOTIFICATION: Assigned user changed ---
        if ($task->assigned_to && $task->assigned_to != $oldAssignedId) {
            $this->createNotification(
                $task->assigned_to,
                'Task assigned to you: ' . $task->title,
                'Task "' . $task->title . '" has been assigned to you.' . ($task->due_date ? ' Due: ' . $task->due_date->format('M d, Y') : ''),
                route('tasks.show', $task)
            );
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTenant($task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function setReminder(Request $request, Task $task)
    {
        $this->authorizeTenant($task);

        $validated = $request->validate([
            'remind_at' => 'required|date|after:now',
            'type' => 'nullable|in:email,in_app,both',
            'description' => 'nullable|string',
        ]);

        $reminder = Reminder::create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'remindable_type' => Task::class,
            'remindable_id' => $task->id,
            'title' => 'Task Reminder: ' . $task->title,
            'description' => $validated['description'] ?? $task->description,
            'remind_at' => $validated['remind_at'],
            'type' => $validated['type'] ?? 'email',
            'is_sent' => false,
        ]);

        return redirect()->back()->with('success', 'Reminder set for ' . $reminder->remind_at->format('M d, Y H:i'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorizeTenant($task);
        $request->validate(['status' => 'required|in:pending,in_progress,completed,cancelled']);

        $oldStatus = $task->status;
        $task->update(['status' => $request->status]);

        // --- NOTIFICATION: Task completed ---
        if ($request->status === 'completed' && $task->assigned_to && $task->assigned_to != Auth::id()) {
            $this->createNotification(
                $task->assigned_to,
                'Task completed: ' . $task->title,
                'Task "' . $task->title . '" was marked as complete by ' . Auth::user()->name . '.',
                route('tasks.show', $task)
            );
        }

        return response()->json(['success' => true]);
    }

    // ====== GUARANTEED NOTIFICATION METHOD ======
    protected function createNotification($userId, $title, $description, $url)
    {
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\ActivityNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => json_encode([
                'title' => $title,
                'description' => $description,
                'url' => $url,
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }
    }
}