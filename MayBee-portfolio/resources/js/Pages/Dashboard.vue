<script setup>
import { computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    messages: Object,
    totalMessages: Number,
    analytics: {
        type: Array,
        default: () => [],
    },
});

const paginationLinks = computed(() => {
    const links = props.messages?.meta?.links || props.messages?.links;
    return Array.isArray(links) ? links : [];
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Analytics Section -->
                <div
                    class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-4">
                            Website Traffic (Last 7 Days)
                        </div>
                        <div
                            v-if="analytics && analytics.length > 0"
                            class="flex flex-wrap gap-4"
                        >
                            <div
                                v-for="(day, index) in analytics"
                                :key="index"
                                class="flex-1 min-w-[80px] bg-slate-50 border border-slate-100 rounded-lg p-3 text-center"
                            >
                                <div class="text-xs text-gray-500 mb-1">
                                    {{ day.date }}
                                </div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ day.visitors }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ day.pageViews }} views
                                </div>
                            </div>
                        </div>
                        <div
                            v-else
                            class="text-sm text-gray-400 p-4 bg-slate-50 rounded-lg border border-slate-100 text-center"
                        >
                            Analytics data is not available yet. Please ensure
                            Google Analytics is configured.
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">
                                Received messages
                            </div>
                            <div
                                class="mt-3 text-3xl font-semibold text-gray-900"
                            >
                                {{ totalMessages }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg sm:col-span-2"
                    >
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Latest messages
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Most recent contact form submissions.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                <template
                                    v-if="
                                        (messages.data || messages).length > 0
                                    "
                                >
                                    <div
                                        v-for="message in messages.data ||
                                        messages"
                                        :key="message.id"
                                        class="rounded-2xl border border-slate-200 p-4 bg-slate-50"
                                    >
                                        <div
                                            class="flex items-center justify-between gap-4"
                                        >
                                            <div>
                                                <div
                                                    class="font-semibold text-gray-900"
                                                >
                                                    {{ message.name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-600"
                                                >
                                                    {{ message.email }}
                                                </div>
                                            </div>
                                            <span
                                                class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700"
                                            >
                                                {{ message.status }}
                                            </span>
                                        </div>
                                        <div class="mt-3 text-sm text-gray-600">
                                            {{ message.message }}
                                        </div>
                                        <div class="mt-3 text-xs text-gray-500">
                                            Received
                                            {{
                                                new Date(
                                                    message.created_at,
                                                ).toLocaleDateString()
                                            }}
                                        </div>
                                    </div>
                                </template>
                                <div
                                    v-else
                                    class="rounded-2xl border border-slate-200 p-6 text-gray-600 bg-slate-50"
                                >
                                    No messages received yet.
                                </div>

                                <!-- Pagination -->
                                <div
                                    v-if="paginationLinks.length > 3"
                                    class="mt-6 flex flex-wrap justify-center gap-1"
                                >
                                    Hello
                                    <template
                                        v-for="(link, index) in paginationLinks"
                                        :key="index"
                                    >
                                        <Link
                                            v-if="link.url"
                                            :href="link.url"
                                            v-html="link.label"
                                            class="px-3 py-1 text-sm border rounded-md transition-colors"
                                            :class="
                                                link.active
                                                    ? 'bg-yellow-500 text-white border-yellow-500'
                                                    : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'
                                            "
                                        />
                                        <span
                                            v-else
                                            v-html="link.label"
                                            class="px-3 py-1 text-sm border rounded-md bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200"
                                        ></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
