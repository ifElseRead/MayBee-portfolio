<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    post: Object,
});

const promptForm = useForm({
    topic: props.post.prompt_topic || "",
});

const formatDateForInput = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    const pad = (n) => n.toString().padStart(2, "0");
    return `${date.getUTCFullYear()}-${pad(date.getUTCMonth() + 1)}-${pad(date.getUTCDate())}T${pad(date.getUTCHours())}:${pad(date.getUTCMinutes())}`;
};

const detailsForm = useForm({
    created_at: formatDateForInput(props.post.created_at),
    published_at: formatDateForInput(props.post.published_at),
});

const updateDetails = () => {
    detailsForm.patch(route("admin.posts.update", props.post.id), {
        preserveScroll: true,
    });
};

const approvePost = () => {
    useForm({ status: "published" }).patch(
        route("admin.posts.update", props.post.id),
        {
            preserveScroll: true,
        },
    );
};

const rejectPost = () => {
    useForm({ status: "rejected" }).patch(
        route("admin.posts.update", props.post.id),
        {
            preserveScroll: true,
        },
    );
};

const unpublishPost = () => {
    useForm({ status: "draft" }).patch(
        route("admin.posts.update", props.post.id),
        {
            preserveScroll: true,
        },
    );
};

const deletePost = () => {
    if (confirm("Are you sure you want to delete this post?")) {
        useForm({}).delete(route("admin.posts.destroy", props.post.id));
    }
};

const regeneratePost = () => {
    promptForm.post(route("admin.posts.regenerate", props.post.id));
};

const generateImage = async () => {
    const prompt = `Please generate a modern, minimal, high-quality editorial abstract vector graphic banner for the following article:\n\nTitle: ${props.post.title}\n\nSummary: ${props.post.summary}\n\nContent:\n${props.post.html_body}\n\ngenerate a banner for this article`;

    try {
        await navigator.clipboard.writeText(prompt);
        window.open("https://gemini.google.com/app", "_blank");
    } catch (err) {
        console.error("Failed to copy text: ", err);
        alert("Failed to copy the article to the clipboard. Please try again.");
    }
};
</script>

<template>
    <Head :title="`Review: ${post.title || 'Pending Generation'}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Review Post
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

            <div
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8"
            >
                <!-- Main Post Content -->
                <div
                    class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <article class="p-8">
                        <header class="mb-8">
                            <h1
                                class="text-3xl font-extrabold text-slate-900 mb-2"
                            >
                                {{ post.title || "Pending Generation" }}
                            </h1>
                            <p class="text-md text-slate-600">
                                {{ post.summary }}
                            </p>
                            <img
                                v-if="post.banner_image"
                                :src="`/storage/blog-banners/${post.banner_image}`"
                                :alt="post.title"
                                class="mt-4 w-full rounded-lg shadow-sm border border-slate-200"
                            />
                        </header>
                        <div
                            v-if="post.html_body"
                            class="prose prose-slate max-w-none"
                            v-html="post.html_body"
                        ></div>
                        <div
                            v-else
                            class="text-slate-500 italic p-6 bg-slate-50 rounded-lg text-center border border-slate-200"
                        >
                            Content is empty or still being generated...
                        </div>
                    </article>
                </div>

                <!-- Actions & Prompt Panel -->
                <div class="space-y-6">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <h3 class="text-lg font-bold text-slate-900 mb-4">
                            Actions
                        </h3>
                        <div class="flex flex-wrap gap-4">
                            <button
                                v-if="post.status !== 'published'"
                                @click="approvePost"
                                class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75"
                            >
                                Approve & Publish
                            </button>
                            <button
                                v-if="post.status === 'published'"
                                @click="unpublishPost"
                                class="px-4 py-2 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-75"
                            >
                                Unpublish
                            </button>
                            <button
                                v-if="post.status !== 'rejected'"
                                @click="rejectPost"
                                class="px-4 py-2 bg-slate-500 text-white font-semibold rounded-lg shadow-md hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-opacity-75"
                            >
                                Reject
                            </button>
                            <button
                                @click="deletePost"
                                class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75"
                            >
                                Delete
                            </button>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            Post Details
                        </h3>
                        <form @submit.prevent="updateDetails">
                            <div class="mb-4">
                                <label
                                    for="created_at"
                                    class="block text-sm font-medium text-gray-700"
                                    >Created At</label
                                >
                                <input
                                    type="datetime-local"
                                    id="created_at"
                                    v-model="detailsForm.created_at"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    :disabled="detailsForm.processing"
                                />
                            </div>
                            <div class="mb-4">
                                <label
                                    for="published_at"
                                    class="block text-sm font-medium text-gray-700"
                                    >Published At</label
                                >
                                <input
                                    type="datetime-local"
                                    id="published_at"
                                    v-model="detailsForm.published_at"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    :disabled="detailsForm.processing"
                                />
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    :disabled="detailsForm.processing"
                                    class="w-full px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75"
                                >
                                    {{
                                        detailsForm.processing
                                            ? "Updating..."
                                            : "Update Details"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            Improve & Regenerate
                        </h3>
                        <p class="text-sm text-slate-600 mb-4">
                            Edit the original prompt and dispatch a new job to
                            regenerate this post. The current version will be
                            updated.
                        </p>

                        <form @submit.prevent="regeneratePost">
                            <div>
                                <label
                                    for="topic"
                                    class="block text-sm font-medium text-gray-700"
                                    >AI Prompt</label
                                >
                                <textarea
                                    id="topic"
                                    v-model="promptForm.topic"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm"
                                    :disabled="promptForm.processing"
                                ></textarea>
                            </div>
                            <div class="mt-4">
                                <button
                                    type="submit"
                                    :disabled="promptForm.processing"
                                    class="w-full px-4 py-2 bg-yellow-500 text-slate-900 font-semibold rounded-lg shadow-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-75"
                                >
                                    {{
                                        promptForm.processing
                                            ? "Regenerating..."
                                            : "Regenerate with New Prompt"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            Generate Image
                        </h3>
                        <p class="text-sm text-slate-600 mb-4">
                            Copy the article details and open Gemini to manually
                            generate a banner.
                        </p>
                        <button
                            @click.prevent="generateImage"
                            class="w-full px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-opacity-75"
                        >
                            Generate Image in Gemini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
