<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import Seo from "@/Components/Seo.vue";

const page = usePage();
const recaptchaSiteKey = page.props.recaptchaSiteKey || "";
const recaptchaLoaded = ref(false);

const form = useForm({
    name: "",
    email: "",
    message: "",
    website: "",
    recaptcha_token: "",
});

const loadRecaptcha = () => {
    return new Promise((resolve, reject) => {
        if (!recaptchaSiteKey) {
            return resolve();
        }

        if (recaptchaLoaded.value || window.grecaptcha) {
            recaptchaLoaded.value = true;
            return resolve();
        }

        const script = document.createElement("script");
        script.src = `https://www.google.com/recaptcha/api.js?render=${recaptchaSiteKey}`;
        script.async = true;
        script.defer = true;
        script.onload = () => {
            recaptchaLoaded.value = true;
            resolve();
        };
        script.onerror = () =>
            reject(new Error("Could not load reCAPTCHA script"));
        document.head.appendChild(script);
    });
};

const executeRecaptcha = async () => {
    if (!recaptchaSiteKey) {
        return "";
    }

    if (!recaptchaLoaded.value) {
        await loadRecaptcha();
    }

    return new Promise((resolve, reject) => {
        window.grecaptcha.ready(() => {
            window.grecaptcha
                .execute(recaptchaSiteKey, { action: "contact" })
                .then(resolve)
                .catch(reject);
        });
    });
};

const submit = async () => {
    if (recaptchaSiteKey) {
        try {
            form.recaptcha_token = await executeRecaptcha();
        } catch (error) {
            console.error(error);
        }
    }

    form.post(route("contact.store"), {
        onSuccess: () => {
            alert("The hive has received your message 🐝");
            form.reset();
        },
    });
};
</script>

<template>
    <Seo
        title="Contact John | Murfy.uk"
        description="Get in touch to discuss web application development, Laravel/Vue technical architecture, or beekeeping logistics."
        url="https://murfy.uk/contact"
        image="/images/contact-preview.jpg"
    />

    <PublicLayout>
        <div class="relative overflow-hidden">
            <!-- Background Glow -->
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(250,204,21,0.12),transparent_30%)]"
            ></div>

            <div class="relative max-w-5xl mx-auto px-6 py-16 sm:px-8 sm:py-24">
                <!-- Hero -->
                <section
                    class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] items-start"
                >
                    <!-- Left -->
                    <div>
                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-yellow-200 bg-yellow-100/70 px-4 py-2 text-sm font-semibold text-yellow-700 shadow-sm backdrop-blur"
                        >
                            🐝 Available for freelance work
                        </div>

                        <h1
                            class="mt-6 text-5xl sm:text-6xl font-black tracking-tight text-slate-900 leading-[1.05]"
                        >
                            Let’s build
                            <span class="relative inline-block">
                                something cool
                                <svg
                                    class="absolute -bottom-2 left-0 w-full"
                                    viewBox="0 0 200 12"
                                    fill="none"
                                >
                                    <path
                                        d="M2 10C40 2 80 2 120 8C150 12 180 10 198 6"
                                        stroke="#FACC15"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </span>
                        </h1>

                        <p
                            class="mt-8 max-w-2xl text-xl leading-relaxed text-slate-600"
                        >
                            Whether it’s Laravel, Vue, APIs, Tailwind, or
                            something completely chaotic, I’m always interested
                            in new projects and interesting conversations.
                        </p>

                        <!-- Quick Contact -->
                        <div
                            class="mt-10 flex flex-wrap gap-4 text-sm font-medium"
                        >
                            <div
                                class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm"
                            >
                                ☕ Powered by coffee
                            </div>

                            <div
                                class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm"
                            >
                                🐝 Bee-friendly developer
                            </div>

                            <div
                                class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm"
                            >
                                ⚡ Laravel + Vue specialist
                            </div>
                        </div>
                    </div>

                    <!-- Form Card -->
                    <div
                        class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60"
                    >
                        <!-- Decorative Gradient -->
                        <div
                            class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-yellow-300 via-yellow-400 to-amber-500"
                        ></div>

                        <div class="mb-8">
                            <h2
                                class="text-2xl font-black tracking-tight text-slate-900"
                            >
                                Send a message ✉️
                            </h2>

                            <p class="mt-2 text-slate-500 leading-relaxed">
                                Tell me about your project, idea, or debugging
                                nightmare.
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div
                                class="pointer-events-none absolute left-[-9999px] top-0 opacity-0"
                                aria-hidden="true"
                            >
                                <label for="website">Website</label>
                                <input
                                    id="website"
                                    v-model="form.website"
                                    name="website"
                                    autocomplete="off"
                                    type="text"
                                />
                            </div>

                            <input
                                type="hidden"
                                name="recaptcha_token"
                                v-model="form.recaptcha_token"
                            />

                            <!-- Name -->
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold uppercase tracking-widest text-slate-700"
                                >
                                    Name
                                </label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="John Bee"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 placeholder:text-slate-400 outline-none transition-all duration-200 focus:border-yellow-400 focus:bg-white focus:ring-4 focus:ring-yellow-400/20"
                                    required
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="text-sm font-semibold text-red-500"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold uppercase tracking-widest text-slate-700"
                                >
                                    Email
                                </label>

                                <input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="john@example.com"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 placeholder:text-slate-400 outline-none transition-all duration-200 focus:border-yellow-400 focus:bg-white focus:ring-4 focus:ring-yellow-400/20"
                                    required
                                />
                                <div
                                    v-if="form.errors.email"
                                    class="text-sm font-semibold text-red-500"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold uppercase tracking-widest text-slate-700"
                                >
                                    Message
                                </label>

                                <textarea
                                    v-model="form.message"
                                    rows="6"
                                    placeholder="Tell me about your project..."
                                    class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 placeholder:text-slate-400 outline-none transition-all duration-200 focus:border-yellow-400 focus:bg-white focus:ring-4 focus:ring-yellow-400/20"
                                    required
                                ></textarea>
                                <div
                                    v-if="form.errors.message"
                                    class="text-sm font-semibold text-red-500"
                                >
                                    {{ form.errors.message }}
                                </div>
                            </div>

                            <!-- Submit -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="group relative w-full overflow-hidden rounded-2xl bg-yellow-400 py-4 text-lg font-black text-slate-900 shadow-lg shadow-yellow-400/20 transition-all duration-300 hover:-translate-y-1 hover:bg-yellow-500 active:scale-[0.99]"
                            >
                                <span
                                    class="relative z-10 flex items-center justify-center gap-2"
                                >
                                    Send Carrier Bee 🐝
                                </span>

                                <div
                                    class="absolute inset-0 translate-y-full bg-white/20 transition-transform duration-300 group-hover:translate-y-0"
                                ></div>
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Extra Card -->
                <section
                    class="mt-12 rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <div
                        class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div>
                            <h2
                                class="text-2xl font-black tracking-tight text-slate-900"
                            >
                                Status and other ways to reach me
                            </h2>

                            <p class="mt-2 text-slate-600">
                                Or summon me via coffee and vague bug reports.
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <a
                                href="#"
                                class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 transition-all hover:-translate-y-1 hover:border-yellow-300 hover:bg-yellow-50"
                            >
                                <div class="text-sm text-slate-500">
                                    Total National Hives
                                </div>
                                <div
                                    class="mt-1 text-3xl font-black text-yellow-500 text-center"
                                >
                                    4
                                </div>
                            </a>

                            <a
                                href="#"
                                class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 transition-all hover:-translate-y-1 hover:border-yellow-300 hover:bg-yellow-50"
                            >
                                <div class="text-sm text-slate-500">Email</div>
                                <div class="mt-1 font-bold text-slate-900">
                                    Nope
                                </div>
                            </a>

                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4"
                            >
                                <div class="text-sm text-slate-500">
                                    Current Status
                                </div>
                                <div class="mt-1 font-bold text-slate-900">
                                    Puury.com
                                </div>
                                <div
                                    class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-amber-700 border border-amber-200"
                                >
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"
                                        ></span>
                                        <span
                                            class="relative inline-flex h-2 w-2 rounded-full bg-amber-500"
                                        ></span>
                                    </span>
                                    Debugging
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="mt-8 text-center">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 rounded-full bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-950 shadow-sm shadow-yellow-200 transition hover:bg-yellow-500 hover:text-slate-950"
                    >
                        ↩︎ Back home
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
