<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    reportable: {
        id: number;
        title: string;
        description: string;
    };
    type: 'question' | 'answer' | 'comment';
    return_url?: string | null;
}>();

const reportForm = useForm({
    reportable_type: props.type,
    reportable_id: props.reportable.id,
    reason: '',
    description: '',
    return_url: props.return_url ?? '',
    general: '',
});

const submitReport = () => {
    reportForm.post('/reports', {
        preserveScroll: true,

        onSuccess: () => {
            reportForm.reset();

            alert('Report submitted successfully.');
        },
    });
};

const contentLabel = () => {
    if (props.type === 'question') return 'Question';
    if (props.type === 'answer') return 'Answer';
    return 'Comment';
};
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-5xl px-6 py-12 lg:px-8">

            <!-- Page Header -->
            <div class="mb-8">
                <div
                    class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600"
                >
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    Community Moderation
                </div>

                <h1
                    class="text-4xl font-bold tracking-tight text-slate-900"
                >
                    Report {{ contentLabel() }}<span class="text-blue-600">.</span>
                </h1>

                <p class="mt-3 text-lg text-slate-500">
                    Please tell us why you are reporting this
                    {{ contentLabel().toLowerCase() }}.
                </p>
            </div>

            <div class="grid gap-8 lg:grid-cols-[1fr_320px]">

                <!-- Main Content -->
                <div class="space-y-6">

                    <!-- Content Preview -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8"
                    >
                        <div class="mb-5 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-slate-900">
                                Reported content
                            </h2>

                            <span
                                class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600"
                            >
                                {{ contentLabel() }}
                            </span>
                        </div>

                        <div
                            class="rounded-xl border border-slate-100 bg-slate-50 p-5"
                        >
                            <h3
                                v-if="reportable.title"
                                class="text-lg font-semibold text-slate-900"
                            >
                                {{ reportable.title }}
                            </h3>

                            <p
                                class="leading-7 text-slate-600"
                                :class="reportable.title ? 'mt-3' : ''"
                            >
                                {{ reportable.description }}
                            </p>
                        </div>
                    </div>

                    <!-- Report Form -->
                    <div
                        class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm md:p-8"
                    >
                        <div
                            class="mb-6 rounded-xl border border-red-100 bg-red-50 p-4"
                        >
                            <div class="flex gap-3">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600"
                                >
                                    !
                                </div>

                                <div>
                                    <h2
                                        class="font-semibold text-red-900"
                                    >
                                        Report this
                                        {{ contentLabel().toLowerCase() }}
                                    </h2>

                                    <p
                                        class="mt-1 text-sm leading-6 text-red-700"
                                    >
                                        Please only submit a report when the
                                        content violates the community
                                        guidelines.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- General Error -->
                        <div
                            v-if="reportForm.errors.general"
                            class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                        >
                            {{ reportForm.errors.general }}
                        </div>

                        <form
                            @submit.prevent="submitReport"
                            class="space-y-6"
                        >
                            <!-- Reason -->
                            <div>
                                <label
                                    for="reason"
                                    class="mb-2 block text-sm font-semibold text-slate-900"
                                >
                                    Reason
                                </label>

                                <select
                                    id="reason"
                                    v-model="reportForm.reason"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all focus:border-red-400 focus:ring-4 focus:ring-red-100"
                                >
                                    <option value="">
                                        Select a reason
                                    </option>

                                    <option value="Spam">
                                        Spam
                                    </option>

                                    <option value="Harassment">
                                        Harassment
                                    </option>

                                    <option value="Inappropriate content">
                                        Inappropriate content
                                    </option>

                                    <option value="Duplicate question">
                                        Duplicate question
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>
                                </select>

                                <p
                                    v-if="reportForm.errors.reason"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ reportForm.errors.reason }}
                                </p>
                            </div>

                            <!-- Additional Details -->
                            <div>
                                <label
                                    for="description"
                                    class="mb-2 block text-sm font-semibold text-slate-900"
                                >
                                    Additional details
                                </label>

                                <p class="mb-3 text-sm text-slate-500">
                                    Provide any additional information that
                                    may help us review the report.
                                </p>

                                <textarea
                                    id="description"
                                    v-model="reportForm.description"
                                    rows="6"
                                    placeholder="Additional details (optional)..."
                                    class="w-full resize-y rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm leading-7 text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-red-400 focus:ring-4 focus:ring-red-100"
                                ></textarea>

                                <p
                                    v-if="reportForm.errors.description"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ reportForm.errors.description }}
                                </p>
                            </div>

                            <!-- Buttons -->
                            <div
                                class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end"
                            >
                                <Link
                                    :href="props.return_url || '/'"
                                    class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Cancel
                                </Link>

                                <button
                                    type="submit"
                                    :disabled="reportForm.processing"
                                    class="rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition-all duration-200 hover:bg-red-700 hover:shadow-xl hover:shadow-red-600/25 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    {{
                                        reportForm.processing
                                            ? 'Submitting...'
                                            : 'Submit Report'
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Information Panel -->
                <div class="space-y-5">

                    <!-- Why report? -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <div
                            class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white shadow-md shadow-blue-600/20"
                        >
                            ?
                        </div>

                        <h3
                            class="text-lg font-semibold text-slate-900"
                        >
                            Why report content?
                        </h3>

                        <p
                            class="mt-3 text-sm leading-6 text-slate-500"
                        >
                            Reports help the CodeHelp community stay useful,
                            respectful, and focused on programming knowledge.
                        </p>

                        <ul
                            class="mt-5 space-y-3 text-sm text-slate-600"
                        >
                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                Spam or misleading content
                            </li>

                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                Harassment or abusive behavior
                            </li>

                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                Inappropriate content
                            </li>

                            <li class="flex gap-3">
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"
                                ></span>
                                Duplicate or irrelevant questions
                            </li>
                        </ul>
                    </div>

                    <!-- Review Notice -->
                    <div
                        class="rounded-2xl border border-amber-200 bg-amber-50 p-6"
                    >
                        <h3
                            class="text-sm font-semibold text-amber-900"
                        >
                            Before submitting
                        </h3>

                        <p
                            class="mt-2 text-sm leading-6 text-amber-800"
                        >
                            Make sure the selected reason accurately describes
                            the problem. Reports may be reviewed by a CodeHelp
                            administrator.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>