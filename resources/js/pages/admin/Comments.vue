<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import {
    MessageSquare,
    Trash2,
    ChevronLeft,
    ChevronRight,
} from '@lucide/vue';

interface User {
    id: number;
    name: string;
}

interface Commentable {
    id: number;
    title?: string;
    content?: string;
}

interface Comment {
    id: number;
    content: string;
    created_at: string;
    user: User;
    commentable: Commentable | null;
    commentable_type: string;
}

const props = defineProps<{
    comments: {
        data: Comment[];
        current_page: number;
        last_page: number;
        total: number;
    };
    highlightedCommentId?: string | number | null;
}>();

const deleteComment = (id: number) => {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }

    router.delete(`/admin/comments/${id}`, {
        preserveScroll: true,
    });
};

const getCommentType = (comment: Comment) => {
    if (!comment.commentable) {
        return 'Deleted Content';
    }

    if (comment.commentable_type.includes('Question')) {
        return 'Question';
    }

    if (comment.commentable_type.includes('Answer')) {
        return 'Answer';
    }

    return 'Unknown';
};

const getCommentTarget = (comment: Comment) => {
    if (!comment.commentable) {
        return 'Content no longer exists';
    }

    if (getCommentType(comment) === 'Question') {
        return comment.commentable.title ?? 'Question';
    }

    if (getCommentType(comment) === 'Answer') {
        return comment.commentable.content ?? 'Answer';
    }

    return 'Unknown';
};

const isHighlighted = (commentId: number) => {
    return Number(props.highlightedCommentId) === Number(commentId);
};

const formatDate = (date: string) => {
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(new Date(date));
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

                        Comment Management
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tight text-slate-900"
                    >
                        Manage Comments<span class="text-blue-600">.</span>
                    </h1>

                    <p class="mt-2 text-base text-slate-500">
                        View and manage comments submitted by the CodeHelp
                        community.
                    </p>
                </div>

                <!-- Dashboard -->
                <Link
                    href="/admin"
                    class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                >
                    ← Dashboard
                </Link>
            </div>

            <!-- Comments Card -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <!-- Card Header -->
                <div
                    class="flex items-center justify-between border-b border-slate-100 px-6 py-5"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                        >
                            <MessageSquare class="size-5" />
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Posted Comments
                            </h2>

                            <p class="text-sm text-slate-500">
                                {{ comments.total }} total comments
                            </p>
                        </div>
                    </div>

                    <!-- Pagination Summary -->
                    <div
                        v-if="comments.total > 0"
                        class="hidden text-sm text-slate-500 sm:block"
                    >
                        Page {{ comments.current_page }}
                        of {{ comments.last_page }}
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1100px]">

                        <!-- Table Header -->
                        <thead
                            class="border-b border-slate-100 bg-slate-50/70"
                        >
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Commenter
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Comment
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Type
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    On
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
                                v-for="comment in comments.data"
                                :key="comment.id"
                                class="transition hover:bg-slate-50/70"
                                :class="{
                                    'bg-blue-50 ring-2 ring-inset ring-blue-400':
                                        isHighlighted(comment.id),
                                }"
                            >
                                <!-- Commenter -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex size-9 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600"
                                        >
                                            {{
                                                comment.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>

                                        <span
                                            class="text-sm font-medium text-slate-700"
                                        >
                                            {{ comment.user.name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Comment -->
                                <td class="max-w-sm px-6 py-5">
                                    <p
                                        class="line-clamp-2 text-sm leading-6 text-slate-600"
                                    >
                                        {{ comment.content }}
                                    </p>
                                </td>

                                <!-- Type -->
                                <td class="px-6 py-5">
                                    <span
                                        v-if="
                                            getCommentType(comment) ===
                                            'Question'
                                        "
                                        class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700"
                                    >
                                        Question
                                    </span>

                                    <span
                                        v-else-if="
                                            getCommentType(comment) ===
                                            'Answer'
                                        "
                                        class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700"
                                    >
                                        Answer
                                    </span>

                                    <span
                                        v-else
                                        class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600"
                                    >
                                        {{ getCommentType(comment) }}
                                    </span>
                                </td>

                                <!-- Target -->
                                <td class="max-w-xs px-6 py-5">
                                    <p
                                        class="truncate text-sm text-slate-600"
                                        :title="getCommentTarget(comment)"
                                    >
                                        {{ getCommentTarget(comment) }}
                                    </p>
                                </td>

                                <!-- Created -->
                                <td
                                    class="px-6 py-5 text-sm text-slate-500"
                                >
                                    {{ formatDate(comment.created_at) }}
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-5">
                                    <div class="flex justify-end">
                                        <button
                                            type="button"
                                            @click="
                                                deleteComment(comment.id)
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
                            <tr v-if="comments.data.length === 0">
                                <td
                                    colspan="6"
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
                                        No comments found
                                    </h3>

                                    <p
                                        class="mt-1 text-sm text-slate-500"
                                    >
                                        There are currently no comments to
                                        manage.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
<div
    v-if="comments.last_page > 1"
    class="flex flex-col gap-4 border-t border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
>
    <!-- Pagination Info -->
    <p class="text-sm text-slate-500">
        Showing page
        <span class="font-semibold text-slate-700">
            {{ comments.current_page }}
        </span>
        of
        <span class="font-semibold text-slate-700">
            {{ comments.last_page }}
        </span>

        ·

        <span class="font-semibold text-slate-700">
            {{ comments.total }}
        </span>
        comments
    </p>

    <!-- Pagination Buttons -->
    <div class="flex items-center gap-2">

        <!-- Previous -->
        <Link
            v-if="comments.current_page > 1"
            :href="`/admin/comments?page=${comments.current_page - 1}`"
            preserve-scroll
            preserve-state
            class="inline-flex cursor-pointer items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            <ChevronLeft class="size-4" />
            Previous
        </Link>

        <!-- Page Numbers -->
        <template
            v-for="page in comments.last_page"
            :key="page"
        >
            <Link
                :href="`/admin/comments?page=${page}`"
                preserve-scroll
                preserve-state
                :class="[
                    'flex size-9 cursor-pointer items-center justify-center rounded-lg border text-sm font-medium transition',
                    page === comments.current_page
                        ? 'border-blue-600 bg-blue-600 text-white shadow-sm'
                        : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50',
                ]"
            >
                {{ page }}
            </Link>
        </template>

        <!-- Next -->
        <Link
            v-if="comments.current_page < comments.last_page"
            :href="`/admin/comments?page=${comments.current_page + 1}`"
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
    </div>
</template>