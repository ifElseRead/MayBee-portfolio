<script setup>
import { computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    messages: Object,
    totalMessages: Number,
    analytics: {
        type: Array,
        default: () => [],
    },
    loginLogs: {
        type: Object,
        default: () => ({ data: [] }),
    },
    systemLogs: {
        type: String,
        default: "",
    },
});

const paginationLinks = computed(() => {
    const links = props.messages?.meta?.links || props.messages?.links;
    return Array.isArray(links) ? links : [];
});

const loginPaginationLinks = computed(() => {
    const links = props.loginLogs?.meta?.links || props.loginLogs?.links;
    return Array.isArray(links) ? links : [];
});

const clearLoginLogs = () => {
    if (
        confirm("Are you sure you want to permanently delete all login logs?")
    ) {
        router.delete("/admin/login-logs", { preserveScroll: true });
    }
};
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

                <!-- Login Logs Section -->
                <div
                    class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-sm font-medium text-gray-500">
                                Recent Logins
                            </div>
                            <!-- <button
                                @click="clearLoginLogs"
                                class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-800 border border-red-200 px-3 py-1 rounded-full transition-colors"
                            >
                                Clear Logs
                            </button> -->
                        </div>
                        <div class="space-y-4">
                            <template
                                v-if="(loginLogs.data || loginLogs).length > 0"
                            >
                                <div
                                    v-for="log in loginLogs.data || loginLogs"
                                    :key="log.id"
                                    class="rounded-2xl border border-slate-200 p-4 bg-slate-50 flex items-center justify-between gap-4"
                                >
                                    <div class="overflow-hidden">
                                        <div
                                            class="font-semibold text-gray-900"
                                        >
                                            {{
                                                log.user
                                                    ? log.user.name
                                                    : log.email ||
                                                      "Unknown User"
                                            }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-600 truncate max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl"
                                            :title="log.user_agent"
                                        >
                                            {{ log.ip_address }} •
                                            {{ log.user_agent }}
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span
                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                            :class="
                                                log.status === 'success'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700'
                                            "
                                        >
                                            {{ log.status }}
                                        </span>
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{
                                                new Date(
                                                    log.created_at,
                                                ).toLocaleDateString()
                                            }}
                                            {{
                                                new Date(
                                                    log.created_at,
                                                ).toLocaleTimeString()
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div
                                v-else
                                class="rounded-2xl border border-slate-200 p-6 text-gray-600 bg-slate-50"
                            >
                                No logins recorded yet.
                            </div>

                            <!-- Login Logs Pagination -->
                            <div
                                v-if="loginPaginationLinks.length > 3"
                                class="mt-6 flex flex-wrap justify-center gap-1"
                            >
                                <template
                                    v-for="(
                                        link, index
                                    ) in loginPaginationLinks"
                                    :key="index"
                                >
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        v-html="link.label"
                                        preserve-scroll
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

                <!-- System Logs Section -->
                <!-- <div
                    class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-sm font-medium text-gray-500">
                                System Logs (Last 100 lines)
                            </div>
                        </div>
                        <div
                            class="bg-gray-900 rounded-lg p-4 overflow-x-auto max-h-96 overflow-y-auto"
                        >
                            <pre
                                class="text-xs text-green-400 font-mono"
                            ><code v-if="systemLogs">{{ systemLogs }}</code><code v-else class="text-gray-500">No logs found in storage/logs/laravel.log.</code></pre>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
