<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: '',
    description: '',
    tags: [] as string[]
});

const tagInput = ref('');

function submit() {
    form.tags = tagInput.value
        .split(',')
        .map((tag) => tag.trim())
        .filter((tag) => tag !== '');


    form.post('/questions');
}
</script>

<template>
    <div class="mx-auto max-w-4xl py-10">
        <h1 class="mb-8 text-3xl font-bold">Ask a Question</h1>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label class="mb-2 block font-medium"> Title </label>

                <input
                    v-model="form.title"
                    type="text"
                    class="w-full rounded-lg border p-3"
                />
            </div>

            <div>
                <label class="mb-2 block font-medium"> Description </label>

                <textarea
                    v-model="form.description"
                    rows="10"
                    class="w-full rounded-lg border p-3"
                ></textarea>
            </div>

            <div>
                <label class="mb-2 block font-medium">Tags</label>

                <input
                    v-model="tagInput"
                    type="text"
                    placeholder="e.g. Java, Laravel, Vue.js"
                    class="w-full rounded-lg border p-3"
                />

                <p class="mt-2 text-sm text-gray-500">
                    Separate tags with commas.
                </p>
            </div>

            <button
                type="submit"
                class="cursor-pointer rounded-lg bg-blue-600 px-6 py-3 text-white transition-all duration-300 hover:scale-105 hover:bg-blue-700 hover:shadow-xl active:scale-95"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Posting...' : 'Post Question' }}
            </button>
        </form>

       
    </div>
</template>
