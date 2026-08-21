<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';

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
});

const submitReport = () => {
    console.log('Submitting report:', reportForm.data());

    reportForm.post('/reports', {
        preserveScroll: true,

        onSuccess: () => {
            console.log('Report submitted successfully');

            reportForm.reset();

            alert('Report submitted successfully.');
        },

        onError: (errors) => {
            console.log('Report errors:', errors);
        },

        onFinish: () => {
            console.log('Report request finished');
        },
    });
};
</script>

<template>
    <div class="mx-auto max-w-3xl px-4 py-10">

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Report {{ type === 'question'? 'Question': type === 'answer'? 'Answer': 'Comment'}}
            </h1>

            <p class="mt-2 text-gray-500">
                Please tell us why you are reporting this {{ type }}.
            </p>
        </div>

        <!-- Question Preview -->
        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ reportable.title }}
            </h2>

            <p class="mt-3 leading-7 text-gray-600">
                {{ reportable.description }}
            </p>
        </div>

        <!-- Report Form -->
        <div class="rounded-2xl border border-red-200 bg-red-50 p-6">

            <h2 class="text-lg font-semibold text-gray-800">
                Report this {{ type }}
            </h2>
   
<!-- General Report Error -->
<div
    v-if="reportForm.errors.report"
    class="mt-4 rounded-xl border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700"
>
    {{ reportForm.errors.report }}
</div>

            <!-- Reason -->
            <div class="mt-5">
                <label
                    for="reason"
                    class="block text-sm font-medium text-gray-700"
                >
                    Reason
                </label>

                <select
                    id="reason"
                    v-model="reportForm.reason"
                    class="mt-2 w-full rounded-xl border border-gray-300 bg-white p-3 focus:border-red-400 focus:outline-none"
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

                <div
                    v-if="reportForm.errors.reason"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ reportForm.errors.reason }}
                </div>
            </div>

            <!-- Description -->
            <div class="mt-5">
                <label
                    for="description"
                    class="block text-sm font-medium text-gray-700"
                >
                    Additional details
                </label>

                <textarea
                    id="description"
                    v-model="reportForm.description"
                    rows="5"
                    placeholder="Additional details (optional)..."
                    class="mt-2 w-full rounded-xl border border-gray-300 bg-white p-3 focus:border-red-400 focus:outline-none"
                ></textarea>

                <div
                    v-if="reportForm.errors.description"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ reportForm.errors.description }}
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex gap-3">

                <button
                    type="button"
                    @click="submitReport"
                    :disabled="reportForm.processing"
                    class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        reportForm.processing
                            ? 'Submitting...'
                            : 'Submit Report'
                    }}
                </button>

                <Link
                    :href="props.return_url || '/'"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-white">
                        Cancel
                </Link>

            </div>
        </div>
    </div>
</template>