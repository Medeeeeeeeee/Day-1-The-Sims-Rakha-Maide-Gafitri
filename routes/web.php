<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAssistantController;

// public landing page
Route::get('/', function () {
    return view('welcome');
});

// authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// student dashboard is public (no login required)
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->name('dashboard');

// alias so that /mahasiswa points to the same page
Route::redirect('/mahasiswa', '/dashboard');

// admin dashboard requires authenticated admin user
Route::get('/admin', [\App\Http\Controllers\DashboardController::class, 'supervisor'])
    ->middleware('auth')
    ->name('admin.dashboard');

// asisten dashboard requires authenticated asisten user
Route::get('/asisten', [\App\Http\Controllers\DashboardController::class, 'asisten'])
    ->middleware('auth')
    ->name('asisten.dashboard');

// ticketing resources
// anyone can view/index and submit tickets
Route::resource('tickets', \App\Http\Controllers\TicketController::class);

// user profile
Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])
    ->middleware('auth')
    ->name('profile');

// maintenance history and schedules require authentication
Route::middleware(['auth','role:supervisor,asisten'])->group(function () {
    Route::get('histories-print', [\App\Http\Controllers\MaintenanceHistoryController::class, 'print'])->name('histories.print');
    Route::resource('histories', \App\Http\Controllers\MaintenanceHistoryController::class);
    Route::patch('schedules/{schedule}/complete', [\App\Http\Controllers\MaintenanceScheduleController::class, 'complete'])->name('schedules.complete');
    Route::resource('schedules', \App\Http\Controllers\MaintenanceScheduleController::class);
});

// lab management only for admins - role check inside controller now
Route::middleware('auth')->group(function () {
    Route::get('/admin/asisten', [AdminAssistantController::class, 'index'])->name('admin.assistants.index');
    Route::get('/admin/asisten/create', [AdminAssistantController::class, 'create'])->name('admin.assistants.create');
    Route::post('/admin/asisten', [AdminAssistantController::class, 'store'])->name('admin.assistants.store');
    Route::get('/admin/asisten/{assistant}/edit', [AdminAssistantController::class, 'edit'])->name('admin.assistants.edit');
    Route::put('/admin/asisten/{assistant}', [AdminAssistantController::class, 'update'])->name('admin.assistants.update');
    Route::delete('/admin/asisten/{assistant}', [AdminAssistantController::class, 'destroy'])->name('admin.assistants.destroy');
    Route::resource('labs', \App\Http\Controllers\LabController::class);
});
