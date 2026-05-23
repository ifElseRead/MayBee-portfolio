<script setup>
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    messages: Object,
});

const deleteMessage = (id) => {
    if (confirm("Are you sure you want to delete this message?")) {
        router.delete(route("admin.destroy", id));
    }
};
</script>

<template>
    <Head title="Contact Messages | Admin" />

    <div
        class="min-h-screen bg-slate-50 antialiased selection:bg-yellow-300 selection:text-slate-900 font-sans"
    >
        <div class="max-w-6xl mx-auto px-6 py-16 sm:px-8 sm:py-24">
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

            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-4xl font-black tracking-tight text-slate-900 mb-2">
                    Contact Messages
                </h1>
                <p class="text-lg text-slate-600">
                    All contact form submissions are logged here.
                </p>
            </div>

            <!-- Messages Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-slate-50 border-b border-slate-200 text-slate-900 font-bold"
                        >
                            <tr>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="message in messages.data"
                                :key="message.id"
                                class="border-b border-slate-200 hover:bg-slate-50 transition-colors"
                            >
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ message.name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ message.email }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold"
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
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{
                                        new Date(message.created_at).toLocaleDateString()
                                    }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <Link
                                            :href="route('admin.show', message.id)"
                                            class="text-yellow-600 hover:text-yellow-700 font-semibold transition-colors"
                                        >
                                            View
                                        </Link>
                                        <button
                                            @click="deleteMessage(message.id)"
                                            class="text-red-600 hover:text-red-700 font-semibold transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div
                    v-if="messages.data.length === 0"
                    class="px-6 py-12 text-center text-slate-500"
                >
                    <p>No contact messages yet.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="messages.links && messages.links.length > 3"
                class="mt-8 flex justify-center gap-2"
            >
                <Link
                    v-for="link in messages.links"
                    :key="link.label"
                    :href="link.url"
                    class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-semibold transition-all"
                    :class="{
                        'bg-yellow-400 text-slate-900 border-yellow-400':
                            link.active,
                        'text-slate-600 hover:border-slate-300': !link.active,
                    }"
                    v-html="link.label"
                ></Link>
            </div>
        </div>
    </div>
</template>
