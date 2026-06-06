<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

defineProps({
    posts: Array,
});
</script>

<template>
    <Head title="The Hive - Latest Buzz" />

    <PublicLayout
        class="bg-stone-50 min-h-screen text-stone-900 antialiased selection:bg-amber-500/10 selection:text-amber-900"
    >
        <div class="max-w-5xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <!-- Modern Minimalist Header -->
            <header
                class="mb-16 border-b border-stone-200 pb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4"
            >
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"
                        ></div>
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-amber-600/90"
                            >Production Logs</span
                        >
                    </div>
                    <h1
                        class="text-2xl font-black tracking-tight text-stone-950 sm:text-3xl"
                    >
                        The Latest Wafflings..
                    </h1>
                    <p class="text-stone-500 text-base mt-2 max-w-xl">
                        Updates, insights, and what ever else is on my mind at
                        the moment.
                    </p>
                </div>

                <!-- Modern Count Badge -->
                <div
                    v-if="posts.length"
                    class="text-xs font-mono font-medium text-stone-500 bg-stone-200/60 px-3 py-1.5 rounded-full self-start sm:self-auto"
                >
                    Active Posts: {{ posts.length }}
                </div>
            </header>

            <!-- Modern Card Grid Feed -->
            <div
                v-if="posts.length"
                class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start"
            >
                <article
                    v-for="post in posts"
                    :key="post.id"
                    class="relative bg-white rounded-2xl border border-stone-200/60 shadow-[0_2px_8px_-3px_rgba(0,0,0,0.05)] hover:shadow-[0_12px_24px_-8px_rgba(245,158,11,0.12)] hover:border-amber-500/30 hover:-translate-y-0.5 transition-all duration-300 group flex flex-col overflow-hidden h-full min-h-[180px]"
                >
                    <!-- Post Image Banner -->
                    <div
                        v-if="post.image_url"
                        class="h-48 w-full overflow-hidden shrink-0 border-b border-stone-100"
                    >
                        <img
                            :src="post.image_url"
                            :alt="post.title"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                    </div>

                    <div class="p-8 flex flex-col justify-between h-full">
                        <div>
                            <!-- Minimalist Accent Row -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-[10px] font-mono tracking-wider uppercase text-stone-400 bg-stone-50 px-2 py-0.5 rounded border border-stone-100"
                                    >
                                        Frame #{{ post.id }}
                                    </span>
                                    <span
                                        v-if="post.published_at"
                                        class="text-xs font-medium text-stone-400"
                                    >
                                        {{
                                            new Date(
                                                post.published_at,
                                            ).toLocaleDateString(undefined, {
                                                year: "numeric",
                                                month: "short",
                                                day: "numeric",
                                            })
                                        }}
                                    </span>
                                </div>

                                <span
                                    class="text-amber-500/0 group-hover:text-amber-500/100 group-hover:translate-x-1 transition-all duration-300 text-sm font-bold"
                                >
                                    ↗
                                </span>
                            </div>

                            <!-- Post Title -->
                            <h2
                                class="text-xl font-bold text-stone-950 tracking-tight leading-snug"
                            >
                                <Link
                                    :href="`/posts/${post.slug}`"
                                    class="hover:text-amber-600 transition-colors duration-200 block focus:outline-none"
                                >
                                    {{ post.title }}
                                </Link>
                            </h2>

                            <!-- Post Summary -->
                            <p
                                class="text-stone-500 text-sm leading-relaxed mt-3 group-hover:text-stone-600 transition-colors duration-200 line-clamp-3"
                            >
                                {{ post.summary }}
                            </p>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sleek Empty State -->
            <div
                v-else
                class="max-w-md mx-auto text-center py-20 px-6 bg-white rounded-2xl border border-stone-200/80 shadow-sm"
            >
                <div
                    class="w-12 h-12 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4 text-amber-600 text-xl font-bold select-none shadow-sm"
                >
                    🍯
                </div>
                <h3 class="text-base font-bold text-stone-950 tracking-tight">
                    The hive is currently quiet
                </h3>
                <p
                    class="text-stone-500 text-xs mt-1.5 leading-relaxed max-w-xs mx-auto"
                >
                    No new updates have been gathered for this frame just yet.
                    Check back soon for fresh arrivals.
                </p>
            </div>
        </div>
    </PublicLayout>
</template>
