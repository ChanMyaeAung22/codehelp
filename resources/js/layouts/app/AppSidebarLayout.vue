<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { LogOut, Menu, X, Plus, ChevronDown } from '@lucide/vue';
import { ref, computed } from 'vue';

import { logout } from '@/routes';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const mobileMenuOpen = ref(false);
const userMenuOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">

        <!-- ==================== NAVBAR ==================== -->
        <header
            class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur"
        >
            <div
                class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8"
            >

                <!-- Logo -->
                <Link
                    href="/"
                    class="flex items-center gap-3"
                >
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white shadow-lg shadow-blue-600/20"
                    >
                        &lt;/&gt;
                    </div>

                    <div class="hidden sm:block">
                        <div
                            class="text-xl font-bold tracking-tight text-slate-900"
                        >
                            Code<span class="text-blue-600">Help</span>
                        </div>

                        <div
                            class="text-[11px] font-medium uppercase tracking-[0.16em] text-slate-400"
                        >
                            Developer Community
                        </div>
                    </div>
                </Link>

                <!-- Desktop Navigation -->
                <nav class="hidden items-center gap-8 md:flex">

                    <Link
                        href="/questions"
                        class="text-sm font-medium text-slate-600 transition hover:text-blue-600"
                    >
                        Questions
                    </Link>

                    <Link
                        href="/tags"
                        class="text-sm font-medium text-slate-600 transition hover:text-blue-600"
                    >
                        Tags
                    </Link>

                    <Link
                        href="/how-it-works"
                        class="text-sm font-medium text-slate-600 transition hover:text-blue-600"
                    >
                        How it works
                    </Link>

                    <Link
                        href="/questions/create"
                        class="flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/25"
                    >
                        <Plus class="h-4 w-4" />
                        Ask Question
                    </Link>

                </nav>

                <!-- User Menu -->
                <div class="hidden items-center md:flex">
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-xl px-3 py-2 transition hover:bg-slate-50"
                        @click="userMenuOpen = !userMenuOpen"
                    >
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-700"
                        >
                            {{ $page.props.auth?.user?.name?.charAt(0)?.toUpperCase() }}
                        </div>

                        <span
                            class="max-w-28 truncate text-sm font-medium text-slate-700"
                        >
                            {{ $page.props.auth?.user?.name }}
                        </span>

                        <ChevronDown
                            class="h-4 w-4 text-slate-400"
                        />
                    </button>

                    <!-- Dropdown -->
                    <div
                        v-if="userMenuOpen"
                        class="absolute right-6 top-[70px] w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-xl"
                    >
                        <Link
                            href="/settings/profile"
                            class="block rounded-lg px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50"
                            @click="userMenuOpen = false"
                        >
                            Profile
                        </Link>

                        <Link
                            href="/settings"
                            class="block rounded-lg px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50"
                            @click="userMenuOpen = false"
                        >
                            Settings
                        </Link>

                        <div class="my-1 border-t border-slate-100"></div>

                        <Link
                            :href="logout()"
                            method="post"
                            as="button"
                            class="flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-left text-sm text-red-600 transition hover:bg-red-50"
                            @click="userMenuOpen = false"
                        >
                            <LogOut class="h-4 w-4" />
                            Log out
                        </Link>
                    </div>
                </div>

                <!-- Mobile button -->
                <button
                    type="button"
                    class="rounded-lg p-2 text-slate-600 transition hover:bg-slate-100 md:hidden"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <X
                        v-if="mobileMenuOpen"
                        class="h-6 w-6"
                    />

                    <Menu
                        v-else
                        class="h-6 w-6"
                    />
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div
                v-if="mobileMenuOpen"
                class="border-t border-slate-200 bg-white px-6 py-5 md:hidden"
            >
                <nav class="flex flex-col gap-2">

                    <Link
                        href="/questions"
                        class="rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        @click="mobileMenuOpen = false"
                    >
                        Questions
                    </Link>

                    <Link
                        href="/tags"
                        class="rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        @click="mobileMenuOpen = false"
                    >
                        Tags
                    </Link>

                    <Link
                        href="/how-it-works"
                        class="rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        @click="mobileMenuOpen = false"
                    >
                        How it works
                    </Link>

                    <Link
                        href="/questions/create"
                        class="mt-2 flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white"
                        @click="mobileMenuOpen = false"
                    >
                        <Plus class="h-4 w-4" />
                        Ask Question
                    </Link>

                    <div class="my-2 border-t border-slate-200"></div>

                    <Link
                        href="/settings/profile"
                        class="rounded-lg px-4 py-3 text-sm text-slate-700 hover:bg-slate-50"
                    >
                        Profile
                    </Link>

                    <Link
                        href="/settings"
                        class="rounded-lg px-4 py-3 text-sm text-slate-700 hover:bg-slate-50"
                    >
                        Settings
                    </Link>

                    <Link
                        :href="logout()"
                        method="post"
                        as="button"
                        class="flex items-center gap-2 rounded-lg px-4 py-3 text-left text-sm text-red-600 hover:bg-red-50"
                    >
                        <LogOut class="h-4 w-4" />
                        Log out
                    </Link>

                </nav>
            </div>
        </header>

        <!-- ==================== PAGE CONTENT ==================== -->
        <main class="min-h-[calc(100vh-80px)]">
            <slot />
        </main>

    </div>
</template>