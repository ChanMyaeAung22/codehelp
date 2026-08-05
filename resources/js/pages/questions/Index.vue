<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    questions: Array,
});
</script>

<template>
    <div class="max-w-4xl mx-auto py-10">

        <!-- Header -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-4xl font-bold">
                    All Questions
                </h1>

                <p class="text-gray-500 mt-2">
                    Browse programming questions from the community.
                </p>
            </div>

            <Link
                href="/questions/create"
                class="bg-blue-600 text-white px-6 py-3 rounded-xl
                       hover:bg-blue-700
                       hover:shadow-lg
                       hover:scale-105
                       active:scale-95
                       transition-all duration-300"
            >
                Ask Question
            </Link>
        </div>

        <!-- Question Cards -->
        <div
            v-for="question in questions"
            :key="question.id"
            class="bg-white rounded-2xl shadow-md hover:shadow-xl
                   transition-all duration-300
                   p-8 mb-6 border border-gray-100"
        >
            <!-- Title -->
            <Link
                :href="`/questions/${question.id}`"
                class="text-2xl font-bold text-gray-800
                       hover:text-blue-600
                       transition-colors
                       cursor-pointer"
            >
                {{ question.title }}
        </Link>

            <!-- Description -->
            <p class="mt-4 text-gray-600 leading-7">
                {{ question.description }}
            </p>

            <!-- Stats -->
            <div class="flex gap-6 mt-6 text-sm text-gray-500">
                <span>
                    💬 {{ question.answers.length }} Answers
                </span>

                <span>
                    👍 {{ question.votes.length }} Votes
                </span>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center mt-6">
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