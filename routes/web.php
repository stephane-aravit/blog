<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'posts' => \App\Models\Post::with(['categories','comments','comments.user','user'])->get(),
        'categories' => \App\Models\Category::all(),
    ]);
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'posts' => \App\Models\Post::with(['categories', 'user'])->get(),
            'comments' => \App\Models\Comment::with(['user', 'post'])->get(),
            'users' => \App\Models\User::get(),
            // 'postsRaw' => \App\Models\Post::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')->groupBy('month')->orderBy('month')->get(),
            // 'commentsRaw' => \App\Models\Comment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')->groupBy('month')->orderBy('month')->get(),
            // 'usersRaw' => \App\Models\User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')->groupBy('month')->orderBy('month')->get(),
            'postsRaw' => \App\Models\Post::all()
                ->groupBy(fn ($post) => Carbon::parse($post->created_at)->format('Y-m'))
                ->map(fn (Collection $group, $month) => [
                    'month' => $month,
                    'total' => $group->count(),
                ])
                ->values(),

            'commentsRaw' => \App\Models\Comment::all()
                ->groupBy(fn ($comment) => Carbon::parse($comment->created_at)->format('Y-m'))
                ->map(fn (Collection $group, $month) => [
                    'month' => $month,
                    'total' => $group->count(),
                ])
                ->values(),

            'usersRaw' => \App\Models\User::all()
                ->groupBy(fn ($user) => Carbon::parse($user->created_at)->format('Y-m'))
                ->map(fn (Collection $group, $month) => [
                    'month' => $month,
                    'total' => $group->count(),
                ])
                ->values(),
        ]);
    })->name('dashboard');
    Route::resource('posts', App\Http\Controllers\PostController::class);
    Route::resource('comments', App\Http\Controllers\CommentController::class);
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
