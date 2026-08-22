<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
</script>

<template>
    <Head title="Code Help" />

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <!-- ==================== NAVBAR ==================== -->
        <header
            class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur"
        >
            <div
                class="mx-auto flex h-18 max-w-7xl items-center justify-between px-6 lg:px-8"
            >
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20"
                    >
                        <!-- Code Icon -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 9l-3 3 3 3m8-6l3 3-3 3M14 5l-4 14"
                            />
                        </svg>
                    </div>

                    <div>
                        <div class="text-lg font-bold tracking-tight">
                            Code<span class="text-blue-600">Help</span>
                        </div>
                        <div class="hidden text-[10px] font-medium uppercase tracking-widest text-slate-400 sm:block">
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
                        href="/questions/tag/java"
                        class="text-sm font-medium text-slate-600 transition hover:text-blue-600"
                    >
                        Tags
                    </Link>

                    <a
                        href="#how-it-works"
                        class="text-sm font-medium text-slate-600 transition hover:text-blue-600"
                    >
                        How it works
                    </a>
                </nav>

                <!-- Auth Navigation -->
                <div class="flex items-center gap-3">
                    <template v-if="$page.props.auth?.user">
                        <Link
                            href="/questions"
                            class="hidden text-sm font-medium text-slate-600 hover:text-blue-600 sm:inline-block"
                        >
                            Browse
                        </Link>

                        <Link
                            href="/questions"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            Dashboard
                        </Link>

                        <Link
                            v-if="$page.props.auth.user.is_admin"
                            href="/admin"
                            class="hidden rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 sm:inline-block"
                        >
                            Admin
                        </Link>
                    </template>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="px-3 py-2 text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                        >
                            Log in
                        </Link>

                        <Link
                            :href="register()"
                            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- ==================== HERO ==================== -->
        <main>
            <section class="relative overflow-hidden">
                <!-- Background decoration -->
                <div
                    class="absolute -left-40 -top-40 h-96 w-96 rounded-full bg-blue-100/60 blur-3xl"
                ></div>

                <div
                    class="absolute -right-40 top-20 h-96 w-96 rounded-full bg-indigo-100/60 blur-3xl"
                ></div>

                <div
                    class="relative mx-auto grid max-w-7xl items-center gap-16 px-6 pb-24 pt-20 lg:grid-cols-2 lg:px-8 lg:pb-32 lg:pt-28"
                >
                    <!-- Hero Text -->
                    <div>
                        <div
                            class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-blue-600"
                            ></span>

                            A community built for developers
                        </div>

                        <h1
                            class="max-w-3xl text-5xl font-extrabold leading-[1.05] tracking-tight text-slate-900 sm:text-6xl lg:text-7xl"
                        >
                            Learn.
                            <span class="text-blue-600">Ask.</span>
                            <br />
                            Build.
                        </h1>

                        <p
                            class="mt-7 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl"
                        >
                            Code Help is a developer community where you can
                            ask programming questions, share your knowledge,
                            and find solutions to real coding problems.
                        </p>

                        <!-- Buttons -->
                        <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                            <Link
                                href="/questions"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700"
                            >
                                Browse Questions

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                                    />
                                </svg>
                            </Link>

                            <Link
                                v-if="$page.props.auth?.user"
                                href="/questions/create"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:bg-blue-50"
                            >
                                Ask a Question
                            </Link>

                            <Link
                                v-else
                                :href="register()"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:bg-blue-50"
                            >
                                Join the Community
                            </Link>
                        </div>

                        <!-- Small trust text -->
                        <div class="mt-8 flex items-center gap-3 text-sm text-slate-500">
                            <div class="flex -space-x-2">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-blue-100 text-xs font-bold text-blue-700"
                                >
                                    J
                                </div>

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-purple-100 text-xs font-bold text-purple-700"
                                >
                                    P
                                </div>

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-emerald-100 text-xs font-bold text-emerald-700"
                                >
                                    M
                                </div>
                            </div>

                            <span>
                                Learn from developers and share what you know.
                            </span>
                        </div>
                    </div>

                    <!-- Code Card -->
                    <div class="relative">
                        <div
                            class="absolute -inset-4 rounded-3xl bg-blue-600/10 blur-2xl"
                        ></div>

                        <div
                            class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/10"
                        >
                            <!-- Window Header -->
                            <div
                                class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4"
                            >
                                <div class="flex gap-2">
                                    <span
                                        class="h-3 w-3 rounded-full bg-red-400"
                                    ></span>

                                    <span
                                        class="h-3 w-3 rounded-full bg-yellow-400"
                                    ></span>

                                    <span
                                        class="h-3 w-3 rounded-full bg-green-400"
                                    ></span>
                                </div>

                                <span
                                    class="text-xs font-medium text-slate-400"
                                >
                                    solution.java
                                </span>

                                <div class="w-10"></div>
                            </div>

                            <!-- Code -->
                            <div class="bg-slate-950 p-7 font-mono text-sm leading-7">
                                <div>
                                    <span class="text-purple-400">public</span>
                                    <span class="text-blue-400"> class </span>
                                    <span class="text-yellow-300">
                                        Solution
                                    </span>
                                    <span class="text-slate-300"> {</span>
                                </div>

                                <div class="pl-6">
                                    <span class="text-purple-400">public</span>
                                    <span class="text-blue-400">
                                        static
                                    </span>
                                    <span class="text-blue-400"> void </span>
                                    <span class="text-yellow-300">
                                        main
                                    </span>
                                    <span class="text-slate-300">
                                        (String[] args) {
                                    </span>
                                </div>

                                <div class="pl-12">
                                    <span class="text-slate-400">
                                        // Find the solution
                                    </span>
                                </div>

                                <div class="pl-12">
                                    <span class="text-blue-400">System</span>
                                    <span class="text-slate-300">.</span>
                                    <span class="text-yellow-300">
                                        out
                                    </span>
                                    <span class="text-slate-300">.</span>
                                    <span class="text-yellow-300">
                                        println
                                    </span>
                                    <span class="text-slate-300">(</span>
                                </div>

                                <div class="pl-16">
                                    <span class="text-green-400">
                                        "Build. Learn. Share."
                                    </span>
                                </div>

                                <div class="pl-12">
                                    <span class="text-slate-300">);</span>
                                </div>

                                <div class="pl-6">
                                    <span class="text-slate-300">}</span>
                                </div>

                                <div>
                                    <span class="text-slate-300">}</span>
                                </div>
                            </div>

                            <!-- Bottom question -->
                            <div class="border-t border-slate-200 bg-white p-5">
                                <div class="text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Community Question
                                </div>

                                <div class="mt-2 text-sm font-semibold text-slate-800">
                                    How should I handle exceptions in Java?
                                </div>

                                <div class="mt-3 flex gap-2">
                                    <span
                                        class="rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700"
                                    >
                                        Java
                                    </span>

                                    <span
                                        class="rounded-md bg-purple-50 px-2.5 py-1 text-xs font-medium text-purple-700"
                                    >
                                        Exception Handling
                                    </span>

                                    <span
                                        class="rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700"
                                    >
                                        Debugging
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== STATS ==================== -->
            <section class="border-y border-slate-200 bg-white">
                <div
                    class="mx-auto grid max-w-7xl grid-cols-2 px-6 py-10 sm:grid-cols-4 lg:px-8"
                >
                    <div class="border-r border-slate-200 text-center">
                        <div class="text-3xl font-bold text-slate-900">
                            100+
                        </div>
                        <div class="mt-1 text-sm text-slate-500">
                            Questions
                        </div>
                    </div>

                    <div
                        class="border-r-0 text-center sm:border-r sm:border-slate-200"
                    >
                        <div class="text-3xl font-bold text-slate-900">
                            250+
                        </div>
                        <div class="mt-1 text-sm text-slate-500">
                            Answers
                        </div>
                    </div>

                    <div
                        class="mt-8 border-r border-slate-200 text-center sm:mt-0"
                    >
                        <div class="text-3xl font-bold text-slate-900">
                            50+
                        </div>
                        <div class="mt-1 text-sm text-slate-500">
                            Developers
                        </div>
                    </div>

                    <div class="mt-8 text-center sm:mt-0">
                        <div class="text-3xl font-bold text-slate-900">
                            20+
                        </div>
                        <div class="mt-1 text-sm text-slate-500">
                            Topics
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== POPULAR TOPICS ==================== -->
            <section class="bg-slate-50 py-24">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center">
                        <p
                            class="text-sm font-semibold uppercase tracking-wider text-blue-600"
                        >
                            Explore
                        </p>

                        <h2
                            class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                        >
                            Explore popular topics
                        </h2>

                        <p
                            class="mx-auto mt-4 max-w-2xl text-slate-600"
                        >
                            Find questions and discussions around the
                            technologies you are learning and using.
                        </p>
                    </div>

                    <div
                        class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <Link
                            href="/questions/tag/java"
                            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                        >
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600"
                            >
                                ☕
                            </div>

                            <h3
                                class="mt-5 font-bold text-slate-900 group-hover:text-blue-600"
                            >
                                Java
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Learn Java, OOP, Spring Boot and more.
                            </p>
                        </Link>

                        <div
                            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                        >
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                            >
                                &lt;/&gt;
                            </div>

                            <h3
                                class="mt-5 font-bold text-slate-900 group-hover:text-blue-600"
                            >
                                Web Development
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Frontend, backend, APIs and modern web apps.
                            </p>
                        </div>

                        <div
                            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                        >
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-600"
                            >
                                ◈
                            </div>

                            <h3
                                class="mt-5 font-bold text-slate-900 group-hover:text-blue-600"
                            >
                                Laravel
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                PHP, Laravel, databases and application
                                development.
                            </p>
                        </div>

                        <div
                            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                        >
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600"
                            >
                                📱
                            </div>

                            <h3
                                class="mt-5 font-bold text-slate-900 group-hover:text-blue-600"
                            >
                                Mobile Development
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Android, Kotlin, Flutter and mobile apps.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== HOW IT WORKS ==================== -->
            <section
                id="how-it-works"
                class="bg-white py-24"
            >
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center">
                        <p
                            class="text-sm font-semibold uppercase tracking-wider text-blue-600"
                        >
                            Simple process
                        </p>

                        <h2
                            class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                        >
                            How Code Help works
                        </h2>
                    </div>

                    <div
                        class="mt-16 grid gap-10 md:grid-cols-3"
                    >
                        <!-- Step 1 -->
                        <div class="text-center">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8 10h8M8 14h5m-8 5l-2 2V5a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2H8z"
                                    />
                                </svg>
                            </div>

                            <h3 class="mt-6 text-lg font-bold text-slate-900">
                                Ask a question
                            </h3>

                            <p
                                class="mx-auto mt-3 max-w-sm text-sm leading-6 text-slate-500"
                            >
                                Describe your programming problem and get help
                                from the community.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="text-center">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-50 text-purple-600"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 3v-3H6a2 2 0 01-2-2v-2m3-8V5a2 2 0 012-2h7a2 2 0 012 2v7a2 2 0 01-2 2h-3l-4 3v-3H9a2 2 0 01-2-2V7z"
                                    />
                                </svg>
                            </div>

                            <h3 class="mt-6 text-lg font-bold text-slate-900">
                                Share knowledge
                            </h3>

                            <p
                                class="mx-auto mt-3 max-w-sm text-sm leading-6 text-slate-500"
                            >
                                Help other developers by sharing your
                                experience and solutions.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 3c-2.954 0-5.688 1.07-7.618 2.984A11.955 11.955 0 004 12c0 2.954 1.07 5.688 2.984 7.618A11.955 11.955 0 0012 21c2.954 0 5.688-1.07 7.618-2.984A11.955 11.955 0 0020 12c0-1.657-.337-3.235-.946-4.656z"
                                    />
                                </svg>
                            </div>

                            <h3 class="mt-6 text-lg font-bold text-slate-900">
                                Find the solution
                            </h3>

                            <p
                                class="mx-auto mt-3 max-w-sm text-sm leading-6 text-slate-500"
                            >
                                Discover useful answers and accepted solutions
                                to solve your problem.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== CTA ==================== -->
            <section class="bg-slate-50 py-20">
                <div class="mx-auto max-w-5xl px-6 lg:px-8">
                    <div
                        class="overflow-hidden rounded-3xl bg-blue-600 px-8 py-14 text-center shadow-xl shadow-blue-600/20 sm:px-16"
                    >
                        <h2
                            class="text-3xl font-bold tracking-tight text-white sm:text-4xl"
                        >
                            Ready to solve your next coding problem?
                        </h2>

                        <p
                            class="mx-auto mt-5 max-w-2xl text-blue-100"
                        >
                            Join Code Help and become part of a community
                            where developers learn from one another.
                        </p>

                        <div class="mt-8">
                            <Link
                                v-if="!$page.props.auth?.user"
                                :href="register()"
                                class="inline-flex items-center rounded-xl bg-white px-6 py-3.5 text-sm font-semibold text-blue-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-50"
                            >
                                Join Code Help
                            </Link>

                            <Link
                                v-else
                                href="/questions/create"
                                class="inline-flex items-center rounded-xl bg-white px-6 py-3.5 text-sm font-semibold text-blue-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-50"
                            >
                                Ask a Question
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- ==================== FOOTER ==================== -->
        <footer class="border-t border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-8 sm:flex-row sm:items-center sm:justify-between lg:px-8"
            >
                <div class="flex items-center gap-2">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 9l-3 3 3 3m8-6l3 3-3 3M14 5l-4 14"
                            />
                        </svg>
                    </div>

                    <span class="font-semibold text-slate-800">
                        Code<span class="text-blue-600">Help</span>
                    </span>
                </div>

                <p class="text-sm text-slate-500">
                    Built for developers. Built for learning.
                </p>

                <div class="flex gap-5 text-sm text-slate-500">
                    <Link
                        href="/questions"
                        class="transition hover:text-blue-600"
                    >
                        Questions
                    </Link>

                    <Link
                        v-if="!$page.props.auth?.user"
                        :href="login()"
                        class="transition hover:text-blue-600"
                    >
                        Log in
                    </Link>
                </div>
            </div>
        </footer>
    </div>
</template>