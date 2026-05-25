<?php

namespace App\Http\Controllers;

use App\Models\Post;

class FeedController extends Controller
{
    public function __invoke()
    {
        $posts = Post::where('is_published', true)
            ->latest()
            ->take(20)
            ->get();

        return response()->view('feed', compact('posts'))
            ->header('Content-Type', 'text/xml');
    }
}
