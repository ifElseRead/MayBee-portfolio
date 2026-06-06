<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateBlogPostJob;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogAdminController extends Controller
{
    public function create()
    {
        return Inertia::render('Admin/GeneratePost');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:5|max:255',
        ]);

        GenerateBlogPostJob::dispatch($validated['topic']);

        return redirect()->back()->with('success', 'Blog post generation queued successfully!');
    }
}
