<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface Question {
    id: number;
    title: string;
    description: string;
}

const props = defineProps<{
    question: Question;
}>();

const form = useForm({
    title: props.question.title,
    description: props.question.description,
});

const submit = () => {
    form.put(`/questions/${props.question.id}`);
};
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-4xl px-6 py-10">
        <h1 class="mb-8 text-2xl font-bold sm:text-3xl">Edit Question</h1>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label class="mb-2 block font-medium">
                    Title
                </label>

                <input
                    v-model="form.title"
                    type="text"
                    class="w-full rounded-lg border p-3"
                />

                <p
                    v-if="form.errors.title"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.title }}
                </p>
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Description
                </label>

                <textarea
                    v-model="form.description"
                    rows="10"
                    class="w-full rounded-lg border p-3"
                ></textarea>

                <p
                    v-if="form.errors.description"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.description }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="cursor-pointer rounded-lg bg-blue-600 px-6 py-3 text-white transition-all duration-300 hover:scale-105 hover:bg-blue-700 hover:shadow-xl active:scale-95"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? 'Updating...'
                            : 'Update Question'
                    }}
                </button>

                <a
                    :href="`/questions/${props.question.id}`"
                    class="rounded-lg border px-6 py-3 transition hover:bg-gray-100"
                >
                    Cancel
                </a>
            </div>
        </form>
        </div>
        </div>
    </div>
</template>