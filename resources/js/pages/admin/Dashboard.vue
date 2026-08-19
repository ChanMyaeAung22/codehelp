<script setup lang="ts">
defineProps<{
    stats: {
        users: number;
        questions: number;
        answers: number;
        comments: number;
    };
}>();
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-10">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">
                Admin Dashboard
            </h1>

            <p class="mt-2 text-gray-500">
                Manage and monitor the Code Help platform.
            </p>
        </div>

        <!-- Statistics -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Users</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.users }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Questions</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.questions }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Answers</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.answers }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Comments</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.comments }}
                </p>
            </div>
        </div>
    </div>
</template>