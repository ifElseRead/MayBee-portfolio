<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateBlogPostJob;
use App\Jobs\GenerateBlogImageJob;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'banner_image' => 'nullable|image|max:5120', // validate image up to 5MB
            'body' => 'nullable|string',
        ]);

        if ($request->hasFile('banner_image')) {
            // Delete the old banner if it exists
            if ($post->banner_image && Storage::disk('public')->exists($post->banner_image)) {
                Storage::disk('public')->delete($post->banner_image);
            }

            $file = $request->file('banner_image');
            $filename = Str::slug($post->title ?? 'banner') . '-' . time() . '.' . $file->extension();
            $validated['banner_image'] = $file->storeAs('blog-banners', $filename, 'public');
        }

        $post->update($validated);

        return redirect()->back()->with('success', 'Post updated successfully!');
    }

    public function regenerate(Request $request, Post $post)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:5|max:15000',
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
