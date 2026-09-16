<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Tag {
    id: number;
    name: string;
    slug: string;
}

interface Question {
    id: number;
    title: string;
    created_at: string;
    votes: { id: number }[];
    answers: { id: number }[];
    tags: Tag[];
}

interface Answer {
    id: number;
    content: string;
    created_at: string;
    is_accepted: boolean;
    votes: { id: number }[];
    question: {
        id: number;
        title: string;
    };
}

interface ProfileUser {
    id: number;
    name: string;
    created_at: string;
    question_count: number;
    answer_count: number;
    questions: Question[];
    answers: Answer[];
}

const props = defineProps<{
    profileUser: ProfileUser;
}>();

function formatDate(date: string) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        timeZone: 'UTC',
    }).format(new Date(date));
}

function memberSince(date: string) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'long',
        timeZone: 'UTC',
    }).format(new Date(date));
}
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

            <!-- Back link -->
            <div class="mb-6">
                <Link
                    href="/questions"
                    class="inline-flex items-center gap-1.5 text-sm text-slate-500 transition hover:text-blue-600"
                >
                    ← Back to Questions
                </Link>
            </div>

            <!-- Profile Hero Card -->
            <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-24 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-500"></div>

                <div class="px-6 pb-8 sm:px-8">
                    <!-- Avatar -->
                    <div class="-mt-12 mb-5">
                        <div class="flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-blue-100 text-3xl font-bold text-blue-700 shadow-lg">
                            {{ profileUser.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>

                    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                                {{ profileUser.name }}
                            </h1>
                            <p class="mt-1 text-sm text-slate-500">
                                Member since {{ memberSince(profileUser.created_at) }}
                            </p>
                        </div>

                        <!-- Stats -->
                        <div class="flex gap-6">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ profileUser.question_count }}
                                </p>
                                <p class="text-xs font-medium text-slate-500">
                                    {{ profileUser.question_count === 1 ? 'Question' : 'Questions' }}
                                </p>
                            </div>

                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">
                                    {{ profileUser.answer_count }}
                                </p>
                                <p class="text-xs font-medium text-slate-500">
                                    {{ profileUser.answer_count === 1 ? 'Answer' : 'Answers' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">

                <!-- Questions -->
                <section>
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-slate-900">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-sm text-blue-700">?</span>
                        Recent Questions
                    </h2>

                    <div v-if="profileUser.questions.length" class="space-y-3">
                        <Link
                            v-for="q in profileUser.questions"
                            :key="q.id"
                            :href="`/questions/${q.id}`"
                            class="block rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md hover:shadow-blue-100/50"
                        >
                            <p class="font-semibold leading-snug text-slate-800 transition group-hover:text-blue-600">
                                {{ q.title }}
                            </p>

                            <!-- Tags -->
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <span
                                    v-for="tag in q.tags"
                                    :key="tag.id"
                                    class="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700"
                                >
                                    {{ tag.name }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center gap-4 text-xs text-slate-400">
                                <span>
                                    <strong class="text-slate-600">{{ q.votes.length }}</strong>
                                    {{ q.votes.length === 1 ? 'vote' : 'votes' }}
                                </span>
                                <span>
                                    <strong class="text-slate-600">{{ q.answers.length }}</strong>
                                    {{ q.answers.length === 1 ? 'answer' : 'answers' }}
                                </span>
                                <span class="ml-auto">{{ formatDate(q.created_at) }}</span>
                            </div>
                        </Link>
                    </div>

                    <div
                        v-else
                        class="rounded-xl border border-dashed border-slate-200 bg-white px-6 py-10 text-center"
                    >
                        <p class="text-sm text-slate-400">No questions asked yet.</p>
                    </div>
                </section>


                <!-- Answers -->
                <section>
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-slate-900">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-green-100 text-sm text-green-700">✓</span>
                        Recent Answers
                    </h2>

                    <div v-if="profileUser.answers.length" class="space-y-3">
                        <Link
                            v-for="a in profileUser.answers"
                            :key="a.id"
                            :href="`/questions/${a.question.id}`"
                            class="block rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-green-200 hover:shadow-md hover:shadow-green-100/50"
                        >
                            <!-- Accepted badge -->
                            <div v-if="a.is_accepted" class="mb-2 inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">
                                <span>✓</span> Accepted
                            </div>

                            <p class="text-sm font-medium text-slate-500">
                                On:
                                <span class="font-semibold text-slate-700">{{ a.question.title }}</span>
                            </p>

                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">
                                {{ a.content }}
                            </p>

                            <div class="mt-3 flex items-center gap-4 text-xs text-slate-400">
                                <span>
                                    <strong class="text-slate-600">{{ a.votes.length }}</strong>
                                    {{ a.votes.length === 1 ? 'vote' : 'votes' }}
                                </span>
                                <span class="ml-auto">{{ formatDate(a.created_at) }}</span>
                            </div>
                        </Link>
                    </div>

                    <div
                        v-else
                        class="rounded-xl border border-dashed border-slate-200 bg-white px-6 py-10 text-center"
                    >
                        <p class="text-sm text-slate-400">No answers given yet.</p>
                    </div>
                </section>

            </div>
        </div>
    </div>
</template>
