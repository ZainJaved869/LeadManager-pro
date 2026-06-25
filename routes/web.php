<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PipelineController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\SubscriptionController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

// ===== SPLASH / WELCOME SCREEN =====
Route::get('/', function () {
    return view('welcome');
});


// ===== ADMIN ROUTES =====
require __DIR__.'/admin.php';

// All routes are wrapped in the 'tenant' middleware to set the current tenant
// from the authenticated user.
Route::middleware('tenant')->group(function () {

    // Guest routes (not logged in)
    Route::middleware('guest')->group(function () {
        // Login
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);

        // Registration
        Route::get('/register', [RegisteredUserController::class, 'create'])
            ->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store']);

        // Forgot password
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        // Reset password
        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');
        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->name('password.update');
    });

    // Authenticated routes (logged in)
    Route::middleware('auth')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile management
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        // Email verification (optional)
        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed'])->name('verification.verify');
        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['throttle:6,1'])->name('verification.send');

        // ========== LEAD MANAGEMENT ROUTES ==========
       // ========== LEAD MANAGEMENT ROUTES ==========
// Custom routes must come BEFORE resource to avoid conflict
Route::get('/leads/export', [LeadController::class, 'export'])->name('leads.export');
Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');

// Resource routes (index, create, store, show, edit, update, destroy)
Route::resource('leads', LeadController::class);

// Additional routes (notes & files)
Route::post('/leads/{lead}/notes', [LeadController::class, 'addNote'])->name('leads.notes.store');
Route::post('/leads/{lead}/files', [LeadController::class, 'uploadFile'])->name('leads.files.store');
Route::delete('/leads/files/{file}', [LeadController::class, 'deleteFile'])->name('leads.files.destroy');

// ========== PIPELINE ROUTES ==========
Route::get('/pipeline', [PipelineController::class, 'index'])->name('pipeline.index');
Route::post('/pipeline/update-stage', [PipelineController::class, 'updateStage'])->name('pipeline.update');
Route::post('/pipeline/reorder-stages', [PipelineController::class, 'reorderStages'])->name('pipeline.reorder');

// Stage Management (CRUD)
Route::get('/pipeline/stages', [PipelineController::class, 'stagesIndex'])->name('pipeline.stages.index');
Route::get('/pipeline/stages/create', [PipelineController::class, 'stagesCreate'])->name('pipeline.stages.create');
Route::post('/pipeline/stages', [PipelineController::class, 'stagesStore'])->name('pipeline.stages.store');
Route::get('/pipeline/stages/{stage}/edit', [PipelineController::class, 'stagesEdit'])->name('pipeline.stages.edit');
Route::put('/pipeline/stages/{stage}', [PipelineController::class, 'stagesUpdate'])->name('pipeline.stages.update');
Route::delete('/pipeline/stages/{stage}', [PipelineController::class, 'stagesDestroy'])->name('pipeline.stages.destroy');
Route::get('/pipeline/stages/{stage}', [PipelineController::class, 'stagesShow'])->name('pipeline.stages.show');

// ========== CUSTOMER MANAGEMENT (COMPANIES & CONTACTS) ==========
// Companies (full CRUD)
Route::resource('companies', CompanyController::class);

// Contacts (nested under companies, with custom names)
Route::get('/companies/{company}/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
Route::post('/companies/{company}/contacts', [ContactController::class, 'store'])->name('contacts.store');
Route::get('/companies/{company}/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
Route::put('/companies/{company}/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
Route::delete('/companies/{company}/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

// ========== TASK MANAGEMENT ==========
Route::resource('tasks', TaskController::class);
Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

// ========== ANALYTICS ==========
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');


// ========== PROFILE & SETTINGS ==========
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');


// ========== AI INTEGRATION ==========
Route::get('/ai', [AIController::class, 'index'])->name('ai.index');
Route::post('/ai/email', [AIController::class, 'generateEmail'])->name('ai.email');
Route::post('/ai/followup', [AIController::class, 'generateFollowup'])->name('ai.followup');
Route::post('/ai/proposal', [AIController::class, 'generateProposal'])->name('ai.proposal');
Route::post('/ai/summary', [AIController::class, 'generateSummary'])->name('ai.summary');


// ========== CALENDAR ==========
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

// ========== REMINDERS (API / AJAX) ==========
Route::post('/tasks/{task}/reminder', [TaskController::class, 'setReminder'])->name('tasks.reminder');

// ========== NOTIFICATIONS ==========
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');

// ========== COMMUNICATION HISTORY ==========
Route::post('/communications', [CommunicationController::class, 'store'])->name('communications.store');
Route::put('/communications/{communication}', [CommunicationController::class, 'update'])->name('communications.update');
Route::delete('/communications/{communication}', [CommunicationController::class, 'destroy'])->name('communications.destroy');


// ========== SUBSCRIPTION SYSTEM ==========
Route::get('/subscription/plans', [SubscriptionController::class, 'plans'])->name('subscription.plans');
Route::get('/subscription/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
Route::post('/subscription/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
Route::get('/subscription/invoices', [SubscriptionController::class, 'invoices'])->name('subscription.invoices');
Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
        // Logout
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});