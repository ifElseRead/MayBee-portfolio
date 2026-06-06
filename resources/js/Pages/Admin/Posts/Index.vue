<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    posts: Object,
});

const statusColor = (status) => {
    return (
        {
            published: "bg-green-100 text-green-800",
            draft: "bg-yellow-100 text-yellow-800",
            rejected: "bg-red-100 text-red-800",
        }[status] || "bg-gray-100 text-gray-800"
    );
};
</script>

<template>
    <Head title="Review Posts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Review Posts
            </h2>
        </template>

        <div class="py-12">
            <div
                v-if="$page.props.flash?.success"
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6"
            >
                <div class="bg-green-100 text-green-800 p-4 rounded shadow-sm">
                    {{ $page.props.flash.success }}
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Title
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Created At
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Goes Live
                                        </th>
                                        <th
                                            scope="col"
                                            class="relative px-6 py-3"
                                        >
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="post in posts.data"
                                        :key="post.id"
                                    >
                                        <td
                                            class="px-6 py-4 max-w-[200px] sm:max-w-sm md:max-w-md lg:max-w-lg"
                                        >
                                            <div
                                                class="text-sm font-medium text-gray-900 truncate"
                                                :title="
                                                    post.title ||
                                                    post.prompt_topic
                                                "
                                            >
                                                {{
                                                    post.title ||
                                                    post.prompt_topic
                                                }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="
                                                    statusColor(post.status)
                                                "
                                            >
                                                {{ post.status }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{
                                                new Date(
                                                    post.created_at,
                                                ).toLocaleString()
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{
                                                post.published_at
                                                    ? new Date(
                                                          post.published_at,
                                                      ).toLocaleString()
                                                    : "-"
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.posts.edit',
                                                        post.id,
                                                    )
                                                "
                                                class="text-yellow-600 hover:text-yellow-900"
                                                >Review</Link
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div
                            v-if="posts.links && posts.links.length > 3"
                            class="mt-6 flex justify-center"
                        >
                            <nav
                                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination"
                            >
                                <template
                                    v-for="(link, index) in posts.links"
                                    :key="index"
                                >
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                        :class="[
                                            link.active
                                                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                            index === 0 ? 'rounded-l-md' : '',
                                            index === posts.links.length - 1
                                                ? 'rounded-r-md'
                                                : '',
                                        ]"
                                    />
                                    <span
                                        v-else
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-300"
                                        :class="[
                                            index === 0 ? 'rounded-l-md' : '',
                                            index === posts.links.length - 1
                                                ? 'rounded-r-md'
                                                : '',
                                        ]"
                                    />
                                </template>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
