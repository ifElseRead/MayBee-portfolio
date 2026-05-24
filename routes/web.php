<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Models\ContactMessage;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return Inertia::render('John', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::get('/contact', function () {
    return Inertia::render('Contact', [
        'recaptchaSiteKey' => env('RECAPTCHA_SITE_KEY', ''),
    ]);
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


Route::get('/dashboard', function () {
    $analyticsData = [];

    return Inertia::render('Dashboard', [
        'messages' => ContactMessage::orderBy('created_at', 'desc')->paginate(5),
        'totalMessages' => ContactMessage::count(),
        'analytics' => $analyticsData,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::get('/messages/{id}', [AdminController::class, 'show'])->name('show');
    Route::delete('/messages/{id}', [AdminController::class, 'destroy'])->name('destroy');
});

require __DIR__ . '/auth.php';

// Catch-all fallback route to cleanly handle 404s
Route::fallback(function () {
    // If an Inertia <Link> triggered the 404, force the browser to do a full page reload
    if (request()->header('X-Inertia')) {
        return Inertia::location(url()->current());
    }

    if (view()->exists('errors.404')) {
        return response()->view('errors.404', [], 404);
    }

    abort(404);
});
