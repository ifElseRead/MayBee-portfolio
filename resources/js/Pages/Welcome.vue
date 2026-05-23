<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";

const clickCount = ref(0);
const titleText = ref("Hi, I'm John 👋");
const subTitleText = ref(
    "I turn coffee into code and occasionally hang out with bees. 🐝☕",
);
const isChaosMode = ref(false);

const titles = ["Not Jon", "But John", "Still, just John 😎"];

const spawnBee = () => {
    const bee = document.createElement("div");
    bee.innerText = "🐝";
    bee.className = "animate-swarm";
    bee.style.left = `${Math.random() * 100}vw`;
    bee.style.top = `${Math.random() * 100}vh`;
    bee.style.fontSize = `${Math.random() * 20 + 20}px`;
    bee.style.animationDuration = `${Math.random() * 3 + 2}s`;
    document.body.appendChild(bee);
    setTimeout(() => bee.remove(), 4000);
};

const handleTrigger = () => {
    clickCount.value++;
    if (clickCount.value <= titles.length) {
        titleText.value = titles[clickCount.value - 1];
    } else if (clickCount.value === 4) {
        subTitleText.value = "I am actually 50,000 bees in a trench coat. 🐝🧥";
    } else if (clickCount.value === 10) {
        isChaosMode.value = true;
        titleText.value = "ABORT! HIVE COMPROMISED! 🚨";
        for (let i = 0; i < 40; i++) spawnBee();
        setTimeout(() => {
            isChaosMode.value = false;
            clickCount.value = 0;
            titleText.value = "Hi, I'm John 👋";
            subTitleText.value =
                "I turn coffee into code and occasionally hang out with bees. 🐝☕";
        }, 4000);
    }
};
</script>

<template>
    <Head title="John | Beekeeper & Dev" />
    <div
        :class="[
            'min-h-screen p-12 transition-all duration-500',
            isChaosMode ? 'bg-yellow-100 animate-pulse' : 'bg-slate-50',
        ]"
    >
        <div class="max-w-2xl mx-auto">
            <h1
                @click="handleTrigger"
                class="text-5xl font-black cursor-pointer select-none hover:text-yellow-600"
            >
                {{ titleText }}
            </h1>
            <p class="text-xl mt-4 text-slate-600">{{ subTitleText }}</p>

            <section class="mt-12 space-y-6 text-slate-700">
                <p>
                    I build modern web apps using PHP/Laravel and Tailwind CSS.
                </p>
                <div
                    class="p-6 bg-white rounded-xl shadow-sm border border-slate-200"
                >
                    <h3 class="font-bold text-slate-900">The Hive Logic</h3>
                    <p class="text-sm">
                        Click my name above to see what happens when the bees
                        get restless.
                    </p>
                </div>
            </section>
        </div>
    </div>
</template>

<style>
@keyframes flyAround {
    0% {
        transform: translate(0, 0) rotate(0deg);
    }
    50% {
        transform: translate(150px, -50px) rotate(15deg);
    }
    100% {
        transform: translate(0, 0) rotate(0deg);
    }
}
.animate-swarm {
    position: fixed;
    z-index: 9999;
    pointer-events: none;
    animation: flyAround linear infinite;
}
</style>
