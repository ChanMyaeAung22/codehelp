<script setup lang="ts">
interface Question {
    id: number;
    title: string;
    description: string;
    created_at: string;

    user: {
        id: number;
        name: string;
    };
}

defineProps<{
    question: Question;
}>();
</script>

<template>
    <div class="max-w-4xl mx-auto py-10">

        <h1 class="text-4xl font-bold">
            {{ question.title }}
        </h1>

        <p class="mt-6 text-gray-700 leading-8">
            {{ question.description }}
        </p>

        <div class="mt-8 text-gray-500">
            Asked by {{ question.user.name }}
        </div>

    </div>
</template>