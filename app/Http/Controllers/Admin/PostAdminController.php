<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateBlogPostJob;
use App\Jobs\GenerateBlogImageJob;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostAdminController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return Inertia::render('Admin/Posts/Index', compact('posts'));
    }

    public function edit(Post $post)
    {
        $post->html_body = Str::markdown($post->body ?? '');
        return Inertia::render('Admin/Posts/Edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:draft,published,rejected',
            'created_at' => 'sometimes|date',
            'published_at' => 'nullable|date',
        ]);

        $post->update($validated);

        return redirect()->back()->with('success', 'Post updated successfully!');
    }

    public function regenerate(Request $request, Post $post)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:5|max:255',
        ]);

        // Dispatch a new job with the updated topic
        GenerateBlogPostJob::dispatch($validated['topic'], $post);

        return redirect()->route('admin.posts.index')->with('success', 'Post regeneration queued successfully! It will be updated shortly.');
    }

    public function generateImage(Request $request, Post $post)
    {
        GenerateBlogImageJob::dispatch($post);

        return redirect()->back()->with('success', 'Image generation queued successfully! It will appear shortly once complete.');
    }
}
