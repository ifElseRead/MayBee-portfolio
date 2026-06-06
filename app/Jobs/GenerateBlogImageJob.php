<?php

namespace App\Jobs;

use App\Models\Post;
use Gemini\Data\GenerationConfig;
use Gemini\Data\ImageConfig;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Exceptions\ErrorException as GeminiException;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GenerateBlogImageJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted when running on a background queue.
     * Given it hits external API limits, we want to fail fast rather than spamming.
     */
    public int $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(public Post $post) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("=== AI Image Generation Started ===", ['post_id' => $this->post->id]);

        try {
            $imagePrompt = "A modern, minimal, high-quality editorial abstract vector graphic banner representing: " . $this->post->title;

            $imageConfig = new ImageConfig(aspectRatio: '16:9');
            $generationConfig = new GenerationConfig(imageConfig: $imageConfig);

            // Using the dedicated production image generation model identifier
            $response = Gemini::generativeModel(model: 'gemini-3.1-flash-image')
                ->withGenerationConfig($generationConfig)
                ->generateContent($imagePrompt);

            if (empty($response->parts()) || empty($response->parts()[0]->inlineData->data)) {
                throw new \Exception("Gemini successfully responded but returned zero image assets.");
            }

            $imageBinary = base64_decode($response->parts()[0]->inlineData->data);
            $filename = 'blog-banners/' . Str::slug($this->post->title) . '-' . time() . '.png';
            Storage::disk('public')->put($filename, $imageBinary);

            $this->post->update([
                'banner_image' => $filename,
            ]);

            Log::info("=== AI Image Generation Completed ===");
        } catch (GeminiException $e) {
            Log::error("Gemini API Specific Failure (Check Quota/Billing)", [
                'post_id' => $this->post->id,
                'error' => $e->getMessage()
            ]);

            $this->handleFailureFallback();

            // Safety gate: Only mark as failed if running inside an active queue worker context.
            // This prevents "Undefined array key 'job'" crashes when testing synchronously.
            if ($this->job && ! $this->job instanceof \Illuminate\Queue\Jobs\SyncJob) {
                $this->fail($e);
            } else {
                // Re-throw during synchronous execution so you can see the actual Gemini API error
                throw $e;
            }
        } catch (Throwable $e) {
            Log::error("Failed AI Image Generation due to system error", [
                'post_id' => $this->post->id,
                'error' => $e->getMessage()
            ]);

            $this->handleFailureFallback();

            // Re-throwing allows Laravel's application/queue kernel to catch and log the issue natively
            throw $e;
        }
    }

    /**
     * Gracefully set a fallback image state so your frontend UI layout 
     * doesn't break if the external API request fails.
     */
    protected function handleFailureFallback(): void
    {
        if (empty($this->post->banner_image)) {
            $this->post->update([
                'banner_image' => 'blog-banners/default-fallback.png'
            ]);
        }
    }
}
