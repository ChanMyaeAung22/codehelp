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
}

defineProps<{
    questions: Question[];
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
