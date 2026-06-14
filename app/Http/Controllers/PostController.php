<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateBlogPostJob;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Posts/Index', [
            'posts' => Post::published()->latest('published_at')->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage by regenerating it.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:5|max:255',
        ]);

        GenerateBlogPostJob::dispatch($validated['topic'], $post);

        return redirect()->back()->with('success', 'Blog post regeneration queued successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        return Inertia::render('Posts/Show', [
            'post' => Post::published()->with(['comments' => function ($query) {
                $query->latest();
            }])->where('slug', $slug)->firstOrFail(),
            'recaptchaSiteKey' => config('services.recaptcha.site_key', ''),
        ]);
    }
}
