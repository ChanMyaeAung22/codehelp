<script setup lang="ts">
import { router } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
}

interface Question {
    id: number;
    title: string;
}

interface Answer {
    id: number;
    content: string;
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
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(`/admin/comments/${id}`);
    }
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
</script>

<template>
    <div class="p-8">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">
                    Manage Comments
                </h1>

                <p class="mt-2 text-gray-500">
                    View and manage comments submitted by users.
                </p>
            </div>

            <a
                href="/admin"
                class="rounded-lg border px-4 py-2 hover:bg-gray-50"
            >
                ← Dashboard
            </a>
        </div>

        <!-- Comments Table -->
        <div class="overflow-hidden rounded-xl border bg-white">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            Commenter
                        </th>

                        <th class="px-6 py-4 text-left">
                            Comment
                        </th>

                        <th class="px-6 py-4 text-left">
                            Type
                        </th>

                        <th class="px-6 py-4 text-left">
                            On
                        </th>

                        <th class="px-6 py-4 text-left">
                            Created
                        </th>

                        <th class="px-6 py-4 text-left">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="comment in comments.data"
                        :key="comment.id"
                        class="border-t"
                        :class="{
                        'bg-yellow-100 ring-2 ring-yellow-400': isHighlighted(comment.id),
                        }"
                    >
                        <!-- Commenter -->
                        <td class="px-6 py-4">
                            {{ comment.user.name }}
                        </td>

                        <!-- Comment -->
                        <td class="max-w-sm px-6 py-4">
                            <div class="truncate">
                                {{ comment.content }}
                            </div>
                        </td>

                        <!-- Type -->
                        <td class="px-6 py-4">
                            {{ getCommentType(comment) }}
                        </td>

                        <!-- Comment Target -->
                        <td class="max-w-xs px-6 py-4">
                            <div class="truncate">
                                {{ getCommentTarget(comment) }}
                            </div>
                        </td>

                        <!-- Created -->
                        <td class="px-6 py-4 text-gray-500">
                            {{
                                new Date(
                                    comment.created_at,
                                ).toLocaleDateString()
                            }}
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4">
                            <button
                                type="button"
                                @click="deleteComment(comment.id)"
                                class="text-red-600 hover:underline"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="comments.data.length === 0">
                        <td
                            colspan="6"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No comments found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Information -->
        <div
            v-if="comments.total > 0"
            class="mt-6 text-sm text-gray-500"
        >
            Page {{ comments.current_page }}
            of {{ comments.last_page }}
            · {{ comments.total }} comments
        </div>

    </div>
</template>