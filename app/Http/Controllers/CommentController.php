<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage securely.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|max:2000',
            'recaptcha_token' => 'required|string',
        ]);

        if (! $this->verifyRecaptcha($validated['recaptcha_token'])) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        $post->comments()->create([
            'author_name' => $validated['author_name'],
            'email' => $validated['email'],
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Your comment has been posted!');
    }
}
