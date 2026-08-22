<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: '',
    description: '',
    tags: [] as string[],
});

const tagInput = ref('');

function submit() {
    form.tags = tagInput.value
        .split(',')
        .map((tag) => tag.trim())
        .filter((tag) => tag !== '');

    form.post('/questions');
}
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-12 lg:px-8">

            <!-- Page Header -->
            <div class="mb-10">
                <div
                    class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600"
                >
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    Developer Community
                </div>

                <h1
                    class="text-4xl font-bold tracking-tight text-slate-900 md:text-5xl"
                >
                    Ask a Question<span class="text-blue-600">.</span>
                </h1>

                <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-500">
                    Ask a programming question and get help from developers
                    in the CodeHelp community.
                </p>
            </div>

            <!-- Main Content -->
            <div class="grid gap-8 lg:grid-cols-[1fr_320px]">

                <!-- Question Form -->
                <div
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8"
                >
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-slate-900">
                            Your question
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Provide enough information so other developers can
                            understand and answer your question.
                        </p>
                    </div>

                    <form
                        @submit.prevent="submit"
                        class="space-y-7"
                    >

                        <!-- Title -->
                        <div>
                            <label
                                for="title"
                                class="mb-2 block text-sm font-semibold text-slate-900"
                            >
                                Title
                            </label>

                            <p class="mb-3 text-sm text-slate-500">
                                Be specific and imagine you are asking another
                                developer.
                            </p>

                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="e.g. How do I handle NullPointerException in Java?"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            />

                            <p
                                v-if="form.errors.title"
                                class="mt-2 text-sm text-red-500"
                            >
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label
                                for="description"
                                class="mb-2 block text-sm font-semibold text-slate-900"
                            >
                                Description
                            </label>

                            <p class="mb-3 text-sm text-slate-500">
                                Explain your problem, what you have tried, and
                                what result you expected.
                            </p>

                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="12"
                                placeholder="Describe your problem in detail..."
                                class="w-full resize-y rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm leading-7 text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            ></textarea>

                            <p
                                v-if="form.errors.description"
                                class="mt-2 text-sm text-red-500"
                            >
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <!-- Tags -->
                        <div>
                            <label
                                for="tags"
                                class="mb-2 block text-sm font-semibold text-slate-900"
                            >
                                Tags
                            </label>

                            <p class="mb-3 text-sm text-slate-500">
                                Add up to a few tags that describe your
                                question.
                            </p>

                            <input
                                id="tags"
                                v-model="tagInput"
                                type="text"
                                placeholder="e.g. Java, Laravel, Vue.js"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            />

                            <p class="mt-2 text-xs text-slate-400">
                                Separate multiple tags with commas.
                            </p>

                            <p
                                v-if="form.errors.tags"
                                class="mt-2 text-sm text-red-500"
                            >
                                {{ form.errors.tags }}
                            </p>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-slate-100"></div>

                        <!-- Submit -->
                        <div class="flex items-center justify-between gap-4">
                            <p class="hidden text-sm text-slate-400 sm:block">
                                Your question will be visible to the community.
                            </p>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-xl bg-blue-600 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition-all duration-200 hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/25 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                {{
                                    form.processing
                                        ? 'Posting...'
                                        : 'Post Question'
                                }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-5">

                    <!-- Tips -->
                    <div
                        class="rounded-2xl border border-blue-100 bg-blue-50 p-6"
                    >
                        <div
                            class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white shadow-md shadow-blue-600/20"
                        >
                            ?
                        </div>

                        <h3 class="text-lg font-semibold text-slate-900">
                            Tips for asking
                        </h3>

                        <ul class="mt-4 space-y-4 text-sm leading-6 text-slate-600">
                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                <span>
                                    Make your question title clear and
                                    specific.
                                </span>
                            </li>

                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                <span>
                                    Explain what you have already tried.
                                </span>
                            </li>

                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                <span>
                                    Include the error message or unexpected
                                    result.
                                </span>
                            </li>

                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                <span>
                                    Add relevant technology tags.
                                </span>
                            </li>
                        </ul>
                    </div>

                    <!-- Example -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <h3 class="text-sm font-semibold text-slate-900">
                            Good question example
                        </h3>

                        <p
                            class="mt-3 text-sm font-medium leading-6 text-slate-800"
                        >
                            How do I handle NullPointerException in Java?
                        </p>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Explain what is happening, show relevant code,
                            and describe the result you expected.
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600"
                            >
                                Java
                            </span>

                            <span
                                class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600"
                            >
                                Exception Handling
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>