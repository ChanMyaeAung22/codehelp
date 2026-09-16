<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const isAdmin = computed(() => (page.props.auth as any)?.user?.is_admin);

interface Question {
    id: number;
    title: string;
    description: string;
    created_at: string;

    user: {
        id: number;
        name: string;
    };

    answers: Array<{
        id: number;
    }>;

    votes: Array<{
        id: number;
    }>;

    tags: Tag[];
}

interface Tag {
    id: number;
    name: string;
    slug: string;
}

interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Paginator {
    data: Question[];
    links: PaginatorLink[];
    current_page: number;
    last_page: number;
    total: number;
    from: number | null;
    to: number | null;
}

const props = defineProps<{
    questions: Paginator;
    selectedTag?: Tag;
    sort?: string;
}>();

const searchTerm = ref('');

function search() {
    router.get(
        '/questions',
        {
            search: searchTerm.value,
            sort: props.sort ?? 'latest',
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

function sortBy(value: string) {
    router.get(
        '/questions',
        {
            search: searchTerm.value,
            sort: value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

/*
 * Use a fixed locale so the server and browser
 * render the same date format.
 */
function formatDate(date: string) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        timeZone: 'UTC',
    }).format(new Date(date));
}

// Decode HTML entities in Laravel paginator labels (e.g. &laquo; &raquo;)
function decodeLabel(label: string): string {
    return label
        .replace(/&laquo;/g, '\u00AB')
        .replace(/&raquo;/g, '\u00BB')
        .replace(/&amp;/g, '&');
}
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-10 lg:px-8">

            <!-- Page Header -->
            <div
                class="mb-8 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <!-- Small label -->
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700"
                    >
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        Developer Community
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl"
                    >
                        All Questions<span class="text-blue-600">.</span>
                    </h1>

                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-500">
                        Browse programming questions, share knowledge, and
                        learn from developers in the community.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex shrink-0 items-center gap-3">
                    <!-- Go to Admin (admin only) -->
                    <Link
                        v-if="isAdmin"
                        href="/admin"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 hover:shadow-md"
                    >
                        <span>⚙️</span>
                        Go to Admin
                    </Link>

                    <!-- Leaderboard -->
                    <Link
                        href="/leaderboard"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 hover:shadow-md"
                    >
                        <span>🏆</span>
                        Leaderboard
                    </Link>

                    <!-- Ask Question -->
                    <Link
                        href="/questions/create"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/25"
                    >
                        <span class="mr-2 text-lg">+</span>
                        Ask Question
                    </Link>
                </div>
            </div>

            <!-- Selected Tag -->
            <div
                v-if="selectedTag"
                class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4"
            >
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">
                            Browsing questions tagged
                        </p>

                        <h2 class="mt-1 text-lg font-bold text-slate-900">
                            {{ selectedTag.name }}
                        </h2>
                    </div>

                    <Link
                        href="/questions"
                        class="text-sm font-semibold text-blue-600 transition hover:text-blue-800 hover:underline"
                    >
                        View all questions →
                    </Link>
                </div>
            </div>

            <!-- Search -->
            <form
                @submit.prevent="search"
                class="mb-10 flex flex-col gap-3 sm:flex-row"
            >
                <div class="relative flex-1">
                    <!-- Search icon -->
                    <span
                        class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                    >
                        🔍
                    </span>

                    <input
                        v-model="searchTerm"
                        type="text"
                        placeholder="Search questions..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-sm text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    />
                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-7 py-3.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-blue-700 hover:shadow-md"
                >
                    Search
                </button>
            </form>

            <!-- Sort Toggle -->
            <div class="mb-6 flex items-center gap-2">
                <span class="text-sm font-medium text-slate-500">Sort by:</span>

                <button
                    type="button"
                    @click="sortBy('latest')"
                    :class="[
                        'rounded-xl border px-4 py-2 text-sm font-semibold transition-all duration-200',
                        (sort ?? 'latest') === 'latest'
                            ? 'border-blue-600 bg-blue-600 text-white shadow-md shadow-blue-600/20'
                            : 'border-slate-200 bg-white text-slate-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700',
                    ]"
                >
                    🕐 Latest
                </button>

                <button
                    type="button"
                    @click="sortBy('popular')"
                    :class="[
                        'rounded-xl border px-4 py-2 text-sm font-semibold transition-all duration-200',
                        sort === 'popular'
                            ? 'border-blue-600 bg-blue-600 text-white shadow-md shadow-blue-600/20'
                            : 'border-slate-200 bg-white text-slate-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700',
                    ]"
                >
                    🔥 Popular
                </button>
            </div>

            <!-- Question Count -->
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">
                        {{ questions.total }}
                        {{ questions.total === 1 ? 'Question' : 'Questions' }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{
                            sort === 'popular'
                                ? 'Sorted by popularity score (upvotes − downvotes)'
                                : 'Recent questions from the community'
                        }}
                    </p>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="questions.data.length === 0"
                class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm"
            >
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"
                >
                    ?
                </div>

                <h2 class="mt-5 text-xl font-bold text-slate-900">
                    No questions found
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    Try searching with a different keyword or be the first
                    developer to ask a question.
                </p>

                <Link
                    href="/questions/create"
                    class="mt-6 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Ask a Question
                </Link>
            </div>

            <!-- Question Cards -->
            <div v-else class="space-y-5">
                <article
                    v-for="question in questions.data"
                    :key="question.id"
                    class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50"
                >
                    <div class="p-6 sm:p-7">

                        <!-- Question Title -->
                        <Link
                            :href="`/questions/${question.id}`"
                            class="block text-xl font-bold leading-8 text-slate-900 transition-colors group-hover:text-blue-600 sm:text-2xl"
                        >
                            {{ question.title }}
                        </Link>

                        <!-- Description -->
                        <p
                            class="mt-3 line-clamp-2 text-sm leading-6 text-slate-500 sm:text-base"
                        >
                            {{ question.description }}
                        </p>

                        <!-- Tags -->
                        <div class="mt-5 flex flex-wrap gap-2">
                            <Link
                                v-for="tag in question.tags"
                                :key="tag.id"
                                :href="`/questions/tag/${tag.slug}`"
                                class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-100"
                            >
                                {{ tag.name }}
                            </Link>
                        </div>

                        <!-- Question Stats -->
                        <div
                            class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-3 text-sm text-slate-500"
                        >
                            <!-- Answers -->
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600"
                                >
                                    💬
                                </span>

                                <span>
                                    <strong class="font-semibold text-slate-700">
                                        {{ question.answers.length }}
                                    </strong>
                                    {{
                                        question.answers.length === 1
                                            ? 'Answer'
                                            : 'Answers'
                                    }}
                                </span>
                            </div>

                            <!-- Votes -->
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600"
                                >
                                    ↑
                                </span>

                                <span>
                                    <strong class="font-semibold text-slate-700">
                                        {{ question.votes.length }}
                                    </strong>
                                    {{
                                        question.votes.length === 1
                                            ? 'Vote'
                                            : 'Votes'
                                    }}
                                </span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="mt-6 flex flex-col gap-4 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <!-- User -->
                            <Link
                                :href="`/users/${question.user.id}`"
                                class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-blue-50"
                                @click.stop
                            >
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-sm font-bold text-blue-700"
                                >
                                    {{ question.user.name.charAt(0).toUpperCase() }}
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Asked by
                                    </p>

                                    <p class="text-sm font-semibold text-blue-700">
                                        {{ question.user.name }}
                                    </p>
                                </div>
                            </Link>

                            <!-- Date -->
                            <div class="text-sm text-slate-400">
                                {{ formatDate(question.created_at) }}
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Pagination -->
            <div
                v-if="questions.last_page > 1"
                class="mt-10 flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="link in questions.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-scroll
                        :class="[
                            'inline-flex min-w-[2.5rem] items-center justify-center rounded-xl border px-3.5 py-2 text-sm font-semibold transition-all duration-200',
                            link.active
                                ? 'border-blue-600 bg-blue-600 text-white shadow-md shadow-blue-600/20'
                                : 'border-slate-200 bg-white text-slate-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700',
                        ]"
                    >{{ decodeLabel(link.label) }}</Link>
                    <span
                        v-else
                        class="inline-flex min-w-[2.5rem] items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm font-semibold text-slate-400"
                    >{{ decodeLabel(link.label) }}</span>
                </template>
            </div>

        </div>
    </div>
</template>