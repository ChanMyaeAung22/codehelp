<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface LeaderboardUser {
    id: number;
    rank: number;
    name: string;
    created_at: string;
    reputation: number;
}

defineProps<{
    users: LeaderboardUser[];
}>();

function memberSince(date: string) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        timeZone: 'UTC',
    }).format(new Date(date));
}

/** Returns a medal emoji for the top 3 ranks, nothing otherwise */
function medal(rank: number): string {
    if (rank === 1) return '🥇';
    if (rank === 2) return '🥈';
    if (rank === 3) return '🥉';
    return '';
}

/** Returns row highlight classes for top 3 */
function rowClass(rank: number): string {
    if (rank === 1)
        return 'border-yellow-300 bg-yellow-50/60 shadow-yellow-100/60';
    if (rank === 2)
        return 'border-slate-300 bg-slate-50/60 shadow-slate-100/60';
    if (rank === 3)
        return 'border-orange-200 bg-orange-50/60 shadow-orange-100/60';
    return 'border-slate-200 bg-white';
}

/** Avatar background colour cycles for variety */
function avatarBg(rank: number): string {
    const colours = [
        'bg-blue-100 text-blue-700',
        'bg-violet-100 text-violet-700',
        'bg-emerald-100 text-emerald-700',
        'bg-rose-100 text-rose-700',
        'bg-amber-100 text-amber-700',
    ];
    return colours[(rank - 1) % colours.length];
}
</script>

<template>
    <Head title="Leaderboard — CodeHelp" />

    <div class="min-h-screen bg-slate-50 text-slate-900">

        <!-- ===== MAIN CONTENT ===== -->
        <main class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-10 text-center">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700">
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    Community Rankings
                </div>

                <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                    Leaderboard<span class="text-blue-600">.</span>
                </h1>

                <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-slate-500">
                    Top contributors ranked by reputation — earned through upvotes on
                    questions and answers.
                </p>

                <!-- Reputation legend -->
                <div class="mt-6 inline-flex flex-wrap justify-center gap-4 rounded-2xl border border-slate-200 bg-white px-6 py-3 text-xs text-slate-500 shadow-sm">
                    <span class="flex items-center gap-1.5">
                        <span class="font-bold text-green-600">+10</span> question upvote
                    </span>
                    <span class="text-slate-300">|</span>
                    <span class="flex items-center gap-1.5">
                        <span class="font-bold text-red-500">−2</span> question downvote
                    </span>
                    <span class="text-slate-300">|</span>
                    <span class="flex items-center gap-1.5">
                        <span class="font-bold text-green-600">+10</span> answer upvote
                    </span>
                    <span class="text-slate-300">|</span>
                    <span class="flex items-center gap-1.5">
                        <span class="font-bold text-red-500">−2</span> answer downvote
                    </span>
                </div>
            </div>

            <!-- Top 3 Podium (visible on md+) -->
            <div v-if="users.length >= 3" class="mb-10 hidden grid-cols-3 gap-4 md:grid">

                <!-- 2nd place -->
                <div class="flex flex-col items-center justify-end pt-10">
                    <div class="mb-2 text-3xl">🥈</div>
                    <Link :href="`/users/${users[1].id}`"
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl font-bold text-slate-700 shadow transition hover:scale-105">
                        {{ users[1].name.charAt(0).toUpperCase() }}
                    </Link>
                    <Link :href="`/users/${users[1].id}`"
                        class="mt-2 text-sm font-semibold text-slate-800 transition hover:text-blue-600">
                        {{ users[1].name }}
                    </Link>
                    <div class="mt-1 rounded-full bg-slate-100 px-3 py-0.5 text-xs font-bold text-slate-600">
                        {{ users[1].reputation.toLocaleString() }} rep
                    </div>
                    <div class="mt-4 h-24 w-full rounded-t-xl bg-slate-200 shadow-inner"></div>
                </div>

                <!-- 1st place -->
                <div class="flex flex-col items-center justify-end">
                    <div class="mb-2 text-4xl">🥇</div>
                    <Link :href="`/users/${users[0].id}`"
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-yellow-100 text-2xl font-bold text-yellow-700 shadow-lg ring-4 ring-yellow-400/40 transition hover:scale-105">
                        {{ users[0].name.charAt(0).toUpperCase() }}
                    </Link>
                    <Link :href="`/users/${users[0].id}`"
                        class="mt-2 text-base font-bold text-slate-900 transition hover:text-blue-600">
                        {{ users[0].name }}
                    </Link>
                    <div class="mt-1 rounded-full bg-yellow-100 px-3 py-0.5 text-xs font-bold text-yellow-700">
                        {{ users[0].reputation.toLocaleString() }} rep
                    </div>
                    <div class="mt-4 h-36 w-full rounded-t-xl bg-yellow-200 shadow-inner"></div>
                </div>

                <!-- 3rd place -->
                <div class="flex flex-col items-center justify-end pt-16">
                    <div class="mb-2 text-3xl">🥉</div>
                    <Link :href="`/users/${users[2].id}`"
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-lg font-bold text-orange-700 shadow transition hover:scale-105">
                        {{ users[2].name.charAt(0).toUpperCase() }}
                    </Link>
                    <Link :href="`/users/${users[2].id}`"
                        class="mt-2 text-sm font-semibold text-slate-800 transition hover:text-blue-600">
                        {{ users[2].name }}
                    </Link>
                    <div class="mt-1 rounded-full bg-orange-100 px-3 py-0.5 text-xs font-bold text-orange-700">
                        {{ users[2].reputation.toLocaleString() }} rep
                    </div>
                    <div class="mt-4 h-16 w-full rounded-t-xl bg-orange-200 shadow-inner"></div>
                </div>
            </div>

            <!-- Full Leaderboard Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <!-- Table header -->
                <div class="grid grid-cols-12 gap-4 border-b border-slate-100 bg-slate-50 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <div class="col-span-1 text-center">#</div>
                    <div class="col-span-6">User</div>
                    <div class="col-span-3 text-right">Reputation</div>
                    <div class="col-span-2 hidden text-right sm:block">Member Since</div>
                </div>

                <!-- Empty state -->
                <div v-if="users.length === 0" class="px-6 py-16 text-center text-slate-400">
                    <div class="mb-3 text-4xl">🏆</div>
                    <p class="text-sm">No users yet. Be the first to earn reputation!</p>
                </div>

                <!-- Rows -->
                <div
                    v-for="user in users"
                    :key="user.id"
                    class="grid grid-cols-12 items-center gap-4 border-b px-5 py-4 transition-all duration-150 last:border-0 hover:bg-slate-50"
                    :class="rowClass(user.rank)"
                >
                    <!-- Rank -->
                    <div class="col-span-1 text-center">
                        <span v-if="medal(user.rank)" class="text-xl leading-none">
                            {{ medal(user.rank) }}
                        </span>
                        <span v-else class="text-sm font-semibold text-slate-400">
                            {{ user.rank }}
                        </span>
                    </div>

                    <!-- User -->
                    <div class="col-span-6 flex items-center gap-3 min-w-0">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-bold"
                            :class="avatarBg(user.rank)"
                        >
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>

                        <div class="min-w-0">
                            <Link
                                :href="`/users/${user.id}`"
                                class="block truncate text-sm font-semibold text-slate-800 transition hover:text-blue-600"
                            >
                                {{ user.name }}
                            </Link>
                        </div>
                    </div>

                    <!-- Reputation -->
                    <div class="col-span-3 text-right">
                        <span
                            class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-sm font-bold"
                            :class="
                                user.rank === 1
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : user.rank === 2
                                      ? 'bg-slate-100 text-slate-600'
                                      : user.rank === 3
                                        ? 'bg-orange-100 text-orange-700'
                                        : 'bg-blue-50 text-blue-700'
                            "
                        >
                            ⭐ {{ user.reputation.toLocaleString() }}
                        </span>
                    </div>

                    <!-- Member since (hidden on mobile) -->
                    <div class="col-span-2 hidden text-right text-xs text-slate-400 sm:block">
                        {{ memberSince(user.created_at) }}
                    </div>
                </div>
            </div>

            <!-- Footer note -->
            <p class="mt-6 text-center text-xs text-slate-400">
                Reputation is calculated from votes received on questions and answers.
                Minimum reputation is 1.
            </p>

        </main>
    </div>
</template>
