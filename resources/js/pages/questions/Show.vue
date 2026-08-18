<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3';

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
    <div class="mx-auto max-w-4xl py-10">
        <!-- Question -->
        <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-md">
            <h1 class="text-4xl font-bold text-gray-800">
                {{ question.title }}
            </h1>

            <p class="mt-6 leading-8 text-gray-700">
                {{ question.description }}
            </p>

        <!-- Question Tag -->
        <div class="mt-4 flex flex-wrap gap-2">
            <a  
                :href="`/questions/tag/${tag.slug}`"
                v-for="tag in question.tags"
                :key="tag.id"
                class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">
                    {{ tag.name }}
            </a>
        </div>

            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    👤 Asked by {{ question.user.name }}
                </div>

                <div class="text-sm text-gray-400">
                    {{ new Date(question.created_at).toLocaleDateString() }}
                </div>
            </div>
        </div>

        <!-- Question Actions -->
    <div
        v-if="page.props.auth.user.id === question.user.id"
        class="mt-6 flex gap-3">
        <a
            :href="`/questions/${question.id}/edit`"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Edit
        </a>

        <button
            type="button"
            @click="deleteQuestion"
            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                Delete
        </button>
    </div>


        <!-- Question Voting -->
        <div class="mt-6 flex items-center gap-3">
            <button
                type="button"
                @click="voteQuestion(question.id, 'up')"
                class="rounded-xl border border-gray-200 px-4 py-2 transition-all duration-300 hover:scale-105 hover:border-green-300 hover:bg-green-50"
            >
                👍 Upvote
            </button>

            <span class="font-semibold text-gray-700">
                {{ question.votes.length }}
            </span>
        </div>

        <!-- Question Comments -->
<div class="mt-8 border-t border-gray-100 pt-6">
    <h3 class="mb-4 text-lg font-semibold text-gray-800">
        {{ question.comments.length }} Comments
    </h3>

    <!-- Existing Comments -->
    <div
        v-for="comment in question.comments"
        :key="comment.id"
        class="mb-3 border-b border-gray-100 pb-3"
    >
        <p class="text-sm text-gray-700">
            {{ comment.content }}
        </p>

        <div class="mt-1 text-xs text-gray-400">
            {{ comment.user.name }}
            ·
            {{ new Date(comment.created_at).toLocaleDateString() }}
        </div>
    </div>

    <!-- Add Comment -->
    <form
        @submit.prevent="submitComment('question', question.id)"
        class="mt-4"
    >
        <textarea
            v-model="commentForm.content"
            rows="3"
            placeholder="Add a comment..."
            class="w-full resize-none rounded-xl border border-gray-300 p-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500"
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
            class="mt-2 rounded-xl bg-gray-800 px-4 py-2 text-sm text-white transition-all duration-300 hover:scale-105 hover:bg-gray-700 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
        >
            {{
                commentForm.processing
                    ? 'Posting...'
                    : 'Add Comment'
            }}
        </button>
    </form>
</div>

        <!-- Answers -->
        <div class="mt-10">
            <h2 class="mb-6 text-2xl font-bold text-gray-800">
                {{ question.answers.length }} Answers
            </h2>

            <!-- Answer Cards -->
            <div
                v-for="answer in question.answers"
                :key="answer.id"
                class="mb-5 rounded-2xl border p-6 shadow-sm transition-all duration-300"
                :class="
                answer.is_accepted
                ? 'border-green-300 bg-green-50'
                : 'border-gray-100 bg-white'
                "
            >
                <div
                     v-if="answer.is_accepted"
                    class="mb-4 flex items-center gap-2 font-semibold text-green-600"
>
                    <span>✓</span>
                    <span>Accepted Answer</span>
                </div>
                <p class="leading-7 text-gray-700">
                    {{ answer.content }}
                </p>

                <!-- Answer Voting -->
                <div class="mt-5 flex items-center gap-3">
                    <button
                        type="button"
                        @click="voteAnswer(answer.id, 'up')"
                        class="rounded-xl border border-gray-200 px-4 py-2 transition-all duration-300 hover:scale-105 hover:border-green-300 hover:bg-green-50"
                    >
                        👍 Upvote
                    </button>

                    <span class="font-semibold text-gray-700">
                        {{ answer.votes.length }}
                    </span>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        👤 {{ answer.user.name }}
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-400">
                            {{ new Date(answer.created_at).toLocaleDateString() }}
                        </div>

                        <a
                            v-if="page.props.auth.user?.id === answer.user.id"
                            :href="`/questions/${question.id}/answers/${answer.id}/edit`"
                            class="rounded-lg border border-blue-500 px-3 py-1.5 text-sm text-blue-600 transition hover:bg-blue-50"
                            >
                            Edit Answer
                        </a>
                        
                        <button
                            v-if="page.props.auth.user?.id === answer.user.id"
                            type="button"
                            @click="deleteAnswer(answer.id)"
                            class="rounded-lg border border-red-500 px-3 py-1.5 text-sm text-red-600 transition hover:bg-red-50"
                        >
                            Delete Answer
                        </button>
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
                    class="rounded-xl border border-green-500 px-4 py-2 text-green-600 transition-all duration-300 hover:scale-105 hover:bg-green-50 active:scale-95"
    >
                     {{ answer.is_accepted ? '✓ Unaccept Answer' : '✓ Accept Answer' }}
                </button>
            </div>

            <!-- Answer Comments -->
<div class="mt-6 border-t pt-4">
    <h4 class="text-sm font-semibold text-gray-700">
        {{ answer.comments.length }}
        {{ answer.comments.length === 1 ? 'Comment' : 'Comments' }}
    </h4>

    <div
        v-for="comment in answer.comments"
        :key="comment.id"
        class="mt-3 border-b pb-3"
    >
        <p class="text-sm text-gray-800">
            {{ comment.content }}
        </p>

        <p class="mt-1 text-xs text-gray-400">
            {{ comment.user.name }} ·
            {{ new Date(comment.created_at).toLocaleDateString() }}
        </p>
    </div>

    <!-- Add Answer Comment -->
    <form
        class="mt-4"
        @submit.prevent="submitAnswerComment(answer.id)"
    >
        <textarea
            v-model="commentForm.content"
            rows="3"
            placeholder="Add a comment..."
            class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:border-gray-500 focus:outline-none"
        ></textarea>

        <button
            type="submit"
            :disabled="commentForm.processing"
            class="mt-2 rounded-xl bg-gray-900 px-4 py-2 text-sm text-white transition-all duration-300 hover:scale-105 hover:bg-gray-800 active:scale-95 disabled:opacity-50"
        >
            {{ commentForm.processing ? 'Adding...' : 'Add Comment' }}
        </button>
    </form>
</div>
            </div>


            <!-- No Answers -->
            <div
                v-if="question.answers.length === 0"
                class="rounded-2xl bg-gray-50 p-8 text-center"
            >
                <p class="text-gray-500">No answers yet.</p>

                <p class="mt-2 text-sm text-gray-400">
                    Be the first person to answer this question!
                </p>
            </div>
        </div>

        <!-- Add Answer -->
        <div class="mt-10">
            <h2 class="mb-6 text-2xl font-bold text-gray-800">Your Answer</h2>

            <form @submit.prevent="submitAnswer">
                <textarea
                    v-model="form.content"
                    rows="6"
                    placeholder="Write your answer here..."
                    class="w-full resize-none rounded-xl border border-gray-300 p-4 text-gray-700 focus:border-blue-500 focus:ring-blue-500"
                ></textarea>

                <p v-if="form.errors.content" class="mt-2 text-sm text-red-500">
                    {{ form.errors.content }}
                </p>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="mt-4 rounded-xl bg-blue-600 px-6 py-3 text-white transition-all duration-300 hover:scale-105 hover:bg-blue-700 hover:shadow-lg active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ form.processing ? 'Posting...' : 'Post Answer' }}
                </button>
            </form>
        </div>
    </div>
</template>
