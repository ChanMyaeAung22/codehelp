<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
}

interface Question {
    id: number;
    title: string;
}

interface Answer {
    id: number;
    content: string;
    created_at: string;
    user: User;
    question: Question;
}

const props = defineProps<{
    answers: Answer[];
    highlightedAnswerId?: string | number | null;
}>();

const isHighlighted = (answerId: number) => {
    return Number(props.highlightedAnswerId) === Number(answerId);
};

const deleteAnswer = (id: number) => {
    if (confirm('Are you sure you want to delete this answer?')) {
        router.delete(`/admin/answers/${id}`);
    }
};
</script>

<template>
    <div class="p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Manage Answers</h1>
                <p class="mt-2 text-gray-500">
                    View and manage answers posted on Code Help.
                </p>
            </div>

            <Link
                href="/admin"
                class="rounded-lg border px-4 py-2 hover:bg-gray-50"
            >
                ← Dashboard
            </Link>
        </div>

        <div class="overflow-hidden rounded-xl border bg-white">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Username</th>
                        <th class="px-6 py-4 text-left">Question</th>
                        <th class="px-6 py-4 text-left">Answer</th>
                        <th class="px-6 py-4 text-left">Created</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="answer in answers"
                        :key="answer.id"
                        class="border-t"
                        :class="{
        'bg-yellow-100 ring-2 ring-yellow-400':
            isHighlighted(answer.id),
    }"
                    >
                        <td class="px-6 py-4">
                            {{ answer.user.name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ answer.question.title }}
                        </td>

                        <td class="max-w-md px-6 py-4">
                            {{ answer.content }}
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ new Date(answer.created_at).toLocaleDateString() }}
                        </td>

                        <td class="px-6 py-4">
                            <button
                                @click="deleteAnswer(answer.id)"
                                class="text-red-600 hover:underline"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>

                    <tr v-if="answers.length === 0">
                        <td
                            colspan="5"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No answers found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>