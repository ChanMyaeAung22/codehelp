<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import {
    MessageSquare,
    Trash2,
    User,
    ChevronLeft,
    ChevronRight
} from '@lucide/vue';

interface UserData {
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
    user: UserData;
    question: Question;
}

interface Pagination {
    data: Answer[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    answers: Pagination;
    highlightedAnswerId?: string | number | null;
}>();

const isHighlighted = (answerId: number) => {
    return Number(props.highlightedAnswerId) === Number(answerId);
};

const formatDate = (date: string) => {
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(new Date(date));
};

const deleteAnswer = (id: number) => {
    if (!confirm('Are you sure you want to delete this answer?')) {
        return;
    }

    router.delete(`/admin/answers/${id}`, {
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

                        Answer Management
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tight text-slate-900"
                    >
                        Manage Answers<span class="text-blue-600">.</span>
                    </h1>

                    <p class="mt-2 text-base text-slate-500">
                        View and manage answers posted by the CodeHelp
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

            <!-- Answers Card -->
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
                            Posted Answers
                        </h2>

                        <p class="text-sm text-slate-500">
                            {{ answers.total }} total answers
                        </p>
                    </div>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px]">

                        <!-- Table Header -->
                        <thead
                            class="border-b border-slate-100 bg-slate-50/70"
                        >
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    User
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Question
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Answer
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
                                v-for="answer in answers.data"
                                :key="answer.id"
                                class="transition hover:bg-slate-50/70"
                                :class="{
                                    'bg-blue-50 ring-2 ring-inset ring-blue-400':
                                        isHighlighted(answer.id),
                                }"
                            >
                                <!-- User -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex size-9 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600"
                                        >
                                            {{
                                                answer.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>

                                        <span
                                            class="text-sm font-medium text-slate-700"
                                        >
                                            {{ answer.user.name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Question -->
                                <td class="px-6 py-5">
                                    <div class="max-w-xs">
                                        <p
                                            class="font-medium text-slate-800"
                                        >
                                            {{ answer.question.title }}
                                        </p>

                                        <p
                                            class="mt-1 text-xs text-slate-400"
                                        >
                                            Question #{{
                                                answer.question.id
                                            }}
                                        </p>
                                    </div>
                                </td>

                                <!-- Answer -->
                                <td class="max-w-md px-6 py-5">
                                    <p
                                        class="line-clamp-3 text-sm leading-6 text-slate-600"
                                    >
                                        {{ answer.content }}
                                    </p>
                                </td>

                                <!-- Created -->
                                <td
                                    class="px-6 py-5 text-sm text-slate-500"
                                >
                                    {{ formatDate(answer.created_at) }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-5">
                                    <div class="flex justify-end">
                                        <button
                                            type="button"
                                            @click="
                                                deleteAnswer(answer.id)
                                            "
                                            class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                        >
                                            <Trash2 class="size-3.5" />

                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="answers.data.length === 0">
                                <td
                                    colspan="5"
                                    class="px-6 py-16 text-center"
                                >
                                    <div
                                        class="mx-auto flex size-14 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                    >
                                        <MessageSquare class="size-7" />
                                    </div>

                                    <h3
                                        class="mt-4 font-semibold text-slate-800"
                                    >
                                        No answers found
                                    </h3>

                                    <p
                                        class="mt-1 text-sm text-slate-500"
                                    >
                                        There are currently no answers to
                                        manage.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
<div
    v-if="answers.last_page > 1"
    class="flex flex-col gap-4 border-t border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
>
    <!-- Pagination Info -->
    <p class="text-sm text-slate-500">
        Showing page
        <span class="font-semibold text-slate-700">
            {{ answers.current_page }}
        </span>
        of
        <span class="font-semibold text-slate-700">
            {{ answers.last_page }}
        </span>

        ·

        <span class="font-semibold text-slate-700">
            {{ answers.total }}
        </span>
        answers
    </p>

    <!-- Pagination Buttons -->
    <div class="flex items-center gap-2">

        <!-- Previous -->
        <Link
            v-if="answers.current_page > 1"
            :href="`/admin/answers?page=${answers.current_page - 1}`"
            preserve-scroll
            preserve-state
            class="inline-flex cursor-pointer items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            <ChevronLeft class="size-4" />
            Previous
        </Link>

        <!-- Page Numbers -->
        <template
            v-for="page in answers.last_page"
            :key="page"
        >
            <Link
                :href="`/admin/answers?page=${page}`"
                preserve-scroll
                preserve-state
                :class="[
                    'flex size-9 cursor-pointer items-center justify-center rounded-lg border text-sm font-medium transition',
                    page === answers.current_page
                        ? 'border-blue-600 bg-blue-600 text-white shadow-sm'
                        : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50',
                ]"
            >
                {{ page }}
            </Link>
        </template>

        <!-- Next -->
        <Link
            v-if="answers.current_page < answers.last_page"
            :href="`/admin/answers?page=${answers.current_page + 1}`"
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
</template>