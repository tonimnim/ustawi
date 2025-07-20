<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    listings: Object,
    applications: Object,
    stats: Object,
    activeTab: String
});

// Tab management
const currentTab = ref(props.activeTab || 'listings');

const switchTab = (tab) => {
    currentTab.value = tab;
    router.get(route('admin.careers.index'), { tab }, { preserveState: true });
};

// Delete listing
const deleteListing = (id) => {
    if (confirm('Are you sure you want to delete this listing?')) {
        router.delete(route('admin.careers.destroy', id));
    }
};

// Toggle active status
const toggleActive = (id) => {
    router.put(route('admin.careers.toggle-active', id));
};

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString();
};

// Get status badge color
const getStatusBadge = (status) => {
    const badges = {
        pending: 'bg-yellow-100 text-yellow-800',
        reviewing: 'bg-blue-100 text-blue-800',
        shortlisted: 'bg-purple-100 text-purple-800',
        rejected: 'bg-red-100 text-red-800',
        hired: 'bg-green-100 text-green-800'
    };
    return badges[status] || 'bg-gray-100 text-gray-800';
};

// Get type badge
const getTypeBadge = (type) => {
    return type === 'volunteer' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800';
};
</script>

<template>
    <AdminLayout>
        <Head title="Careers Management - Admin" />
        
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Careers Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage job listings and applications</p>
                </div>
                <Link
                    :href="route('admin.careers.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Listing
                </Link>
            </div>
        </template>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A8.997 8.997 0 0112 15a8.997 8.997 0 01-9-1.745M11 21h2m-1-5.657V21m0-5.657a3 3 0 01-3-3 3 3 0 013-3 3 3 0 013 3 3 3 0 01-3 3z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Total Listings</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.total_listings }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Active Listings</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.active_listings }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Total Applications</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.total_applications }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Pending Review</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.pending_applications }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button
                        @click="switchTab('listings')"
                        :class="[
                            currentTab === 'listings'
                                ? 'border-blue-500 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            'whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors'
                        ]"
                    >
                        Job Listings
                    </button>
                    <button
                        @click="switchTab('applications')"
                        :class="[
                            currentTab === 'applications'
                                ? 'border-blue-500 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            'whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors'
                        ]"
                    >
                        Applications
                    </button>
                </nav>
            </div>
            
            <!-- Listings Tab -->
            <div v-if="currentTab === 'listings'" class="p-6">
                <div v-if="listings.data.length > 0" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Position
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deadline
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="listing in listings.data" :key="listing.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ listing.title }}</div>
                                        <div v-if="listing.department" class="text-sm text-gray-500">{{ listing.department }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getTypeBadge(listing.type)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ listing.type === 'volunteer' ? 'Volunteer' : 'Job' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ listing.location }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button
                                        @click="toggleActive(listing.id)"
                                        :class="listing.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                        class="px-2 py-1 text-xs font-semibold rounded-full cursor-pointer hover:opacity-80"
                                    >
                                        {{ listing.is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatDate(listing.deadline) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatDate(listing.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link
                                        :href="route('admin.careers.edit', listing.id)"
                                        class="text-blue-600 hover:text-blue-900 mr-3"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteListing(listing.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No job listings</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new job listing.</p>
                    <div class="mt-6">
                        <Link
                            :href="route('admin.careers.create')"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Listing
                        </Link>
                    </div>
                </div>
            </div>
            
            <!-- Applications Tab -->
            <div v-if="currentTab === 'applications'" class="p-6">
                <div v-if="applications.data.length > 0" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Applicant
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Position
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Applied
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="application in applications.data" :key="application.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ application.name }}</div>
                                        <div class="text-sm text-gray-500">{{ application.email }}</div>
                                        <div class="text-sm text-gray-500">{{ application.phone }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ application.job_title }}</div>
                                        <span :class="getTypeBadge(application.job_type)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ application.job_type === 'volunteer' ? 'Volunteer' : 'Job' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusBadge(application.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ application.status.charAt(0).toUpperCase() + application.status.slice(1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatDate(application.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link
                                        :href="route('admin.careers.applications.view', application.id)"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        View Details
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No applications yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Applications will appear here when candidates apply.</p>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div v-if="(currentTab === 'listings' && listings.links.length > 3) || (currentTab === 'applications' && applications.links.length > 3)" class="mt-6">
            <nav class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <Link
                        v-for="link in (currentTab === 'listings' ? listings.links : applications.links)"
                        v-if="link.label === 'Previous' || link.label === 'Next'"
                        :key="link.label"
                        :href="link.url"
                        :class="[
                            link.url ? 'bg-white hover:bg-gray-50' : 'bg-gray-100 cursor-not-allowed',
                            'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700'
                        ]"
                        :disabled="!link.url"
                    >
                        {{ link.label }}
                    </Link>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ currentTab === 'listings' ? listings.from : applications.from }}</span>
                            to
                            <span class="font-medium">{{ currentTab === 'listings' ? listings.to : applications.to }}</span>
                            of
                            <span class="font-medium">{{ currentTab === 'listings' ? listings.total : applications.total }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <Link
                                v-for="link in (currentTab === 'listings' ? listings.links : applications.links)"
                                :key="link.label"
                                :href="link.url"
                                :class="[
                                    link.active
                                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                        : link.url
                                            ? 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                            : 'bg-gray-100 border-gray-300 text-gray-400 cursor-not-allowed',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                ]"
                                :disabled="!link.url"
                                v-html="link.label"
                            />
                        </nav>
                    </div>
                </div>
            </nav>
        </div>
    </AdminLayout>
</template>