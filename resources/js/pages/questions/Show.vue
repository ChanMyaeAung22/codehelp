<script setup lang="ts">
import { Link ,router, useForm, usePage } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
}

interface Answer {
    id: number;
    content: string;
    created_at: string;
    is_accepted: boolean;
    user: User;
    votes: {
        id: number;
        user_id: number;
        vote_type: string;
    }[];
    comments: {
        id: number;
        content: string;
        created_at: string;
        user: User;
    }[];
}

interface Question {
    id: number;
    title: string;
    description: string;
    created_at: string;
    user: User;
    votes: {
        id: number;
        user_id: number;
        vote_type: string;
    }[];
    answers: Answer[];

    comments: Comment[];

    tags: Tag[];
}

interface Comment {
    id: number;
    content: string;
    created_at: string;
    user: User;
}

interface Tag {
    id: number;
    name: string;
    slug: string;
}

const props = defineProps<{
    question: Question;
}>();

const form = useForm({
    content: '',
});

const voteQuestion = (questionId: number, voteType: string) => {
    router.post(`/questions/${questionId}/vote`, {
        vote_type: voteType,
    });
};

const submitAnswer = () => {
    form.post(`/questions/${props.question.id}/answers`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const scrollToAnswer = () => {
    document.getElementById('your-answer')?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
};

const voteAnswer = (answerId: number, voteType: string) => {
    router.post(`/answers/${answerId}/vote`, {
        vote_type: voteType,
    });
};

const acceptAnswer = (answerId: number) => {
    router.post(
        `/questions/${props.question.id}/answers/${answerId}/accept`,
        {},
        {
            preserveScroll: true,
        },
    );
};

const page = usePage();

const commentForm = useForm({
    content: '',
    commentable_type: '',
    commentable_id: 0,
});

const submitComment = (
    commentableType: 'question' | 'answer',
    commentableId: number,
) => {
    commentForm.commentable_type = commentableType;
    commentForm.commentable_id = commentableId;

    commentForm.post('/comments', {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
        },
    });
};

const submitAnswerComment = (answerId: number) => {
    commentForm.commentable_type = 'answer';
    commentForm.commentable_id = answerId;

    commentForm.post('/comments', {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
        },
    });
};

const deleteQuestion = () => {
    if (!confirm('Are you sure you want to delete this question?')) {
        return;
    }

    router.delete(`/questions/${props.question.id}`);
};

function deleteAnswer(answerId: number) {
    if (!confirm('Are you sure you want to delete this answer?')) {
        return;
    }

    router.delete(
        `/questions/${props.question.id}/answers/${answerId}`
    );
}

</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Back / Breadcrumb -->
            <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
                <Link
                    href="/questions"
                    class="transition hover:text-blue-600"
                >
                    Questions
                </Link>

                <span>/</span>

                <span class="truncate text-gray-400">
                    Question
                </span>
            </div>


            <!-- Question Card -->
            <article
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
            >

                <!-- Question Header -->
                <div class="border-b border-gray-100 p-6 sm:p-8">

                    <div class="flex flex-col gap-6">

                        <!-- Title -->
                        <div>
                            <h1
                                class="text-3xl font-bold leading-tight tracking-tight text-slate-900 sm:text-4xl"
                            >
                                {{ question.title }}
                            </h1>

                            <div
                                class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500"
                            >
                                <span>
                                    Asked
                                    {{ new Date(question.created_at).toLocaleDateString() }}
                                </span>

                                <span class="hidden sm:inline">•</span>

                                <span>
                                    by
                                    <span class="font-medium text-gray-700">
                                        {{ question.user.name }}
                                    </span>
                                </span>
                            </div>
                        </div>


                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2">
                            <Link
                                v-for="tag in question.tags"
                                :key="tag.id"
                                :href="`/questions/tag/${tag.slug}`"
                                class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100"
                            >
                                {{ tag.name }}
                            </Link>
                        </div>

                    </div>
                </div>


                <!-- Question Body -->
                <div class="p-6 sm:p-8">

                    <div class="flex gap-5">

                        <!-- Vote Column -->
                        <div class="hidden w-16 shrink-0 flex-col items-center gap-3 sm:flex">

                            <template v-if="page.props.auth.user">
                                <button
                                    type="button"
                                    @click="voteQuestion(question.id, 'up')"
                                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 text-lg transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                    title="Upvote"
                                >
                                    ↑
                                </button>
                            </template>

                            <template v-else>
                                <Link
                                    href="/login"
                                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 text-lg text-gray-500 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                    title="Log in to vote"
                                >
                                    ↑
                                </Link>
                            </template>

                            <span class="text-lg font-semibold text-gray-700">
                                {{ question.votes.length }}
                            </span>

                            <span class="text-xs text-gray-400">
                                votes
                            </span>

                        </div>


                        <!-- Main Content -->
                        <div class="min-w-0 flex-1">

                            <!-- Mobile Vote -->
                            <div class="mb-5 flex items-center gap-3 sm:hidden">
                                <template v-if="page.props.auth.user">
                                    <button
                                        type="button"
                                        @click="voteQuestion(question.id, 'up')"
                                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                    >
                                        ↑ Upvote
                                    </button>
                                </template>

                                <template v-else>
                                    <Link
                                        href="/login"
                                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition hover:border-blue-400 hover:bg-blue-50"
                                    >
                                        Log in to vote
                                    </Link>
                                </template>

                                <span class="font-semibold text-gray-700">
                                    {{ question.votes.length }}
                                </span>
                            </div>


                            <!-- Description -->
                            <div
                                class="prose prose-slate max-w-none text-[16px] leading-8 text-gray-700"
                            >
                                <p class="whitespace-pre-line">
                                    {{ question.description }}
                                </p>
                            </div>


                            <!-- Question Actions -->
                            <div
                                class="mt-8 flex flex-wrap items-center justify-between gap-4 border-t border-gray-100 pt-6"
                            >

                                <div class="flex flex-wrap gap-2">

                                    <!-- Answer Question -->
                                    <button
                                        type="button"
                                        @click="scrollToAnswer"
                                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md"
                                    >
                                        Answer this question
                                    </button>

                                    <!-- Owner Actions -->
                                    <template
                                        v-if="page.props.auth.user?.id === question.user.id"
                                    >
                                        <Link
                                            :href="`/questions/${question.id}/edit`"
                                            class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-100"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            @click="deleteQuestion"
                                            class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100"
                                        >
                                            Delete
                                        </button>
                                    </template>


                                    <!-- Report -->
                                    <Link
                                        v-if="
                                            page.props.auth.user &&
                                            page.props.auth.user.id !== question.user.id
                                        "
                                        :href="`/questions/${question.id}/report`"
                                        class="rounded-lg px-3 py-2 text-sm text-gray-500 transition hover:bg-red-50 hover:text-red-600"
                                    >
                                        Report
                                    </Link>

                                </div>


                                <!-- Author -->
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 font-semibold text-blue-700"
                                    >
                                        {{ question.user.name.charAt(0).toUpperCase() }}
                                    </div>

                                    <div>
                                        <p class="text-xs text-gray-400">
                                            Asked by
                                        </p>

                                        <p class="text-sm font-medium text-gray-700">
                                            {{ question.user.name }}
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                <!-- Question Comments -->
                <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-6 sm:px-8">

                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ question.comments.length }}
                            {{ question.comments.length === 1 ? 'Comment' : 'Comments' }}
                        </h2>
                    </div>


                    <!-- Comments -->
                    <div
                        v-if="question.comments.length"
                        class="divide-y divide-gray-200"
                    >
                        <div
                            v-for="comment in question.comments"
                            :key="comment.id"
                            class="py-4"
                        >

                            <p class="text-sm leading-6 text-gray-700">
                                {{ comment.content }}
                            </p>

                            <div
                                class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-400"
                            >
                                <span class="font-medium text-gray-500">
                                    {{ comment.user.name }}
                                </span>

                                <span>•</span>

                                <span>
                                    {{ new Date(comment.created_at).toLocaleDateString() }}
                                </span>

                                <Link
                                    v-if="
                                        Number(page.props.auth.user?.id) !==
                                        Number(comment.user?.id)
                                    "
                                    :href="`/comments/${comment.id}/report?return_url=/questions/${question.id}`"
                                    class="text-red-500 hover:underline"
                                >
                                    Report
                                </Link>
                            </div>

                        </div>
                    </div>


                    <!-- No Comments -->
                    <p
                        v-else
                        class="py-3 text-sm text-gray-400"
                    >
                        No comments yet.
                    </p>


                    <!-- Add Comment -->
                    <div class="mt-5">

                        <template v-if="page.props.auth.user">

                            <form
                                @submit.prevent="submitComment('question', question.id)"
                            >
                                <textarea
                                    v-model="commentForm.content"
                                    rows="3"
                                    placeholder="Add a comment..."
                                    class="w-full resize-none rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                ></textarea>

                                <p
                                    v-if="commentForm.errors.content"
                                    class="mt-2 text-sm text-red-500"
                                >
                                    {{ commentForm.errors.content }}
                                </p>

                                <button
                                    type="submit"
                                    :disabled="commentForm.processing"
                                    class="mt-3 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    {{
                                        commentForm.processing
                                            ? 'Posting...'
                                            : 'Add Comment'
                                    }}
                                </button>
                            </form>

                        </template>


                        <template v-else>

                            <div
                                class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-center"
                            >
                                <p class="text-sm text-gray-600">
                                    Want to join the discussion?
                                </p>

                                <Link
                                    href="/login"
                                    class="mt-2 inline-block text-sm font-semibold text-blue-600 hover:underline"
                                >
                                    Log in to comment
                                </Link>
                            </div>

                        </template>

                    </div>

                </div>

            </article>


            <!-- Answers Section -->
            <section class="mt-10">

                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">
                        {{ question.answers.length }}
                        {{ question.answers.length === 1 ? 'Answer' : 'Answers' }}
                    </h2>
                </div>


                <!-- Answers -->
                <div
                    v-for="answer in question.answers"
                    :key="answer.id"
                    class="mb-5 overflow-hidden rounded-2xl border bg-white shadow-sm"
                    :class="
                        answer.is_accepted
                            ? 'border-green-300'
                            : 'border-gray-200'
                    "
                >

                    <!-- Accepted Header -->
                    <div
                        v-if="answer.is_accepted"
                        class="border-b border-green-200 bg-green-50 px-6 py-3"
                    >
                        <div class="flex items-center gap-2 text-sm font-semibold text-green-700">
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-green-500 text-white"
                            >
                                ✓
                            </span>

                            Accepted Answer
                        </div>
                    </div>


                    <div class="p-6 sm:p-8">

                        <div class="flex gap-5">

                            <!-- Answer Vote -->
                            <div class="hidden w-16 shrink-0 flex-col items-center gap-3 sm:flex">

                                <button
                                    type="button"
                                    @click="voteAnswer(answer.id, 'up')"
                                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 text-lg transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                >
                                    ↑
                                </button>

                                <span class="font-semibold text-gray-700">
                                    {{ answer.votes.length }}
                                </span>

                                <span class="text-xs text-gray-400">
                                    votes
                                </span>

                            </div>


                            <!-- Answer Content -->
                            <div class="min-w-0 flex-1">

                                <!-- Mobile vote -->
                                <div class="mb-4 flex items-center gap-3 sm:hidden">
                                    <button
                                        type="button"
                                        @click="voteAnswer(answer.id, 'up')"
                                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                    >
                                        ↑ Upvote
                                    </button>

                                    <span class="font-semibold text-gray-700">
                                        {{ answer.votes.length }}
                                    </span>
                                </div>


                                <p
                                    class="whitespace-pre-line leading-8 text-gray-700"
                                >
                                    {{ answer.content }}
                                </p>


                                <!-- Answer Footer -->
                                <div
                                    class="mt-8 flex flex-col gap-4 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between"
                                >

                                    <div class="flex flex-wrap gap-2">

                                        <!-- Edit -->
                                        <Link
                                            v-if="
                                                Number(page.props.auth.user?.id) ===
                                                Number(answer.user?.id)
                                            "
                                            :href="`/questions/${question.id}/answers/${answer.id}/edit`"
                                            class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-sm text-blue-600 transition hover:bg-blue-100"
                                        >
                                            Edit
                                        </Link>


                                        <!-- Delete -->
                                        <button
                                            v-if="
                                                Number(page.props.auth.user?.id) ===
                                                Number(answer.user?.id)
                                            "
                                            type="button"
                                            @click="deleteAnswer(answer.id)"
                                            class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-sm text-red-600 transition hover:bg-red-100"
                                        >
                                            Delete
                                        </button>


                                        <!-- Report -->
                                        <Link
                                            v-if="
                                                Number(page.props.auth.user?.id) !==
                                                Number(answer.user?.id)
                                            "
                                            :href="`/answers/${answer.id}/report?return_url=/questions/${question.id}`"
                                            class="rounded-lg px-3 py-1.5 text-sm text-gray-500 transition hover:bg-red-50 hover:text-red-600"
                                        >
                                            Report
                                        </Link>

                                    </div>


                                    <!-- Answer Author -->
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 font-semibold text-blue-700"
                                        >
                                            {{ answer.user.name.charAt(0).toUpperCase() }}
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-400">
                                                Answered by
                                            </p>

                                            <p class="text-sm font-medium text-gray-700">
                                                {{ answer.user.name }}
                                            </p>
                                        </div>

                                    </div>

                                </div>


                                <!-- Accept Answer -->
                                <div
                                    v-if="question.user.id === page.props.auth.user?.id"
                                    class="mt-5"
                                >
                                    <button
                                        type="button"
                                        @click="acceptAnswer(answer.id)"
                                        class="rounded-lg border border-green-300 px-4 py-2 text-sm font-medium text-green-600 transition hover:bg-green-50"
                                    >
                                        {{
                                            answer.is_accepted
                                                ? '✓ Unaccept Answer'
                                                : '✓ Accept Answer'
                                        }}
                                    </button>
                                </div>


                                <!-- Answer Comments -->
                                <div class="mt-6 border-t border-gray-100 pt-5">

                                    <h3 class="mb-4 text-sm font-semibold text-gray-700">
                                        {{ answer.comments.length }}
                                        {{
                                            answer.comments.length === 1
                                                ? 'Comment'
                                                : 'Comments'
                                        }}
                                    </h3>


                                    <div
                                        v-for="comment in answer.comments"
                                        :key="comment.id"
                                        class="border-b border-gray-100 py-3 last:border-0"
                                    >
                                        <p class="text-sm leading-6 text-gray-700">
                                            {{ comment.content }}
                                        </p>

                                        <div
                                            class="mt-1 flex items-center gap-3 text-xs text-gray-400"
                                        >
                                            <span class="font-medium text-gray-500">
                                                {{ comment.user.name }}
                                            </span>

                                            <span>•</span>

                                            <span>
                                                {{
                                                    new Date(
                                                        comment.created_at
                                                    ).toLocaleDateString()
                                                }}
                                            </span>

                                            <Link
                                                v-if="
                                                    Number(page.props.auth.user?.id) !==
                                                    Number(comment.user?.id)
                                                "
                                                :href="`/comments/${comment.id}/report?return_url=/questions/${question.id}`"
                                                class="text-red-500 hover:underline"
                                            >
                                                Report
                                            </Link>
                                        </div>
                                    </div>


                                    <!-- Answer Comment Form -->
                                    <template v-if="page.props.auth.user">

                                        <form
                                            class="mt-4"
                                            @submit.prevent="submitAnswerComment(answer.id)"
                                        >
                                            <textarea
                                                v-model="commentForm.content"
                                                rows="2"
                                                placeholder="Add a comment..."
                                                class="w-full resize-none rounded-xl border border-gray-200 p-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                            ></textarea>

                                            <button
                                                type="submit"
                                                :disabled="commentForm.processing"
                                                class="mt-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800 disabled:opacity-50"
                                            >
                                                {{
                                                    commentForm.processing
                                                        ? 'Adding...'
                                                        : 'Add Comment'
                                                }}
                                            </button>
                                        </form>

                                    </template>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>


                <!-- No Answers -->
                <div
                    v-if="question.answers.length === 0"
                    class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center"
                >
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"
                    >
                        ?
                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-gray-900">
                        No answers yet
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Be the first developer to help solve this problem.
                    </p>
                </div>


                <!-- Your Answer -->
                <div
                    id="your-answer"
                    class="mt-10 scroll-mt-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8"
                >

                    <template v-if="page.props.auth.user">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Your Answer
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            Share your solution and help another developer.
                        </p>

                        <form
                            class="mt-6"
                            @submit.prevent="submitAnswer"
                        >
                            <textarea
                                v-model="form.content"
                                rows="8"
                                placeholder="Write your answer here..."
                                class="w-full resize-none rounded-xl border border-gray-200 p-4 text-sm leading-7 text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            ></textarea>

                            <p
                                v-if="form.errors.content"
                                class="mt-2 text-sm text-red-500"
                            >
                                {{ form.errors.content }}
                            </p>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="mt-4 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {{
                                    form.processing
                                        ? 'Posting...'
                                        : 'Post Your Answer'
                                }}
                            </button>
                        </form>

                    </template>


                    <!-- Guest -->
                    <template v-else>

                        <div class="text-center">

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"
                            >
                                ✦
                            </div>

                            <h2 class="mt-4 text-xl font-bold text-gray-900">
                                Have an answer?
                            </h2>

                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                                Log in to share your knowledge, answer questions,
                                and help other developers.
                            </p>

                            <Link
                                href="/login"
                                class="mt-5 inline-flex rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                            >
                                Log in to answer
                            </Link>

                        </div>

                    </template>

                </div>

            </section>

        </div>
    </div>
</template>
