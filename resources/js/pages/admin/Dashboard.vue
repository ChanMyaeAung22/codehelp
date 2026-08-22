<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Users,
    MessageSquare,
    MessagesSquare,
    FileText,
    Flag,
    Clock3,
    CheckCircle2,
    XCircle,
    ArrowRight,
} from '@lucide/vue';

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
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">

            <!-- Header -->
            <div
                class="mb-10 flex flex-col justify-between gap-6 md:flex-row md:items-end"
            >
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600"
                    >
                        <span
                            class="h-2 w-2 rounded-full bg-blue-600"
                        ></span>

                        Administration
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tight text-slate-900"
                    >
                        Admin Dashboard<span class="text-blue-600">.</span>
                    </h1>

                    <p class="mt-3 text-lg text-slate-500">
                        Manage and monitor the CodeHelp platform.
                    </p>
                </div>

                <Link
                    href="/questions"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                >
                    View Community

                    <ArrowRight class="size-4" />
                </Link>
            </div>

            <!-- Platform Statistics -->
            <div class="mb-10">
                <div class="mb-5">
                    <h2 class="text-xl font-semibold text-slate-900">
                        Platform Overview
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Current activity across the CodeHelp community.
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <!-- Users -->
                    <Link
                        href="/admin/users"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                            >
                                <Users class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-slate-300 transition group-hover:text-blue-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Total Users
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold tracking-tight text-slate-900"
                        >
                            {{ stats.users }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Registered members
                        </p>
                    </Link>

                    <!-- Questions -->
                    <Link
                        href="/admin/questions"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                            >
                                <MessageSquare class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-slate-300 transition group-hover:text-blue-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Total Questions
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold tracking-tight text-slate-900"
                        >
                            {{ stats.questions }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Community questions
                        </p>
                    </Link>

                    <!-- Answers -->
                    <Link
                        href="/admin/answers"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                            >
                                <MessagesSquare class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-slate-300 transition group-hover:text-blue-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Total Answers
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold tracking-tight text-slate-900"
                        >
                            {{ stats.answers }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Community responses
                        </p>
                    </Link>

                    <!-- Comments -->
                    <Link
                        href="/admin/comments"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                            >
                                <FileText class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-slate-300 transition group-hover:text-blue-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Total Comments
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold tracking-tight text-slate-900"
                        >
                            {{ stats.comments }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Community discussions
                        </p>
                    </Link>
                </div>
            </div>

            <!-- Reports -->
            <div>
                <div
                    class="mb-5 flex flex-col justify-between gap-2 sm:flex-row sm:items-end"
                >
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">
                            Reports
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Monitor and manage reported community content.
                        </p>
                    </div>

                    <Link
                        href="/admin/reports"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                    >
                        View all reports →
                    </Link>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <!-- Total Reports -->
                    <Link
                        href="/admin/reports"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                            >
                                <Flag class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-slate-300 transition group-hover:text-blue-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Total Reports
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold text-slate-900"
                        >
                            {{ stats.reports }}
                        </p>
                    </Link>

                    <!-- Pending Reports -->
                    <Link
                        href="/admin/reports?status=pending"
                        class="group rounded-2xl border border-orange-200 bg-orange-50/50 p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-orange-300 hover:bg-orange-50 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-orange-100 text-orange-600"
                            >
                                <Clock3 class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-orange-300 transition group-hover:text-orange-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-orange-700">
                            Pending Reports
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold text-orange-900"
                        >
                            {{ stats.pendingReports }}
                        </p>

                        <p class="mt-2 text-xs text-orange-600">
                            Require attention
                        </p>
                    </Link>

                    <!-- Resolved Reports -->
                    <Link
                        href="/admin/reports?status=resolved"
                        class="group rounded-2xl border border-emerald-200 bg-emerald-50/50 p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-emerald-300 hover:bg-emerald-50 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"
                            >
                                <CheckCircle2 class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-emerald-300 transition group-hover:text-emerald-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-emerald-700">
                            Resolved Reports
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold text-emerald-900"
                        >
                            {{ stats.resolvedReports }}
                        </p>

                        <p class="mt-2 text-xs text-emerald-600">
                            Successfully handled
                        </p>
                    </Link>

                    <!-- Dismissed Reports -->
                    <Link
                        href="/admin/reports?status=dismissed"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-slate-300 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between">
                            <div
                                class="flex size-11 items-center justify-center rounded-xl bg-slate-100 text-slate-500"
                            >
                                <XCircle class="size-5" />
                            </div>

                            <ArrowRight
                                class="size-4 text-slate-300 transition group-hover:text-slate-600"
                            />
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Dismissed Reports
                        </p>

                        <p
                            class="mt-1 text-3xl font-bold text-slate-900"
                        >
                            {{ stats.dismissedReports }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            No action required
                        </p>
                    </Link>
                </div>
            </div>

        </div>
    </div>
</template>