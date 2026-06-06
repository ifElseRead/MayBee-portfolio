<?php

namespace App\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Promptable;

class BlogPostAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function provider(): string
    {
        return 'google';
    }

    public function model(): string
    {
        return 'gemini-2.5-pro';
    }

    public function instructions(): string
    {
        return "You are an expert, senior full-stack developer with a focus on modern PHP, Laravel, Vue.js, and clean architecture. "
            . "You write technical, insightful, and SEO-optimized blog posts. Your tone is professional, engaging, and practical. "
            . "Always ensure your code examples are accurate and your explanations are easy to follow.";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string(),
            'slug' => $schema->string()->description('URL-friendly slug'),
            'summary' => $schema->string()->description('short 2-sentence summary'),
            'body' => $schema->string()->description('format as clean Markdown'),
        ];
    }
}
