<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
});

function submit() {
    form.post('/questions');
}
</script>

<template>
    <div class="max-w-4xl mx-auto py-10">

        <h1 class="text-3xl font-bold mb-8">
            Ask a Question
        </h1>

        <form @submit.prevent="submit" class="space-y-6">

            <div>
                <label class="block mb-2 font-medium">
                    Title
                </label>

                <input
                    v-model="form.title"
                    type="text"
                    class="w-full rounded-lg border p-3"
                />
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Description
                </label>

                <textarea
                    v-model="form.description"
                    rows="10"
                    class="w-full rounded-lg border p-3"
                ></textarea>
            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg
                       hover:bg-blue-700
                        hover:shadow-xl
                        hover:scale-105
                        active:scale-95
                        transition-all
                        duration-300
                        cursor-pointer"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Posting...' : 'Post Question' }} 
            </button>

        </form>

    </div>
</template>