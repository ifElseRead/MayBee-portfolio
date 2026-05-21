<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import Seo from "@/Components/Seo.vue";

// Reactive State
const clickCount = ref(0);
const titleText = ref("Hi, I'm John 👋");
const subTitleText = ref(
    "I turn coffee into code and occasionally hang out with bees. 🐝☕",
);
const isAgitated = ref(false);
const isChaosMode = ref(false);

const titles = ["Not Jon", "But John", "Still, just John 😎"];

const spawnBee = () => {
    const bee = document.createElement("div");
    bee.innerText = "🐝";
    bee.className = "animate-swarm";

    // Randomize placement
    bee.style.left = Math.random() * 100 + "vw";
    bee.style.top = Math.random() * 100 + "vh";

    // Randomize size and timing for a "swarm" depth effect
    const size = Math.random() * 20 + 20;
    bee.style.fontSize = `${size}px`;
    bee.style.animationDuration = Math.random() * 3 + 2 + "s";
    bee.style.animationDelay = Math.random() * 2 + "s";

    // Add a blur to smaller (further) bees
    if (size < 25) bee.style.filter = "blur(1px)";

    document.body.appendChild(bee);

    // Cleanup after animation cycle (8s to match the long swarm)
    setTimeout(() => bee.remove(), 8000);
};

const handleTrigger = () => {
    clickCount.value++;

    if (clickCount.value <= titles.length) {
        titleText.value = titles[clickCount.value - 1];
    } else if (clickCount.value === 4) {
        subTitleText.value =
            "I am actually 60,000 bees in a trench coat masquerading as a web developer. 🐝🧥";
        titleText.value = "Bzzzzzz 🐝";
    } else if (clickCount.value === 7) {
        subTitleText.value =
            "Okay, stop poking me. The hive is getting agitated. 🛑🐝";
        isAgitated.value = true;
    } else if (clickCount.value === 11) {
        // ACTIVATE TACTICAL SWARM
        titleText.value = "ABORT! HIVE COMPROMISED! 🚨";
        subTitleText.value = "DEPLOYING TACTICAL SWARM! 🐝";
        isChaosMode.value = true;

        // Create the swarm
        for (let i = 0; i < 40; i++) {
            spawnBee();
        }

        // Reset everything after 8 seconds
        setTimeout(() => {
            clickCount.value = 0;
            isChaosMode.value = false;
            isAgitated.value = false;
            titleText.value = "Hi, I'm John 👋";
            subTitleText.value =
                "I turn coffee into code and occasionally hang out with bees. 🐝☕";
        }, 8000);
    }
};
</script>

<template>
    <Seo
        title="John | Laravel Developer & Beekeeper"
        description="Building Laravel and Vue applications with a focus on clean architecture and practical solutions."
        url="https://murfy.uk"
    />

    <PublicLayout>
        <div
            class="transition-colors duration-500"
            :class="isChaosMode ? 'bg-yellow-100 animate-pulse' : 'bg-slate-50'"
        >
            <div class="max-w-5xl mx-auto py-10 px-2 sm:py-24 sm:px-8">
                <!-- Header Section -->
                <header class="mb-12">
                    <h1
                        @click="handleTrigger"
                        class="text-4xl sm:text-4xl mb-6 font-extrabold tracking-tight text-slate-900 mb-4 cursor-pointer select-none hover:text-yellow-500 transition-all duration-300 inline-block active:scale-95"
                    >
                        {{ titleText }}
                    </h1>
                    <p
                        class="text-xl sm:text-2xl transition-all duration-300 leading-relaxed"
                        :class="
                            isAgitated
                                ? 'text-red-600 font-bold'
                                : 'text-slate-600'
                        "
                    >
                        {{ subTitleText }}
                    </p>
                </header>

                <!-- Main Content -->
                <main class="space-y-12">
                    <section>
                        <h2
                            class="text-2xl font-bold text-slate-900 mb-3 border-b-2 border-yellow-400 inline-block pb-1"
                        >
                            The Intro
                        </h2>
                        <p class="text-lg text-slate-700 leading-relaxed mt-2">
                            I’m an Irish guy currently loving life in the UK
                            🇮🇪🇬🇧. I build modern web apps using PHP/Laravel,
                            JS/Vue, and my one true love... Tailwind CSS 💟.
                        </p>
                    </section>

                    <section>
                        <h2
                            class="text-2xl font-bold text-slate-900 mb-3 border-b-2 border-yellow-400 inline-block pb-1"
                        >
                            What I Do
                        </h2>
                        <div
                            class="text-lg text-slate-700 leading-relaxed mt-2 space-y-4"
                        >
                            <p>
                                I care deeply about clean code, blazing-fast
                                performance, and building things that actually
                                make logical sense.
                            </p>
                            <p>
                                I spend my days tinkering with APIs, wrestling
                                with dashboards, and launching "experiments"
                                that may or may not occasionally break things
                                ⚡.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2
                            class="text-2xl font-bold text-slate-900 mb-3 border-b-2 border-yellow-400 inline-block pb-1"
                        >
                            Off the Keyboard
                        </h2>
                        <p class="text-lg text-slate-700 leading-relaxed mt-2">
                            When I'm not untangling spaghetti code, I'm a new
                            beekeeper 🐝. If you think debugging is hard, try
                            reasoning with 60,000 stinging insects.
                        </p>
                    </section>

                    <!-- Toolbox Card -->
                    <section
                        class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200 mt-8 hover:shadow-md transition-shadow"
                    >
                        <h2 class="text-2xl font-bold text-slate-900 mb-1">
                            The Toolbox 🛠️
                        </h2>
                        <p class="text-sm text-slate-500 italic mb-6">
                            (aka the things I Google on a daily basis)
                        </p>

                        <ul class="space-y-4 text-lg">
                            <li
                                class="flex flex-col sm:flex-row sm:items-start"
                            >
                                <span
                                    class="font-bold w-48 shrink-0 text-slate-900"
                                    >📦 The Staples:</span
                                >
                                <span class="text-slate-600"
                                    >PHP, JavaScript (ES6+), SQL, Git & GitHub,
                                    Pest (Test-Driven Development)</span
                                >
                            </li>
                            <li
                                class="flex flex-col sm:flex-row sm:items-start"
                            >
                                <span
                                    class="font-bold w-48 shrink-0 text-slate-900"
                                    >🏋️ The Heavy Lifters:</span
                                >
                                <span class="text-slate-600"
                                    >Laravel, Vue 3, Inertia.js, Tailwind
                                    CSS</span
                                >
                            </li>

                            <li
                                class="flex flex-col sm:flex-row sm:items-start"
                            >
                                <span
                                    class="font-bold w-48 shrink-0 text-slate-900"
                                    >🍯 The Glue:</span
                                >
                                <span class="text-slate-600"
                                    >RESTful APIs, NGINX, Certbot, Webhooks &
                                    Willpower</span
                                >
                            </li>
                        </ul>
                    </section>
                </main>

                <div class="mt-10 text-center">
                    <Link
                        href="/contact"
                        class="inline-flex items-center gap-2 rounded-full bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-950 shadow-sm shadow-yellow-200 transition hover:bg-yellow-500 hover:text-slate-950"
                    >
                        Contact me ↗
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style>
@keyframes fly {
    0% {
        transform: translate(0, 0) rotate(0deg);
    }
    25% {
        transform: translate(100px, -50px) rotate(15deg);
    }
    50% {
        transform: translate(200px, 50px) rotate(-15deg);
    }
    75% {
        transform: translate(-100px, 100px) rotate(10deg);
    }
    100% {
        transform: translate(0, 0) rotate(0deg);
    }
}

.animate-swarm {
    animation: fly linear infinite;
    pointer-events: none;
    position: fixed;
    z-index: 50;
    will-change: transform;
    user-select: none;
}
</style>
