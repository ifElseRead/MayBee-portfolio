<script setup>
import { useForm } from '@inertiajs/vue3';
import PublicLayout from "@/Layouts/PublicLayout.vue";
import Seo from "@/Components/Seo.vue";

const form = useForm({
    topic: '',
});

const submit = () => {
    form.post('/admin/posts/generate', {
        preserveScroll: true,
        onSuccess: () => form.reset('topic'),
    });
};
</script>

<template>
    <Seo title="AI Blog Orchestrator" />
    
    <PublicLayout>
        <div class="max-w-2xl mx-auto p-8 bg-white rounded-2xl shadow-sm border border-slate-200 mt-12 sm:mt-24">
            <h2 class="text-2xl font-extrabold text-slate-900 mb-2">AI Blog Orchestrator 🤖</h2>
            <p class="text-slate-600 mb-8">Enter a topic and let Gemini draft the content and paint the banner.</p>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="topic" class="block text-sm font-bold text-slate-700 mb-1">Blog Topic</label>
                    <input
                        id="topic"
                        v-model="form.topic"
                        type="text"
                        placeholder="e.g., Implementing Event Sourcing in Laravel"
                        class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 disabled:bg-slate-50 disabled:text-slate-500 transition-colors"
                        :disabled="form.processing"
                        required
                    />
                    <div v-if="form.errors.topic" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.topic }}</div>
                </div>

                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-3 bg-yellow-400 border border-transparent rounded-full font-bold text-slate-900 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-150 disabled:opacity-75 disabled:cursor-not-allowed shadow-sm shadow-yellow-200 active:scale-95"
                    >
                        {{ form.processing ? 'Gemini is drafting & painting...' : 'Generate Post ↗' }}
                    </button>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>