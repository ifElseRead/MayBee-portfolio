<template>
    <div class="mt-10">
        <h3 class="text-xl font-bold mb-4">Comments</h3>

        <div class="space-y-4 mb-8">
            <div
                v-for="comment in post.comments"
                :key="comment.id"
                class="p-4 bg-gray-50 rounded-lg flex gap-4"
            >
                <img
                    :src="comment.avatar_url"
                    :alt="comment.author_name"
                    class="w-12 h-12 rounded-full bg-gray-200 shrink-0 shadow-sm"
                />
                <div class="flex-1">
                    <div
                        class="font-semibold flex items-center justify-between"
                    >
                        <span>{{ comment.author_name }}</span>
                        <span
                            class="text-sm text-gray-500 font-normal"
                            v-if="comment.created_at"
                        >
                            {{
                                new Date(
                                    comment.created_at,
                                ).toLocaleDateString()
                            }}
                        </span>
                    </div>
                    <p class="text-gray-700 mt-2">{{ comment.content }}</p>
                </div>
            </div>
            <div
                v-if="post.comments && post.comments.length === 0"
                class="text-gray-500"
            >
                No comments yet. Be the first to share your thoughts!
            </div>
        </div>

        <form @submit.prevent="submitComment" class="space-y-4">
            <div>
                <label
                    for="author_name"
                    class="block text-sm font-medium text-gray-700"
                    >Name</label
                >
                <input
                    type="text"
                    id="author_name"
                    v-model="form.author_name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
                <div
                    v-if="form.errors.author_name"
                    class="text-red-500 text-sm mt-1"
                >
                    {{ form.errors.author_name }}
                </div>
            </div>

            <div>
                <label
                    for="email"
                    class="block text-sm font-medium text-gray-700"
                    >Email
                    <span class="text-gray-400 font-normal text-xs"
                        >(will not be published)</span
                    ></label
                >
                <input
                    type="email"
                    id="email"
                    v-model="form.email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
                <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                    {{ form.errors.email }}
                </div>
            </div>

            <div>
                <label
                    for="content"
                    class="block text-sm font-medium text-gray-700"
                    >Comment</label
                >
                <textarea
                    id="content"
                    v-model="form.content"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                ></textarea>
                <div
                    v-if="form.errors.content"
                    class="text-red-500 text-sm mt-1"
                >
                    {{ form.errors.content }}
                </div>
            </div>

            <div v-if="form.errors.recaptcha" class="text-red-500 text-sm mt-1">
                {{ form.errors.recaptcha }}
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="py-2 px-4 bg-yellow-600 text-white font-semibold rounded-md shadow-sm hover:bg-yellow-700 disabled:opacity-50 transition-colors"
            >
                {{ form.processing ? "Posting..." : "Post Comment" }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    post: Object,
});

const page = usePage();
const recaptchaSiteKey = page.props.recaptchaSiteKey || "";
const recaptchaLoaded = ref(false);

const form = useForm({
    author_name: "",
    email: "",
    content: "",
    recaptcha_token: "",
});

const loadRecaptcha = () => {
    return new Promise((resolve, reject) => {
        if (!recaptchaSiteKey) return resolve();

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
    if (!recaptchaSiteKey) return "";
    if (!recaptchaLoaded.value) await loadRecaptcha();
    return new Promise((resolve, reject) => {
        window.grecaptcha.ready(() => {
            window.grecaptcha
                .execute(recaptchaSiteKey, { action: "comment_submit" })
                .then(resolve)
                .catch(reject);
        });
    });
};

const submitComment = async () => {
    try {
        // Clear old errors before sending
        form.clearErrors();

        // Wait for token and catch any script load/execution errors gracefully
        const token = await executeRecaptcha();
        form.recaptcha_token = token;

        form.post(route("posts.comments.store", props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                // Safely reset form inputs to default state
                form.reset("content", "recaptcha_token");
            },
            onError: (errors) => {
                console.error("Inertia form submission errors:", errors);
            },
        });
    } catch (error) {
        console.error("Failed to execute reCAPTCHA:", error);
        // Set a manual form error so the user sees what went wrong
        // without crashing the native UI event runner
        form.setError(
            "recaptcha",
            "Security check failed. Please refresh and try again.",
        );
    }
};
</script>
