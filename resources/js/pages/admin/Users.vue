<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Search,
    RotateCcw,
    Users,
    ShieldCheck,
    ShieldBan,
    UserRoundCheck,
    UserRoundX,
    Clock,
    X,
    ChevronLeft,
    ChevronRight,
} from '@lucide/vue';

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
    is_admin: boolean;
    status: 'active' | 'suspended' | 'banned';
    suspended_until: string | null;
    suspension_reason: string | null;
}

interface Pagination {
    data: User[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    users: Pagination;
    filters: {
        search: string | null;
    };
}>();

const selectedUser = ref<User | null>(null);
const showSuspendModal = ref(false);

const search = ref(props.filters.search ?? '');

const openSuspendModal = (user: User) => {
    selectedUser.value = user;
    showSuspendModal.value = true;
};

const closeSuspendModal = () => {
    selectedUser.value = null;
    showSuspendModal.value = false;
};

const suspendUser = (days: number) => {
    if (!selectedUser.value) {
        return;
    }

    router.patch(
        `/admin/users/${selectedUser.value.id}/suspend`,
        {
            days,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                closeSuspendModal();
            },
        },
    );
};

const unsuspendUser = (user: User) => {
    if (!confirm(`Are you sure you want to unsuspend ${user.name}?`)) {
        return;
    }

    router.patch(
        `/admin/users/${user.id}/unsuspend`,
        {},
        {
            preserveScroll: true,
        },
    );
};

const banUser = (user: User) => {
    if (
        !confirm(
            `Are you sure you want to permanently ban ${user.name}?`,
        )
    ) {
        return;
    }

    router.patch(
        `/admin/users/${user.id}/ban`,
        {},
        {
            preserveScroll: true,
        },
    );
};

const unbanUser = (user: User) => {
    if (!confirm(`Are you sure you want to unban ${user.name}?`)) {
        return;
    }

    router.patch(`/admin/users/${user.id}/unban`);
};

const searchUsers = () => {
    router.get(
        '/admin/users',
        {
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const resetSearch = () => {
    search.value = '';

    router.get(
        '/admin/users',
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const formatDate = (date: string) => {
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(new Date(date));
};

const getInitial = (name: string) => {
    return name.charAt(0).toUpperCase();
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
                    <!-- Small badge -->
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700"
                    >
                        <span
                            class="flex h-2 w-2 rounded-full bg-blue-600"
                        ></span>

                        User Management
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tight text-slate-900"
                    >
                        Manage Users<span class="text-blue-600">.</span>
                    </h1>

                    <p class="mt-2 text-base text-slate-500">
                        View and manage registered members of the CodeHelp
                        community.
                    </p>
                </div>

                <!-- Back -->
                <Link
                    href="/admin"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                >
                    ← Dashboard
                </Link>
            </div>

            <!-- Search Card -->
            <div
                class="mb-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <form
                    @submit.prevent="searchUsers"
                    class="flex flex-col gap-3 md:flex-row"
                >
                    <div class="relative flex-1">
                        <Search
                            class="absolute left-4 top-1/2 size-5 -translate-y-1/2 text-slate-400"
                        />

                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search users by name or email..."
                            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        />
                    </div>

                    <button
                        type="submit"
                        class="inline-flex h-12 cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md active:scale-[0.98]"
                    >
                        <Search class="size-4" />
                        Search
                    </button>

                    <button
                        type="button"
                        @click="resetSearch"
                        class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-amber-600 transition hover:bg-amber-50"
                    >
                        <RotateCcw class="size-4" />
                        Reset
                    </button>
                </form>
            </div>

            <!-- Users Card -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <!-- Table Header -->
                <div
                    class="flex flex-col justify-between gap-2 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                        >
                            <Users class="size-5" />
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Registered Users
                            </h2>

                            <p class="text-sm text-slate-500">
                                {{ users.total }} total users
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead class="border-b border-slate-100 bg-slate-50/70">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    User
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Email
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Role
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Status
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Registered
                                </th>

                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="user in users.data"
                                :key="user.id"
                                class="transition hover:bg-slate-50/70"
                            >
                                <!-- User -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex size-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600"
                                        >
                                            {{ getInitial(user.name) }}
                                        </div>

                                        <div>
                                            <p
                                                class="font-semibold text-slate-800"
                                            >
                                                {{ user.name }}
                                            </p>

                                            <p
                                                class="text-xs text-slate-400"
                                            >
                                                User #{{ user.id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td
                                    class="px-6 py-5 text-sm text-slate-600"
                                >
                                    {{ user.email }}
                                </td>

                                <!-- Role -->
                                <td class="px-6 py-5">
                                    <span
                                        v-if="user.is_admin"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-700"
                                    >
                                        <ShieldCheck class="size-3.5" />
                                        Admin
                                    </span>

                                    <span
                                        v-else
                                        class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600"
                                    >
                                        User
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-5">
                                    <span
                                        v-if="user.status === 'active'"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"
                                    >
                                        <span
                                            class="size-1.5 rounded-full bg-emerald-500"
                                        ></span>

                                        Active
                                    </span>

                                    <span
                                        v-else-if="user.status === 'suspended'"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700"
                                    >
                                        <Clock class="size-3.5" />
                                        Suspended
                                    </span>

                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700"
                                    >
                                        <ShieldBan class="size-3.5" />
                                        Banned
                                    </span>
                                </td>

                                <!-- Registered -->
                                <td
                                    class="px-6 py-5 text-sm text-slate-500"
                                >
                                    {{ formatDate(user.created_at) }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-5">
                                    <div
                                        class="flex justify-end gap-2"
                                    >
                                        <!-- Active -->
                                        <template
                                            v-if="user.status === 'active'"
                                        >
                                            <button
                                                v-if="!user.is_admin"
                                                type="button"
                                                @click="openSuspendModal(user)"
                                                class="inline-flex items-center cursor-pointer gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-amber-600 transition hover:bg-amber-50"
                                            >
                                                <Clock class="size-3.5" />
                                                Suspend
                                            </button>

                                            <button
                                                v-if="!user.is_admin"
                                                type="button"
                                                @click="banUser(user)"
                                                class="inline-flex items-center cursor-pointer gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                            >
                                                <ShieldBan class="size-3.5" />
                                                Ban
                                            </button>
                                        </template>

                                        <!-- Suspended -->
                                        <template
                                            v-else-if="
                                                user.status === 'suspended'
                                            "
                                        >
                                            <button
                                                type="button"
                                                @click="unsuspendUser(user)"
                                                class="inline-flex items-center cursor-pointer gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50"
                                            >
                                                <UserRoundCheck
                                                    class="size-3.5"
                                                />
                                                Unsuspend
                                            </button>

                                            <button
                                                v-if="!user.is_admin"
                                                type="button"
                                                @click="banUser(user)"
                                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                            >
                                                <ShieldBan class="size-3.5" />
                                                Ban
                                            </button>
                                        </template>

                                        <!-- Banned -->
                                        <template
                                            v-else-if="
                                                user.status === 'banned'
                                            "
                                        >
                                            <button
                                                type="button"
                                                @click="unbanUser(user)"
                                                class="inline-flex items-center cursor-pointer gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-50"
                                            >
                                                <UserRoundCheck
                                                    class="size-3.5"
                                                />
                                                Unban
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty -->
                            <tr v-if="users.data.length === 0">
                                <td
                                    colspan="6"
                                    class="px-6 py-16 text-center"
                                >
                                    <div
                                        class="mx-auto flex size-14 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                    >
                                        <Users class="size-7" />
                                    </div>

                                    <h3
                                        class="mt-4 font-semibold text-slate-800"
                                    >
                                        No users found
                                    </h3>

                                    <p
                                        class="mt-1 text-sm text-slate-500"
                                    >
                                        Try changing your search criteria.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
<div
    v-if="users.last_page > 1"
    class="flex flex-col gap-4 border-t border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
>
    <!-- Pagination Info -->
    <p class="text-sm text-slate-500">
        Showing page
        <span class="font-semibold text-slate-700">
            {{ users.current_page }}
        </span>
        of
        <span class="font-semibold text-slate-700">
            {{ users.last_page }}
        </span>

        ·

        <span class="font-semibold text-slate-700">
            {{ users.total }}
        </span>
        users
    </p>

    <!-- Pagination Buttons -->
    <div class="flex items-center gap-2">

        <!-- Previous -->
        <Link
            v-if="users.current_page > 1"
            :href="`/admin/users?page=${users.current_page - 1}${search ? `&search=${encodeURIComponent(search)}` : ''}`"
            preserve-scroll
            preserve-state
            class="inline-flex cursor-pointer items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            <ChevronLeft class="size-4" />
            Previous
        </Link>

        <!-- Page Numbers -->
        <template
            v-for="page in users.last_page"
            :key="page"
        >
            <Link
                :href="`/admin/users?page=${page}${search ? `&search=${encodeURIComponent(search)}` : ''}`"
                preserve-scroll
                preserve-state
                :class="[
                    'flex size-9 cursor-pointer items-center justify-center rounded-lg border text-sm font-medium transition',
                    page === users.current_page
                        ? 'border-blue-600 bg-blue-600 text-white shadow-sm'
                        : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50',
                ]"
            >
                {{ page }}
            </Link>
        </template>

        <!-- Next -->
        <Link
            v-if="users.current_page < users.last_page"
            :href="`/admin/users?page=${users.current_page + 1}${search ? `&search=${encodeURIComponent(search)}` : ''}`"
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

    <!-- Suspend Modal -->
    <div
        v-if="showSuspendModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 px-4 backdrop-blur-sm"
        @click.self="closeSuspendModal"
    >
        <div
            class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        >
            <!-- Modal Header -->
            <div
                class="flex items-start justify-between border-b border-slate-100 px-6 py-5"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex size-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600"
                    >
                        <Clock class="size-5" />
                    </div>

                    <div>
                        <h2
                            class="font-semibold text-slate-900"
                        >
                            Suspend User
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Temporarily restrict this account.
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="closeSuspendModal"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                >
                    <X class="size-5" />
                </button>
            </div>

            <!-- User -->
            <div class="px-6 pt-6">
                <div
                    class="flex items-center gap-3 rounded-xl bg-slate-50 p-4"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-full bg-blue-50 font-bold text-blue-600"
                    >
                        {{
                            selectedUser
                                ? getInitial(selectedUser.name)
                                : ''
                        }}
                    </div>

                    <div>
                        <p class="font-semibold text-slate-800">
                            {{ selectedUser?.name }}
                        </p>

                        <p class="text-sm text-slate-500">
                            {{ selectedUser?.email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Duration -->
            <div class="px-6 py-6">
                <p
                    class="mb-3 text-sm font-semibold text-slate-700"
                >
                    Select suspension duration
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button
                        type="button"
                        @click="suspendUser(7)"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-4 text-left transition hover:border-amber-300 hover:bg-amber-50"
                    >
                        <span
                            class="block font-semibold text-slate-800"
                        >
                            7 Days
                        </span>

                        <span
                            class="mt-1 block text-xs text-slate-500"
                        >
                            Short suspension
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="suspendUser(30)"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-4 text-left transition hover:border-amber-300 hover:bg-amber-50"
                    >
                        <span
                            class="block font-semibold text-slate-800"
                        >
                            30 Days
                        </span>

                        <span
                            class="mt-1 block text-xs text-slate-500"
                        >
                            One month
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="suspendUser(90)"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-4 text-left transition hover:border-amber-300 hover:bg-amber-50"
                    >
                        <span
                            class="block font-semibold text-slate-800"
                        >
                            90 Days
                        </span>

                        <span
                            class="mt-1 block text-xs text-slate-500"
                        >
                            Three months
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="suspendUser(365)"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-4 text-left transition hover:border-amber-300 hover:bg-amber-50"
                    >
                        <span
                            class="block font-semibold text-slate-800"
                        >
                            1 Year
                        </span>

                        <span
                            class="mt-1 block text-xs text-slate-500"
                        >
                            Long suspension
                        </span>
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div
                class="flex justify-end border-t border-slate-100 bg-slate-50 px-6 py-4"
            >
                <button
                    type="button"
                    @click="closeSuspendModal"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>