<script setup>
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    message: Object,
});

const deleteMessage = () => {
    if (confirm("Are you sure you want to delete this message?")) {
        router.delete(route("admin.destroy", message.id), {
            onSuccess: () => router.visit(route("admin.messages")),
        });
    }
};
</script>

<template>
    <Head title="View Message | Admin" />

    <div
        class="min-h-screen bg-slate-50 antialiased selection:bg-yellow-300 selection:text-slate-900 font-sans"
    >
        <div class="max-w-4xl mx-auto px-6 py-16 sm:px-8 sm:py-24">
            <!-- Nav -->
            <nav class="flex items-center justify-between mb-16">
                <Link
                    href="/"
                    class="text-2xl font-black tracking-tight text-slate-900 hover:text-yellow-500 transition-colors"
                >
                    Murfy<span class="text-yellow-500">.</span>
                </Link>
                <Link
                    href="/dashboard"
                    class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors"
                >
                    Dashboard
                </Link>
            </nav>

            <!-- Back Link -->
            <Link
                :href="route('admin.messages')"
                class="text-yellow-600 hover:text-yellow-700 font-semibold transition-colors mb-8 inline-block"
            >
                ← Back to Messages
            </Link>

            <!-- Message Card -->
            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8"
            >
                <!-- Header -->
                <div class="mb-8 pb-8 border-b border-slate-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-3xl font-black text-slate-900">
                                {{ message.name }}
                            </h1>
                            <p class="mt-2 text-slate-600">
                                {{ message.email }}
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold"
                            :class="{
                                'bg-green-100 text-green-700':
                                    message.status === 'sent',
                                'bg-yellow-100 text-yellow-700':
                                    message.status === 'pending',
                                'bg-red-100 text-red-700':
                                    message.status === 'failed',
                            }"
                        >
                            {{ message.status }}
                        </span>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="mb-8">
                    <h2
                        class="text-sm font-bold uppercase tracking-widest text-slate-600 mb-4"
                    >
                        Message
                    </h2>
                    <p
                        class="text-lg text-slate-700 leading-relaxed whitespace-pre-wrap"
                    >
                        {{ message.message }}
                    </p>
                </div>

                <!-- Error Message (if failed) -->
                <div
                    v-if="message.status === 'failed' && message.error_message"
                    class="mb-8"
                >
                    <h2
                        class="text-sm font-bold uppercase tracking-widest text-slate-600 mb-4"
                    >
                        Error Details
                    </h2>
                    <div
                        class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700"
                    >
                        {{ message.error_message }}
                    </div>
                </div>

                <!-- Metadata -->
                <div
                    class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-200"
                >
                    <div>
                        <h3
                            class="text-xs font-bold uppercase tracking-widest text-slate-600 mb-2"
                        >
                            Received
                        </h3>
                        <p class="text-slate-900 font-semibold">
                            {{ new Date(message.created_at).toLocaleString() }}
                        </p>
                    </div>
                    <div>
                        <h3
                            class="text-xs font-bold uppercase tracking-widest text-slate-600 mb-2"
                        >
                            Message ID
                        </h3>
                        <p class="text-slate-900 font-semibold">
                            #{{ message.id }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex gap-4">
                    <a
                        :href="`mailto:${message.email}`"
                        class="flex-1 rounded-lg bg-yellow-400 py-3 text-center font-bold text-slate-900 hover:bg-yellow-500 transition-colors"
                    >
                        Reply via Email
                    </a>
                    <button
                        @click="deleteMessage"
                        class="px-6 py-3 rounded-lg border border-red-200 bg-red-50 font-bold text-red-700 hover:bg-red-100 transition-colors"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
