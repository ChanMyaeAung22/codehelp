<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import {
    MessageSquare,
    ThumbsUp,
    Trash2,
    Users,
    ChevronLeft,
    ChevronRight,
} from '@lucide/vue';

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

interface Pagination {
    data: Question[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    questions: Pagination;
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

    router.delete(`/admin/questions/${id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">

            <!-- Header -->
            <div
                class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end"
            >
                <div>
                    <!-- Badge -->
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700"
                    >
                        <span
                            class="flex h-2 w-2 rounded-full bg-blue-600"
                        ></span>

                        Question Management
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tight text-slate-900"
                    >
                        Manage Questions<span class="text-blue-600">.</span>
                    </h1>

                    <p class="mt-2 text-base text-slate-500">
                        View and manage questions posted by the CodeHelp
                        community.
                    </p>
                </div>

                <!-- Back to Dashboard -->
                <Link
                    href="/admin"
                    class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                >
                    ← Dashboard
                </Link>
            </div>

            <!-- Questions Card -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <!-- Card Header -->
                <div
                    class="flex items-center gap-3 border-b border-slate-100 px-6 py-5"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                    >
                        <MessageSquare class="size-5" />
                    </div>

                    <div>
                        <h2 class="font-semibold text-slate-900">
                            Posted Questions
                        </h2>

                        <p class="text-sm text-slate-500">
                            {{ questions.total }} total questions
                        </p>
                    </div>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">

                        <!-- Table Header -->
                        <thead
                            class="border-b border-slate-100 bg-slate-50/70"
                        >
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Question
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Author
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Answers
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Votes
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Created
                                </th>

                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="divide-y divide-slate-100">

                            <tr
                                v-for="question in questions.data"
                                :key="question.id"
                                class="transition hover:bg-slate-50/70"
                                :class="{
                                    'bg-blue-50 ring-2 ring-inset ring-blue-400':
                                        isHighlighted(question.id),
                                }"
                            >
                                <!-- Question -->
                                <td class="px-6 py-5">
                                    <div class="max-w-md">
                                        <p
                                            class="font-semibold text-slate-800"
                                        >
                                            {{ question.title }}
                                        </p>

                                        <p
                                            class="mt-1 text-xs text-slate-400"
                                        >
                                            Question #{{ question.id }}
                                        </p>
                                    </div>
                                </td>

                                <!-- Author -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex size-9 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600"
                                        >
                                            {{
                                                question.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>

                                        <span
                                            class="text-sm font-medium text-slate-700"
                                        >
                                            {{ question.user.name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Answers -->
                                <td class="px-6 py-5">
                                    <div
                                        class="inline-flex items-center gap-2 text-sm text-slate-600"
                                    >
                                        <MessageSquare
                                            class="size-4 text-slate-400"
                                        />

                                        {{ question.answers.length }}
                                    </div>
                                </td>

                                <!-- Votes -->
                                <td class="px-6 py-5">
                                    <div
                                        class="inline-flex items-center gap-2 text-sm text-slate-600"
                                    >
                                        <ThumbsUp
                                            class="size-4 text-slate-400"
                                        />

                                        {{ question.votes.length }}
                                    </div>
                                </td>

                                <!-- Created -->
                                <td
                                    class="px-6 py-5 text-sm text-slate-500"
                                >
                                    {{ formatDate(question.created_at) }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-5">
                                    <div class="flex justify-end">
                                        <button
                                            type="button"
                                            @click="
                                                deleteQuestion(question.id)
                                            "
                                            class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                        >
                                            <Trash2 class="size-3.5" />

                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty -->
                            <tr v-if="questions.data.length === 0">
                                <td
                                    colspan="6"
                                    class="px-6 py-16 text-center"
                                >
                                    <div
                                        class="mx-auto flex size-14 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                    >
                                        <Users class="size-7" />
                                    </div>

                                    <h3
                                        class="mt-4 font-semibold text-slate-800"
                                    >
                                        No questions found
                                    </h3>

                                    <p
                                        class="mt-1 text-sm text-slate-500"
                                    >
                                        There are currently no questions to
                                        manage.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
<div
    v-if="questions.last_page > 1"
    class="flex flex-col gap-4 border-t border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
>
    <!-- Pagination Info -->
    <p class="text-sm text-slate-500">
        Showing page
        <span class="font-semibold text-slate-700">
            {{ questions.current_page }}
        </span>
        of
        <span class="font-semibold text-slate-700">
            {{ questions.last_page }}
        </span>

        ·

        <span class="font-semibold text-slate-700">
            {{ questions.total }}
        </span>
        questions
    </p>

    <!-- Pagination Buttons -->
    <div class="flex items-center gap-2">

        <!-- Previous -->
        <Link
            v-if="questions.current_page > 1"
            :href="`/admin/questions?page=${questions.current_page - 1}`"
            preserve-scroll
            preserve-state
            class="inline-flex cursor-pointer items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            <ChevronLeft class="size-4" />
            Previous
        </Link>

        <!-- Page Numbers -->
        <template
            v-for="page in questions.last_page"
            :key="page"
        >
            <Link
                :href="`/admin/questions?page=${page}`"
                preserve-scroll
                preserve-state
                :class="[
                    'flex size-9 cursor-pointer items-center justify-center rounded-lg border text-sm font-medium transition',
                    page === questions.current_page
                        ? 'border-blue-600 bg-blue-600 text-white shadow-sm'
                        : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50',
                ]"
            >
                {{ page }}
            </Link>
        </template>

        <!-- Next -->
        <Link
            v-if="questions.current_page < questions.last_page"
            :href="`/admin/questions?page=${questions.current_page + 1}`"
            preserve-scroll
            preserve-state
            class="inline-flex cursor-pointer items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            Next
            <ChevronRight class="size-4" />
        </Link>

    </div>
</div>
            </div>
        </div>
    </div>
</template>