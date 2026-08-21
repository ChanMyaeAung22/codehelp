<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
    is_admin: boolean;
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

const search = ref(props.filters.search ?? '');

const formatDate = (date: string) => {
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(new Date(date));
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
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-10">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800">
                    Manage Users
                </h1>

                <p class="mt-2 text-gray-500">
                    View all registered users on Code Help.
                </p>
            </div>

            <Link
                href="/admin"
                class="rounded-xl border border-gray-300 px-5 py-2.5 text-gray-700 transition hover:bg-gray-50"
            >
                ← Dashboard
            </Link>
        </div>

        <!-- Search -->
<div
    class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
>
    <form
        @submit.prevent="searchUsers"
        class="flex flex-col gap-3 sm:flex-row"
    >
        <input
            v-model="search"
            type="text"
            placeholder="Search users by name or email..."
            class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
        />

        <button
            type="submit"
            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
        >
            Search
        </button>

        <button
            type="button"
            @click="resetSearch"
            class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
        >
            Reset
        </button>
    </form>
</div>

        <!-- Users Table -->
        <div
            class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-md"
        >
            <table class="w-full">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600"
                        >
                            Name
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600"
                        >
                            Email
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600"
                        >
                            Role
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600"
                        >
                            Registered
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="user in users.data"
                        :key="user.id"
                        class="border-b last:border-b-0 hover:bg-gray-50"
                    >
                        <!-- Name -->
                        <td class="px-6 py-5 font-medium text-gray-800">
                            {{ user.name }}
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-5 text-gray-600">
                            {{ user.email }}
                        </td>

                        <!-- Role -->
                        <td class="px-6 py-5">
                            <span
                                v-if="user.is_admin"
                                class="rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-700"
                            >
                                Admin
                            </span>

                            <span
                                v-else
                                class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600"
                            >
                                User
                            </span>
                        </td>

                        <!-- Registered Date -->
                        <td class="px-6 py-5 text-gray-500">
                            {{ formatDate(user.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
<div
    v-if="users.last_page > 1"
    class="mt-6 flex items-center justify-center gap-2"
>
    <Link
        v-if="users.current_page > 1"
        :href="`/admin/users?page=${users.current_page - 1}${search ? `&search=${encodeURIComponent(search)}` : ''}`"
        preserve-scroll
        preserve-state
        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
    >
        Previous
    </Link>

    <Link
        v-for="page in users.last_page"
        :key="page"
        :href="`/admin/users?page=${page}${search ? `&search=${encodeURIComponent(search)}` : ''}`"
        preserve-scroll
        preserve-state
        :class="[
            'min-w-9 rounded-lg border px-3 py-2 text-center text-sm',
            page === users.current_page
                ? 'border-blue-600 bg-blue-600 text-white'
                : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
        ]"
    >
        {{ page }}
    </Link>

    <Link
        v-if="users.current_page < users.last_page"
        :href="`/admin/users?page=${users.current_page + 1}${search ? `&search=${encodeURIComponent(search)}` : ''}`"
        preserve-scroll
        preserve-state
        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
    >
        Next
    </Link>
</div>
        </div>
    </div>
</template>