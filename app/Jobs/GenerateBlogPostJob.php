<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Gemini\Laravel\Facades\Gemini;
use Throwable;

class GenerateBlogPostJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $topic, public ?Post $post = null) {}

    public function handle(): Post
    {
        Log::info("=== AI Blog Generation Started ===", ['topic' => $this->topic]);

        // --- CHECKPOINT 1: TEXT GENERATION ---
        try {
            Log::info("Checkpoint 1: Requesting unstructured raw content with metadata markers...");

            // Get both the metadata and body in a single plain text stream, safely partitioned
            $response = Gemini::generativeModel(model: 'gemini-2.5-flash')->generateContent(
                "You are an expert technical blogger. Write a deep dive blog post about: '{$this->topic}'.\n\n" .
                    "OUTPUT TEMPLATE:\n" .
                    "You must start your response EXACTLY with this key-value header setup, then use '===' on its own line to separate it from the content:\n\n" .
                    "TITLE: Clear, compelling title here\n" .
                    "SLUG: url-friendly-slug-here\n" .
                    "SUMMARY: A 2-3 sentence overview paragraph summarizing the post.\n" .
                    "===\n" .
                    "# Your Deep Dive Blog Post Content Starts Here...\n" .
                    "Use standard markdown notation. Feel free to include code snippets (e.g. ```php) or configuration blocks freely without escaping anything."
            );

            $rawText = $response->text();

            if (!str_contains($rawText, '===')) {
                throw new \Exception("Gemini output was missing the structural split marker '==='.");
            }

            // Split header block from markdown post body
            [$metaBlock, $markdownBody] = explode('===\n', str_replace("\r", "", $rawText), 2) + [null, null];

            // Fallback split if newline configurations vary across generation
            if (empty($markdownBody)) {
                [$metaBlock, $markdownBody] = explode('===', $rawText, 2);
            }

            // Pull parameters using regex lines
            preg_match('/TITLE:\s*(.*)/i', $metaBlock, $titleMatch);
            preg_match('/SLUG:\s*(.*)/i', $metaBlock, $slugMatch);
            preg_match('/SUMMARY:\s*(.*)/i', $metaBlock, $summaryMatch);

            $content = [
                'title'   => trim($titleMatch[1] ?? 'Untitled Generated Post'),
                'slug'    => trim($slugMatch[1] ?? Str::slug($this->topic)),
                'summary' => trim($summaryMatch[1] ?? ''),
                'body'    => trim($markdownBody),
            ];

            Log::info("Gemini Text Content parsed successfully via plain text markers!", ['title' => $content['title']]);
        } catch (Throwable $e) {
            Log::error("Failed at Checkpoint 1 (Text parsing)", ['message' => $e->getMessage()]);
            throw $e;
        }

        // --- CHECKPOINT 2: DATABASE WRITE ---
        try {
            Log::info("Checkpoint 2: Saving post configuration to DB...");

            $baseSlug = Str::slug($content['slug'] ?? $content['title']);
            $slug = $baseSlug;
            $counter = 1;

            while (Post::where('slug', $slug)->when($this->post, fn($query) => $query->where('id', '!=', $this->post->id))->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $postData = [
                'title'        => $content['title'],
                'slug'         => $slug,
                'summary'      => $content['summary'],
                'body'         => $content['body'], // Raw unstructured markdown content saved perfectly
                'prompt_topic' => $this->topic,
                'status'       => 'draft',
            ];

            if ($this->post) {
                $this->post->update($postData);
                $post = $this->post;
            } else {
                $post = Post::create($postData);
            }
            Log::info("=== AI Blog Generation Completed Flawlessly ===");

            return $post;
        } catch (Throwable $e) {
            Log::error("Failed at Checkpoint 4 (Database Write)", ['message' => $e->getMessage()]);
            throw $e;
        }
    }
}
