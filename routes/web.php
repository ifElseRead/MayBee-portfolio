<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogAdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Models\ContactMessage;
use App\Models\LoginLog;
use App\Models\PageView;
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
})->name('home');
Route::get('/contact', function () {
    return Inertia::render('Contact', [
        'recaptchaSiteKey' => env('RECAPTCHA_SITE_KEY', ''),
    ]);
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

Route::get('/dashboard', function () {
    // Get raw aggregated data from the database grouped by date
    $analyticsRaw = PageView::selectRaw('DATE(created_at) as date, count(*) as pageViews, count(DISTINCT ip_address) as visitors')
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->groupByRaw('DATE(created_at)')
        ->get()
        ->keyBy('date');

    // Detailed page views per date
    $pagesRaw = PageView::selectRaw('DATE(created_at) as date, url, count(*) as views')
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->groupByRaw('DATE(created_at), url')
        ->orderByDesc('views')
        ->get()
        ->groupBy('date');

    // Map over the last 7 days to ensure dates with zero traffic still show up in the UI
    $analyticsData = collect(range(6, 0))->map(function ($daysAgo) use ($analyticsRaw, $pagesRaw) {
        $dateString = now()->subDays($daysAgo)->format('Y-m-d');
        $dayData = $analyticsRaw->get($dateString);
        $dayPages = $pagesRaw->get($dateString, collect())->map(fn($item) => ['url' => $item->url, 'views' => $item->views])->toArray();

        return [
            'date' => now()->subDays($daysAgo)->format('M j'),
            'visitors' => $dayData ? $dayData->visitors : 0,
            'pageViews' => $dayData ? $dayData->pageViews : 0,
            'pages' => $dayPages,
        ];
    })->toArray();

    // Global top pages for the last 7 days
    $topPages = PageView::selectRaw('url, count(*) as views')
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->groupBy('url')
        ->orderByDesc('views')
        ->limit(10)
        ->get()
        ->toArray();

    $logPath = storage_path('logs/laravel.log');
    $systemLogs = '';
    if (file_exists($logPath)) {
        $lines = file($logPath);
        $systemLogs = empty($lines) ? '' : implode("", array_slice($lines, -100)); // Get the last 100 lines
    }

    return Inertia::render('Dashboard', [
        'messages' => ContactMessage::orderBy('created_at', 'desc')->paginate(5),
        'totalMessages' => ContactMessage::count(),
        'analytics' => $analyticsData,
        'topPages' => $topPages,
        'loginLogs' => LoginLog::with('user')->latest()->paginate(3, ['*'], 'logins_page'),
        'systemLogs' => $systemLogs,
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


    Route::get('/posts/generate', [BlogAdminController::class, 'create'])->name('posts.generate');
    Route::post('/posts/generate', [BlogAdminController::class, 'store'])->name('posts.store');

    Route::get('/posts', [PostAdminController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}/edit', [PostAdminController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostAdminController::class, 'update'])->name('posts.update');
    Route::post('/posts/{post}/regenerate', [PostAdminController::class, 'regenerate'])->name('posts.regenerate');
    Route::post('/posts/{post}/generate-image', [PostAdminController::class, 'generateImage'])->name('posts.generate-image');
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
