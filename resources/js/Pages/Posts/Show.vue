<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import Seo from "@/Components/Seo.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    post: Object,
});
</script>

<template>
    <Seo :title="post.title" :description="post.summary" />

    <PublicLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Back Link -->
                <div class="mb-4">
                    <Link
                        href="/posts"
                        class="text-yellow-600 hover:text-yellow-900 font-semibold transition-colors inline-block"
                    >
                        ← Back to all posts
                    </Link>
                </div>

                <!-- Post Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <article class="p-6 bg-white border-b border-gray-200">
                        <header
                            class="mb-10 pb-10 border-b border-gray-200 text-center"
                        >
                            <!-- <h1
                                class="text-4xl sm:text-5xl font-black tracking-tight text-gray-900 mb-6"
                            >
                                {{ post.title }}
                            </h1>
                            <p
                                class="text-xl text-gray-600 leading-relaxed max-w-2xl mx-auto"
                            >
                                {{ post.summary }}
                            </p> -->
                            <img
                                v-if="post.banner_image"
                                :src="`/storage/${post.banner_image}`"
                                :alt="post.title"
                                class="w-full sm:rounded-lg shadow-sm border border-gray-200 mt-10"
                            />
                        </header>

                        <!-- Fallback to display the raw body with preserved spacing if html_body is not compiled -->
                        <div
                            class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-a:font-semibold prose-a:text-yellow-600 hover:prose-a:text-yellow-900 prose-a:transition-colors prose-blockquote:border-l-yellow-400 prose-blockquote:bg-yellow-50/50 prose-blockquote:px-6 prose-blockquote:py-2 prose-blockquote:rounded-r-lg prose-blockquote:not-italic prose-blockquote:text-gray-700 prose-img:rounded-lg prose-pre:bg-black prose-pre:text-white"
                            :class="{ 'whitespace-pre-wrap': !post.html_body }"
                            v-html="post.html_body || post.body"
                        ></div>
                    </article>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
