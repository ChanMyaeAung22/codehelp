<script setup lang="ts">
defineProps({
    question: Object,
});
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