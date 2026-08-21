<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    stats: {
        users: number;
        questions: number;
        answers: number;
        comments: number;
        reports: number;
        pendingReports: number;
        resolvedReports: number;
        dismissedReports: number;
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
            <Link 
                href="/admin/users"
                class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Users</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.users }}
                </p>
            </Link>

            <Link
                href="/admin/questions"
                class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Questions</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.questions }}
                </p>
            </Link>

            <Link 
                href="/admin/answers"
                class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Answers</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.answers }}
                </p>
            </Link>

            <Link 
                href="/admin/comments"
                class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Comments</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.comments }}
                </p>
            </Link>

            <Link 
                href="/admin/reports"
                class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md">
                <p class="text-sm text-gray-500">Total Reports</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ stats.reports }}
                </p>
            </Link>

            <Link
    href="/admin/reports?status=pending"
    class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md"
>
    <p class="text-sm text-gray-500">Pending Reports</p>

    <p class="mt-2 text-3xl font-bold text-gray-800">
        {{ stats.pendingReports }}
    </p>
</Link>

<Link
    href="/admin/reports?status=resolved"
    class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md"
>
    <p class="text-sm text-gray-500">Resolved Reports</p>

    <p class="mt-2 text-3xl font-bold text-gray-800">
        {{ stats.resolvedReports }}
    </p>
</Link>

<Link
    href="/admin/reports?status=dismissed"
    class="rounded-2xl border border-gray-100 bg-white p-6 shadow-md"
>
    <p class="text-sm text-gray-500">Dismissed Reports</p>

    <p class="mt-2 text-3xl font-bold text-gray-800">
        {{ stats.dismissedReports }}
    </p>
</Link>
        </div>
    </div>
</template>