<?php

namespace App\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;

class BlogPostAgent implements Agent, HasStructuredOutput
{
    public function instructions(): string
    {
        return "You are an expert, senior full-stack developer with a focus on modern PHP, Laravel, Vue.js, and clean architecture. "
            . "You write technical, insightful, and SEO-optimized blog posts. Your tone is professional, engaging, and practical. "
            . "Always ensure your code examples are accurate and your explanations are easy to follow.";
    }

    public function schema(): array
    {
        return [
            'title' => 'string',
            'slug' => 'string (URL-friendly slug)',
            'summary' => 'string (short 2-sentence summary)',
            'body' => 'string (format as clean Markdown)',
        ];
    }
}
