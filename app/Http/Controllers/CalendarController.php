<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $tasks = Task::where('tenant_id', Auth::user()->tenant_id)
            ->with('assignedTo')
            ->get();

        $events = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                'extendedProps' => [
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'assigned_to' => $task->assignedTo->name ?? 'Unassigned',
                ],
                'className' => $task->status === 'completed' ? 'bg-emerald-500' :
                              ($task->status === 'in_progress' ? 'bg-blue-500' :
                              ($task->status === 'cancelled' ? 'bg-rose-500' : 'bg-yellow-500')),
            ];
        })->filter(function ($event) {
            return $event['start'] !== null;
        })->values();

        return view('calendar.index', compact('events'));
    }
}