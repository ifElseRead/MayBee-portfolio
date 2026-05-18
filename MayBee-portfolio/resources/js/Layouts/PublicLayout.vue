<script setup>
import { Link, Head } from "@inertiajs/vue3"; // Added Head here
</script>

<template>
    <Head>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    </Head>

    <div
        class="min-h-screen bg-slate-50 text-slate-800 antialiased selection:bg-yellow-300 selection:text-slate-900 font-sans"
    >
        <div class="relative max-w-5xl mx-auto px-6 py-6 sm:px-8 sm:py-10">
            <nav class="w-full flex items-center justify-between mb-16">
                <Link href="/" class="flex items-end gap-0.5 group">
                    <svg
                        class="w-11 h-11 text-slate-900 shrink-0 translate-y-[3px] group-hover:text-yellow-500 transition-colors duration-300"
                        viewBox="0 0 100 100"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <polygon
                            points="50,5 90,27.5 90,72.5 50,95 10,72.5 10,27.5"
                            stroke="currentColor"
                            stroke-width="9"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M25 65V35L50 55L75 35V65"
                            stroke="#eab308"
                            stroke-width="9"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    <span
                        class="text-4xl font-black tracking-tighter text-slate-900 leading-none -ml-1 select-none group-hover:text-yellow-500 transition-colors duration-300"
                    >
                        urfy<span
                            class="text-yellow-500 group-hover:text-slate-900 transition-colors duration-300"
                            >.</span
                        >
                    </span>
                </Link>
            </nav>

            <main>
                <slot />
            </main>

            <footer
                class="mt-8 rounded-[2rem] border border-yellow-100 bg-yellow-50/80 p-8 text-center text-slate-700 shadow-sm shadow-yellow-100"
            >
                <p class="text-base font-semibold text-slate-900">
                    © 2026 Murfy. Built with ☕, 🐝, and sheer willpower.
                </p>
                <p class="mt-3 text-sm text-slate-600">
                    Designed for clients, coders, and anything that needs a bit
                    of bee energy.
                </p>
            </footer>
        </div>
    </div>
</template>
