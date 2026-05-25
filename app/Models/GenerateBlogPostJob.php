<?php

namespace App\Jobs;

use App\Agents\BlogPostAgent;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Ai\Facades\Ai;
use Laravel\Ai\Image;

class GenerateBlogPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $topic) {}

    public function handle(): void
    {
        // 1. Generate text content
        $agent = new BlogPostAgent();
        $content = Ai::agent($agent)
            ->prompt("Write a detailed, technical blog post about: {$this->topic}")
            ->generate();

        // 2. Generate banner image
        $imagePrompt = "A high-quality, abstract, modern 16:9 landscape digital art image representing: " . $content['title'];
        $imageResult = Image::prompt($imagePrompt)->generate();

        // 3. Store image locally
        $filename = 'blog-banners/' . Str::slug($content['title']) . '-' . time() . '.png';
        Storage::disk('public')->put($filename, $imageResult->getContent());

        // 4. Save to database
        Post::create([
            ...$content,
            'banner_image' => $filename,
            'is_published' => true,
        ]);
    }
}
