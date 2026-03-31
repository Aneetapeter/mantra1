<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgressController;

Route::get('/', function () {
    return view('index');
});

// Register Route
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Login Route
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Forgot Password Routes (OTP-based)
use App\Http\Controllers\ForgotPasswordController;
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify.post');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
Route::post('/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend');

// Logout page
Route::get('/logout', function () {
    return view('auth.logout');
})->middleware('auth')->name('logout.page');

// Contact Form
use App\Http\Controllers\ContactController;
Route::post('/contact', [ContactController::class, 'sendMessage'])->name('contact.send');

// Logout action
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Dashboard Intro — always plays the intro video, then auto-redirects to dashboard
Route::get('/dashboard-intro', function () {
    return view('dashboard-intro');
})->middleware('auth')->name('dashboard.intro');

// Dashboard Route (Protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    // TODOS
    Route::get('/api/todos', [TodoController::class, 'index']);
    Route::post('/api/todos', [TodoController::class, 'store']);
    Route::put('/api/todos/{id}', [TodoController::class, 'update']);
    Route::delete('/api/todos/{id}', [TodoController::class, 'destroy']);

    // EVENTS (CALENDAR)
    Route::get('/api/events', [EventController::class, 'index']);
    Route::post('/api/events', [EventController::class, 'store']);
    Route::put('/api/events/{id}', [EventController::class, 'update']);
    Route::patch('/api/events/{id}/date', [EventController::class, 'updateDate']); // Drag-drop
    Route::patch('/api/events/{id}/toggle', [EventController::class, 'toggleStatus']); // Toggle complete
    Route::delete('/api/events/{id}', [EventController::class, 'destroy']);

    // NOTES
    Route::get('/api/notes', [NoteController::class, 'index']);
    Route::post('/api/notes', [NoteController::class, 'store']);
    Route::put('/api/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/api/notes/{id}', [NoteController::class, 'destroy']);

    // STUDY TRACKING
    Route::post('/api/study/complete', [DashboardController::class, 'completeStudySession']);

    // SMART NOTES (Phase 1)
    Route::get('/app/smart-notes', [App\Http\Controllers\SmartNoteController::class, 'index']); // Get all data
    Route::post('/app/smart-notes', [App\Http\Controllers\SmartNoteController::class, 'store']);
    Route::put('/app/smart-notes/{id}', [App\Http\Controllers\SmartNoteController::class, 'update']);
    Route::delete('/app/smart-notes/{id}', [App\Http\Controllers\SmartNoteController::class, 'destroy']);
    Route::post('/app/smart-notes/folders', [App\Http\Controllers\SmartNoteController::class, 'storeFolder']);
    Route::delete('/app/smart-notes/folders/{id}', [App\Http\Controllers\SmartNoteController::class, 'destroyFolder']);
    Route::post('/app/smart-notes/tags', [App\Http\Controllers\SmartNoteController::class, 'storeTag']);
    Route::post('/app/smart-notes/extract', [App\Http\Controllers\SmartNoteController::class, 'extract']);
    Route::post('/app/smart-notes/upload', [App\Http\Controllers\SmartNoteController::class, 'upload']);

    // ── App-open time heartbeat (passive study time tracking) ──
    Route::post('/api/heartbeat', [App\Http\Controllers\DashboardController::class, 'heartbeat'])
        ->name('heartbeat');
});

use Illuminate\Support\Facades\Auth;

Route::get('/watch', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('watch.redirect');

// (duplicate removed — see route above)


Route::get('/library', [App\Http\Controllers\LibraryController::class, 'index'])->middleware('auth')->name('library');

Route::middleware('auth')->group(function () {
    Route::post('/library/track-visit', [App\Http\Controllers\LibraryController::class, 'trackVisit']);

    Route::view('/study', 'study')->name('study');

    Route::get('/progress', [ProgressController::class, 'index'])->name('progress');
    Route::get('/api/progress', [ProgressController::class, 'data']);

    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [App\Http\Controllers\SettingController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [App\Http\Controllers\SettingController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/preferences', [App\Http\Controllers\SettingController::class, 'updatePreferences'])->name('settings.preferences');
    Route::delete('/settings/account', [App\Http\Controllers\SettingController::class, 'deleteAccount'])->name('settings.delete');

    // ── Chat Routes ──
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
    Route::get('/chat/search', [App\Http\Controllers\ChatController::class, 'searchStudents']);
    Route::post('/chat/request', [App\Http\Controllers\ChatController::class, 'sendFriendRequest']);
    Route::get('/chat/requests', [App\Http\Controllers\ChatController::class, 'getFriendRequests']);
    Route::post('/chat/respond', [App\Http\Controllers\ChatController::class, 'respondRequest']);
    Route::get('/chat/friends', [App\Http\Controllers\ChatController::class, 'getFriends']);
    Route::get('/chat/messages/{friendId}', [App\Http\Controllers\ChatController::class, 'getMessages']);
    Route::post('/chat/message', [App\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::post('/chat/block', [App\Http\Controllers\ChatController::class, 'blockUser']);
    Route::post('/chat/unblock', [App\Http\Controllers\ChatController::class, 'unblockUser']);
    Route::get('/chat/blocked', [App\Http\Controllers\ChatController::class, 'getBlocked']);
    Route::get('/chat/profile/{id}', [App\Http\Controllers\ChatController::class, 'getUserProfile']);

    // ── Quiz Routes ──
    Route::post('/api/quiz/generate', [App\Http\Controllers\QuizController::class, 'generate'])->name('quiz.generate');
    Route::post('/api/quiz/complete', [App\Http\Controllers\QuizController::class, 'complete'])->name('quiz.complete');

    // ── Profile Page ──
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});