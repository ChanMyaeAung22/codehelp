<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
}

interface Reportable {
    id: number;
    title?: string;
    content?: string;
    body?: string;
}

interface Report {
    id: number;
    reason: string;
    description: string | null;
    status: string;
    created_at: string;
    user: User;
    reportable: Reportable;
    reportable_type: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: PaginationLink[];
}

const props = defineProps<{
    reports: Report[];

    filters: {
        status: string | null;
        type: string | null;
        search: string | null;
    };

    pagination: Pagination;
}>();

const selectedReport = ref<Report | null>(null);

const statusFilter = ref(props.filters.status ?? '');
const typeFilter = ref(props.filters.type ?? '');
const searchQuery = ref(props.filters.search ?? '');

const getReportType = (report: Report) => {
    if (report.reportable_type.includes('Question')) {
        return 'Question';
    }

    if (report.reportable_type.includes('Answer')) {
        return 'Answer';
    }

    if (report.reportable_type.includes('Comment')) {
        return 'Comment';
    }

    return 'Unknown';
};

const getReportContent = (report: Report) => {
    if (!report.reportable) {
        return 'Content no longer exists';
    }

    if (report.reportable_type === 'App\\Models\\Comment') {
        return report.reportable.content ?? 'Comment';
    }

    if (report.reportable_type === 'App\\Models\\Question') {
        return report.reportable.title ?? 'Question';
    }

    if (report.reportable_type === 'App\\Models\\Answer') {
        return report.reportable.content ?? 'Answer';
    }

    return 'Unknown';
};

const getAdminContentRoute = (report: Report) => {
    if (!report.reportable) {
        return '#';
    }

    switch (report.reportable_type) {
        case 'App\\Models\\Question':
            return `/admin/questions?highlight=${report.reportable.id}`;

        case 'App\\Models\\Answer':
            return `/admin/answers?highlight=${report.reportable.id}`;

        case 'App\\Models\\Comment':
            return `/admin/comments?highlight=${report.reportable.id}`;

        default:
            return '#';
    }
};

const openReport = (report: Report) => {
    selectedReport.value = report;
};

const closeReport = () => {
    selectedReport.value = null;
};

const applyFilters = () => {
    router.get(
        '/admin/reports',
        {
            status: statusFilter.value || undefined,
            type: typeFilter.value || undefined,
            search: searchQuery.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const resetFilters = () => {
    statusFilter.value = '';
    typeFilter.value = '';
    searchQuery.value = '';

    router.get(
        '/admin/reports',
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const deleteReport = (id: number) => {
    if (confirm('Are you sure you want to delete this report?')) {
        router.delete(`/admin/reports/${id}`);
    }
};

const updateStatus = (
    reportId: number,
    status: 'resolved' | 'dismissed'
) => {
    router.patch(`/admin/reports/${reportId}/status`, {
        status,
    });
};

const getPaginationUrl = (page: number) => {
    const params = new URLSearchParams();

    if (props.filters.status) {
        params.set('status', props.filters.status);
    }

    if (props.filters.type) {
        params.set('type', props.filters.type);
    }

    if (props.filters.search) {
        params.set('search', props.filters.search);
    }

    params.set('page', String(page));

    return `/admin/reports?${params.toString()}`;
};
</script>

<template>
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Manage Reports</h1>

                <p class="mt-2 text-gray-500">
                    View and manage reports submitted by users.
                </p>
            </div>

            <Link
                href="/admin"
                class="rounded-lg border px-4 py-2 hover:bg-gray-50"
            >
                ← Dashboard
            </Link>
        </div>

        <!-- Filters -->
<div class="mb-6 rounded-xl border bg-white p-5">
    <div class="grid gap-4 md:grid-cols-4">

        <!-- Status -->
        <div>
            <label
                for="status"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Status
            </label>

            <select
                id="status"
                v-model="statusFilter"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 focus:border-blue-500 focus:outline-none"
            >
                <option value="">
                    All Status
                </option>

                <option value="pending">
                    Pending
                </option>

                <option value="resolved">
                    Resolved
                </option>

                <option value="dismissed">
                    Dismissed
                </option>
            </select>
        </div>

        <!-- Type -->
        <div>
            <label
                for="type"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Type
            </label>

            <select
                id="type"
                v-model="typeFilter"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 focus:border-blue-500 focus:outline-none"
            >
                <option value="">
                    All Types
                </option>

                <option value="question">
                    Question
                </option>

                <option value="answer">
                    Answer
                </option>

                <option value="comment">
                    Comment
                </option>
            </select>
        </div>

        <!-- Search -->
        <div>
            <label
                for="search"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Search
            </label>

            <input
                id="search"
                v-model="searchQuery"
                type="text"
                placeholder="Search reports..."
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
                @keyup.enter="applyFilters"
            />
        </div>

        <!-- Buttons -->
        <div class="flex items-end gap-2">
            <button
                type="button"
                @click="applyFilters"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Search
            </button>

            <button
                type="button"
                @click="resetFilters"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Reset
            </button>
        </div>

    </div>
</div>

        <!-- Reports Table -->
        <div class="overflow-hidden rounded-xl border bg-white">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Reporter</th>
                        <th class="px-6 py-4 text-left">Type</th>
                        <th class="px-6 py-4 text-left">Reported</th>
                        <th class="px-6 py-4 text-left">Reason</th>
                        <th class="px-6 py-4 text-left">Description</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Created</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="report in reports"
                        :key="report.id"
                        class="border-t"
                    >
                        <!-- Reporter -->
                        <td class="px-6 py-4">
                            {{ report.user.name }}
                        </td>

                        <!-- Type -->
                        <td class="px-6 py-4">
                            {{ getReportType(report) }}
                        </td>

                        <!-- Reported Content -->
                        <td class="max-w-xs px-6 py-4">
                            <Link
                                :href="getAdminContentRoute(report)"
                                class="text-blue-600 hover:underline">
                                    {{ getReportContent(report) }}
                            </Link>
                        </td>

                        <!-- Reason -->
                        <td class="px-6 py-4">
                            {{ report.reason }}
                        </td>

                        <!-- Description -->
                        <td class="max-w-xs px-6 py-4">
                            {{ report.description || '-' }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span
                                class="rounded-full px-3 py-1 text-sm font-medium"
                                :class="{
                                'bg-yellow-100 text-yellow-700':
                                report.status === 'pending',

                                'bg-green-100 text-green-700':
                                report.status === 'resolved',

                                'bg-gray-100 text-gray-700':
                                report.status === 'dismissed',
                            }">
                                {{ report.status }}
                            </span>
                        </td>

                        <!-- Created -->
                        <td class="px-6 py-4 text-gray-500">
                            {{
                                new Date(
                                    report.created_at,
                                ).toLocaleDateString()
                            }}
                        </td>

                        <!-- Action -->
                       <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                    <!-- View -->
                                <button
                                    type="button"
                                        @click="openReport(report)"
                                        class="text-blue-600 hover:underline">
                                            View
                                </button>

                    <!-- Resolve -->
                                <button
                                    v-if="report.status === 'pending'"
                                    type="button"
                                    @click="updateStatus(report.id, 'resolved')"
                                    class="text-green-600 hover:underline">
                                        Resolve
                                </button>

                    <!-- Dismiss -->
                                <button
                                    v-if="report.status === 'pending'"
                                    type="button"
                                    @click="updateStatus(report.id, 'dismissed')"
                                    class="text-yellow-600 hover:underline">
                                        Dismiss
                                </button>

                    <!-- Delete -->
                                <button
                                    v-if="report.status !== 'pending'"
                                    type="button"
                                    @click="deleteReport(report.id)"
                                    class="text-red-600 hover:underline">
                                        Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="reports.length === 0">
                        <td
                            colspan="8"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            No reports found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
<div
    v-if="pagination.last_page > 1"
    class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
>
    <!-- Showing -->
    <div class="text-sm text-gray-500">
        Showing
        <span class="font-medium text-gray-700">
            {{ pagination.from }}
        </span>
        to
        <span class="font-medium text-gray-700">
            {{ pagination.to }}
        </span>
        of
        <span class="font-medium text-gray-700">
            {{ pagination.total }}
        </span>
        reports
    </div>

    <!-- Pagination -->
    <div class="flex items-center gap-1">

        <!-- Previous -->
        <Link
            v-if="pagination.current_page > 1"
            :href="getPaginationUrl(pagination.current_page - 1)"
            preserve-scroll
            preserve-state
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50"
        >
            Previous
        </Link>

        <!-- Page Numbers -->
        <Link
            v-for="page in pagination.last_page"
            :key="page"
            :href="getPaginationUrl(page)"
            preserve-scroll
            preserve-state
            :class="[
                'min-w-9 rounded-lg border px-3 py-2 text-center text-sm transition',
                page === pagination.current_page
                    ? 'border-blue-600 bg-blue-600 text-white'
                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
            ]"
        >
            {{ page }}
        </Link>

        <!-- Next -->
        <Link
            v-if="pagination.current_page < pagination.last_page"
            :href="getPaginationUrl(pagination.current_page + 1)"
            preserve-scroll
            preserve-state
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50"
        >
            Next
        </Link>

    </div>
</div>

    <!-- Report Details Modal -->
<div
    v-if="selectedReport"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
    @click.self="closeReport"
>
    <div
        class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                Report Details
            </h2>

            <button
                type="button"
                @click="closeReport"
                class="text-2xl text-gray-400 hover:text-gray-700"
            >
                ×
            </button>
        </div>

        <!-- Report Information -->
        <div class="mt-6 grid grid-cols-2 gap-4">

            <div>
                <p class="text-sm text-gray-500">
                    Reporter
                </p>

                <p class="mt-1 font-medium text-gray-800">
                    {{ selectedReport.user.name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Type
                </p>

                <p class="mt-1 font-medium text-gray-800">
                    {{ getReportType(selectedReport) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Reason
                </p>

                <p class="mt-1 font-medium text-gray-800">
                    {{ selectedReport.reason }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Status
                </p>

                <p class="mt-1 font-medium text-gray-800">
                    {{ selectedReport.status }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Created
                </p>

                <p class="mt-1 font-medium text-gray-800">
                    {{
                        new Date(
                            selectedReport.created_at,
                        ).toLocaleDateString()
                    }}
                </p>
            </div>
        </div>

        <!-- Reported Content -->
        <div class="mt-6">
            <p class="text-sm font-medium text-gray-500">
                Reported Content
            </p>

            <Link
                :href="getAdminContentRoute(selectedReport)"
                class="mt-2 inline-block text-blue-600 hover:underline">
                    {{ getReportContent(selectedReport) }}
            </Link>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <p class="text-sm font-medium text-gray-500">
                Reporter's Description
            </p>

            <div
                class="mt-2 rounded-xl bg-gray-50 p-4 text-gray-800"
            >
                {{ selectedReport.description || 'No additional details provided.' }}
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end gap-3">

            <button
                v-if="selectedReport.status === 'pending'"
                type="button"
                @click="updateStatus(selectedReport.id, 'resolved'); closeReport()"
                class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700"
            >
                Resolve
            </button>

            <button
                v-if="selectedReport.status === 'pending'"
                type="button"
                @click="updateStatus(selectedReport.id, 'dismissed'); closeReport()"
                class="rounded-lg bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600"
            >
                Dismiss
            </button>

            <button
                type="button"
                @click="closeReport"
                class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50"
            >
                Close
            </button>

        </div>
    </div>
</div>
</template>

