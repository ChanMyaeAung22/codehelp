<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
}

interface Question {
    id: number;
    title: string;
    created_at: string;
    user: User;
    answers: unknown[];
    votes: unknown[];
}

const props = defineProps<{
    questions: Question[];
    highlightedQuestionId?: string | number | null;
}>();

const isHighlighted = (questionId: number) => {
    return Number(props.highlightedQuestionId) === Number(questionId);
};

const formatDate = (date: string) => {
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(new Date(date));
};

const deleteQuestion = (id: number) => {
    if (!confirm('Are you sure you want to delete this question?')) {
        return;
    }

    router.delete(`/admin/questions/${id}`);
};
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">
                        Manage Questions
                    </h1>

                    <p class="mt-2 text-gray-600">
                        View and manage questions posted on Code Help.
                    </p>
                </div>

                <a
                    href="/admin"
                    class="rounded-lg border px-4 py-2 text-gray-700 hover:bg-gray-50"
                >
                    ← Dashboard
                </a>
            </div>

            <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-6 py-4">Question</th>
                            <th class="px-6 py-4">Author</th>
                            <th class="px-6 py-4">Answers</th>
                            <th class="px-6 py-4">Votes</th>
                            <th class="px-6 py-4">Created</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="question in questions"
                            :key="question.id"
                            class="border-t"
                            :class="{
        'bg-yellow-100 ring-2 ring-yellow-400':
            isHighlighted(question.id),
    }"
                        >
                            <td class="px-6 py-4 font-medium">
                                {{ question.title }}
                            </td>

                            <td class="px-6 py-4">
                                {{ question.user.name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ question.answers.length }}
                            </td>

                            <td class="px-6 py-4">
                                {{ question.votes.length }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ formatDate(question.created_at) }}
                            </td>

                            <td class="px-6 py-4">
                                <button
                                    type="button"
                                    class="rounded-lg bg-red-500 px-3 py-2 text-sm text-white hover:bg-red-600"
                                    @click="deleteQuestion(question.id)">
                                        Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>