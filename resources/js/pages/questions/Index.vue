<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Question {
    id: number;
    title: string;
    description: string;
    created_at: string;

    user: {
        id: number;
        name: string;
    };

    answers: Array<{
        id: number;
    }>;

    votes: Array<{
        id: number;
    }>;

    tags: Tag[];
}

interface Tag {
    id: number;
    name: string;
    slug: string;
}

defineProps<{
    questions: Question[];
    selectedTag?: Tag;
}>();
</script>

<template>
    <div class="mx-auto max-w-4xl py-10">
        <!-- Header -->
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold">All Questions</h1>

                <p class="mt-2 text-gray-500">
                    Browse programming questions from the community.
                </p>
            </div>

            <Link
                href="/questions/create"
                class="rounded-xl bg-blue-600 px-6 py-3 text-white transition-all duration-300 hover:scale-105 hover:bg-blue-700 hover:shadow-lg active:scale-95"
            >
                Ask Question
            </Link>
        </div>

        <!-- Question Cards -->
        <div
            v-for="question in questions"
            :key="question.id"
            class="mb-6 rounded-2xl border border-gray-100 bg-white p-8 shadow-md transition-all duration-300 hover:shadow-xl"
        >
            <!-- Title -->
            <Link
                :href="`/questions/${question.id}`"
                class="cursor-pointer text-2xl font-bold text-gray-800 transition-colors hover:text-blue-600"
            >
                {{ question.title }}
            </Link>

            <!-- Description -->
            <p class="mt-4 leading-7 text-gray-600">
                {{ question.description }}
            </p>

            <div class="mt-3 flex flex-wrap gap-2">
                <a
                    v-for="tag in question.tags"
                    :key="tag.id"
                    :href="`/questions/tag/${tag.slug}`"
                    class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700 hover:bg-blue-200">
                        {{ tag.name }}
                </a>
            </div>

            <div v-if="selectedTag" class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Questions tagged "{{ selectedTag.name }}"
                </h1>

                <a
                    href="/questions"
                    class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                    ← View all questions
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-6 flex gap-6 text-sm text-gray-500">
                <span> 💬 {{ question.answers.length }} Answers </span>

                <span> 👍 {{ question.votes.length }} Votes </span>
            </div>

            <!-- Footer -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    👤 {{ question.user.name }}
                </div>

                <div class="text-sm text-gray-400">
                    {{ new Date(question.created_at).toLocaleDateString() }}
                </div>
            </div>
        </div>
    </div>
</template>
