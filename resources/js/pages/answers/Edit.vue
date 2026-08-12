<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface Question {
    id: number;
    title: string;
}

interface Answer {
    id: number;
    content: string;
}

const props = defineProps<{
    question: Question;
    answer: Answer;
}>();

const form = useForm({
    content: props.answer.content,
});

const submit = () => {
    form.put(`/questions/${props.question.id}/answers/${props.answer.id}`);
};
</script>

<template>
    <div class="mx-auto max-w-4xl px-6 py-10">
        <h1 class="mb-2 text-3xl font-bold text-gray-800">
            Edit Answer
        </h1>

        <p class="mb-8 text-gray-500">
            Answering:
            <span class="font-medium text-gray-700">
                {{ props.question.title }}
            </span>
        </p>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label class="mb-2 block font-medium text-gray-700">
                    Your Answer
                </label>

                <textarea
                    v-model="form.content"
                    rows="8"
                    class="w-full resize-none rounded-lg border border-gray-300 p-4 text-gray-700 focus:border-blue-500 focus:ring-blue-500"
                ></textarea>

                <p
                    v-if="form.errors.content"
                    class="mt-2 text-sm text-red-500"
                >
                    {{ form.errors.content }}
                </p>
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="cursor-pointer rounded-lg bg-blue-600 px-6 py-3 text-white transition-all duration-300 hover:scale-105 hover:bg-blue-700 hover:shadow-xl active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'Updating...'
                            : 'Update Answer'
                    }}
                </button>

                <a
                    :href="`/questions/${props.question.id}`"
                    class="rounded-lg border border-gray-300 px-6 py-3 text-gray-700 transition hover:bg-gray-100"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</template>