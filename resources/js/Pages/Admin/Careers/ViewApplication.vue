<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    application: Object
});

// Status form
const statusForm = useForm({
    status: props.application.status,
    notes: props.application.notes || ''
});

// Update status
const updateStatus = () => {
    statusForm.put(route('admin.careers.applications.update-status', props.application.id));
};

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
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

// Navigation between applications
const navigateApplications = (direction) => {
    // This would require backend support to get next/previous application IDs
    alert('Navigation between applications will be implemented in a future update');
};
</script>

<template>
    <AdminLayout>
        <Head :title="`Application from ${application.name} - Admin`" />
        
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Job Application</h1>
                    <p class="mt-1 text-sm text-gray-600">Review applicant details and manage status</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button
                        @click="navigateApplications('prev')"
                        class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        title="Previous application"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        @click="navigateApplications('next')"
                        class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        title="Next application"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <Link
                        :href="route('admin.careers.index', { tab: 'applications' })"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                    >
                        Back to Applications
                    </Link>
                </div>
            </div>
        </template>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Position Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Position Applied For</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-semibold text-gray-900">{{ application.job_title }}</h4>
                            <span :class="getTypeBadge(application.job_type)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                {{ application.job_type === 'volunteer' ? 'Volunteer' : 'Job' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                            <div v-if="application.department">
                                <span class="font-medium">Department:</span> {{ application.department }}
                            </div>
                            <div>
                                <span class="font-medium">Location:</span> {{ application.location }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Applicant Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Applicant Information</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Full Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ application.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Email Address</label>
                                <a :href="`mailto:${application.email}`" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                    {{ application.email }}
                                </a>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                                <a :href="`tel:${application.phone}`" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                    {{ application.phone }}
                                </a>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Applied On</label>
                                <p class="mt-1 text-sm text-gray-900">{{ formatDate(application.created_at) }}</p>
                            </div>
                        </div>
                        
                        <!-- Cover Letter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Cover Letter / Message</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ application.cover_letter || 'No cover letter provided' }}</p>
                            </div>
                        </div>
                        
                        <!-- CV Download -->
                        <div v-if="application.cv_path">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Curriculum Vitae</label>
                            <a
                                :href="route('admin.careers.applications.download-cv', application.id)"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download CV
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Internal Notes -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Internal Notes</h3>
                    <form @submit.prevent="updateStatus">
                        <div class="space-y-4">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Add notes about this application
                                </label>
                                <textarea
                                    id="notes"
                                    v-model="statusForm.notes"
                                    rows="4"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': statusForm.errors.notes }"
                                    placeholder="Internal notes (not visible to applicant)..."
                                ></textarea>
                                <p v-if="statusForm.errors.notes" class="mt-1 text-sm text-red-600">{{ statusForm.errors.notes }}</p>
                            </div>
                            
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    :disabled="statusForm.processing"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="statusForm.processing">Saving...</span>
                                    <span v-else>Save Notes</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Management -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Application Status</h3>
                    
                    <div class="mb-4">
                        <span :class="getStatusBadge(application.status)" class="px-3 py-1 text-sm font-semibold rounded-full">
                            {{ application.status.charAt(0).toUpperCase() + application.status.slice(1) }}
                        </span>
                    </div>
                    
                    <form @submit.prevent="updateStatus" class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Change Status
                            </label>
                            <select
                                id="status"
                                v-model="statusForm.status"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-500': statusForm.errors.status }"
                            >
                                <option value="pending">Pending</option>
                                <option value="reviewing">Reviewing</option>
                                <option value="shortlisted">Shortlisted</option>
                                <option value="rejected">Rejected</option>
                                <option value="hired">Hired</option>
                            </select>
                            <p v-if="statusForm.errors.status" class="mt-1 text-sm text-red-600">{{ statusForm.errors.status }}</p>
                        </div>
                        
                        <button
                            type="submit"
                            :disabled="statusForm.processing || statusForm.status === application.status"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="statusForm.processing">Updating...</span>
                            <span v-else>Update Status</span>
                        </button>
                    </form>
                </div>
                
                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Application Timeline</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                <p class="text-xs text-gray-500">{{ formatDate(application.created_at) }}</p>
                            </div>
                        </div>
                        
                        <div v-if="application.reviewed_at" class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">First Reviewed</p>
                                <p class="text-xs text-gray-500">{{ formatDate(application.reviewed_at) }}</p>
                            </div>
                        </div>
                        
                        <div v-if="application.updated_at !== application.created_at" class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-xs text-gray-500">{{ formatDate(application.updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h4>
                    <div class="space-y-2">
                        <a
                            :href="`mailto:${application.email}`"
                            class="block w-full px-3 py-2 text-sm text-center text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Send Email
                        </a>
                        <button
                            @click="alert('Print functionality will be implemented in a future update')"
                            class="block w-full px-3 py-2 text-sm text-center text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Print Application
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>