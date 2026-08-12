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

            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    👤 Asked by {{ question.user.name }}
                </div>

                <div class="text-sm text-gray-400">
                    {{ new Date(question.created_at).toLocaleDateString() }}
                </div>
            </div>
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

                    <div class="text-sm text-gray-400">
                        {{ new Date(answer.created_at).toLocaleDateString() }}
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
