<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    message: "",
});

const submit = () => {
    // For now, we simulate success
    alert("The bees are delivering your message! 🐝");
    form.reset();
};
</script>

<template>
    <Head title="Contact | John Murphy" />

    <div
        class="min-h-screen bg-slate-50 text-slate-800 antialiased font-sans selection:bg-yellow-300"
    >
        <div class="max-w-3xl mx-auto py-16 px-6 sm:py-24 sm:px-8">
            <nav class="flex justify-end gap-6 mb-8">
                <Link
                    href="/"
                    class="text-sm font-bold text-slate-400 hover:text-yellow-600 transition-colors"
                    >Home</Link
                >
                <Link
                    href="/contact"
                    class="text-sm font-bold text-slate-900 hover:text-yellow-600 transition-colors underline decoration-yellow-400 underline-offset-4"
                    >Contact</Link
                >
            </nav>

            <header class="mb-12">
                <h1
                    class="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900 mb-4"
                >
                    Get in Touch 📩
                </h1>
                <p class="text-xl text-slate-600 leading-relaxed">
                    Have a question about Laravel, beekeeping, or a domain? Drop
                    me a line below.
                </p>
            </header>

            <main>
                <form
                    @submit.prevent="submit"
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 space-y-6"
                >
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wide"
                            >Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-yellow-400 focus:ring-yellow-400 transition-all"
                            placeholder="John Doe"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wide"
                            >Email</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-yellow-400 focus:ring-yellow-400 transition-all"
                            placeholder="john@example.com"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wide"
                            >Message</label
                        >
                        <textarea
                            v-model="form.message"
                            rows="5"
                            required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-yellow-400 focus:ring-yellow-400 transition-all"
                            placeholder="Tell me about your project..."
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-4 bg-yellow-400 text-slate-900 font-bold rounded-xl hover:bg-yellow-500 hover:-translate-y-1 transition-all active:scale-95 shadow-md shadow-yellow-200"
                    >
                        Send to the Hive
                    </button>
                </form>
            </main>

            <footer
                class="mt-16 pt-8 border-t border-slate-200 text-center text-slate-500"
            >
                <p>&copy; 2026 John Murphy. Built with sheer willpower.</p>
            </footer>
        </div>
    </div>
</template>
