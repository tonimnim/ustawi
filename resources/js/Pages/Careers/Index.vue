<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    settings: Object,
    careers: Object,
    flash: Object
});

// Filter state
const selectedType = ref('all');
const selectedLocation = ref('all');

// Application modal state
const showApplicationModal = ref(false);
const selectedJob = ref(null);

// Application form
const form = useForm({
    career_listing_id: null,
    name: '',
    email: '',
    phone: '',
    cover_letter: '',
    cv: null
});

// Get unique locations
const locations = computed(() => {
    const locs = ['all'];
    if (props.careers?.data) {
        const uniqueLocs = [...new Set(props.careers.data.map(job => job.location))];
        locs.push(...uniqueLocs);
    }
    return locs;
});

// Filter careers
const filteredCareers = computed(() => {
    if (!props.careers?.data) return [];
    
    return props.careers.data.filter(job => {
        const typeMatch = selectedType.value === 'all' || job.type === selectedType.value;
        const locationMatch = selectedLocation.value === 'all' || job.location === selectedLocation.value;
        return typeMatch && locationMatch;
    });
});

// Open application modal
const openApplicationModal = (job) => {
    selectedJob.value = job;
    form.career_listing_id = job.id;
    showApplicationModal.value = true;
};

// Close application modal
const closeApplicationModal = () => {
    showApplicationModal.value = false;
    form.reset();
    form.clearErrors();
};

// Handle file upload
const handleFileUpload = (event) => {
    form.cv = event.target.files[0];
};

// Submit application
const submitApplication = () => {
    form.post('/careers/apply', {
        preserveScroll: true,
        onSuccess: () => {
            closeApplicationModal();
        },
    });
};

// Format employment type
const formatEmploymentType = (type) => {
    return type.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};

// Get type badge color
const getTypeBadgeColor = (type) => {
    return type === 'volunteer' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800';
};
</script>

<template>
    <PublicLayout :settings="settings">
        <Head title="Careers - Join Our Team" />
        
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6">Join Our Team</h1>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                        Be part of our mission to empower communities and create lasting positive change
                    </p>
                </div>
            </div>
        </section>
        
        <!-- Success Message -->
        <div v-if="flash?.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
            <p class="font-medium">{{ flash.success }}</p>
        </div>
        
        <!-- Filters Section -->
        <section class="py-8 bg-gray-50 border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap gap-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select 
                            v-model="selectedType"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="all">All Opportunities</option>
                            <option value="job">Jobs</option>
                            <option value="volunteer">Volunteer</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <select 
                            v-model="selectedLocation"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option v-for="location in locations" :key="location" :value="location">
                                {{ location === 'all' ? 'All Locations' : location }}
                            </option>
                        </select>
                    </div>
                    
                    <div class="ml-auto">
                        <p class="text-sm text-gray-600">
                            Showing {{ filteredCareers.length }} opportunities
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Job Listings -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Listings Grid -->
                <div v-if="filteredCareers.length > 0" class="space-y-6">
                    <div 
                        v-for="career in filteredCareers" 
                        :key="career.id"
                        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ career.title }}</h3>
                                    <span 
                                        :class="getTypeBadgeColor(career.type)"
                                        class="px-3 py-1 text-xs font-semibold rounded-full"
                                    >
                                        {{ career.type === 'volunteer' ? 'Volunteer' : 'Job' }}
                                    </span>
                                </div>
                                
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ career.location }}
                                    </div>
                                    <div v-if="career.department" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ career.department }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ formatEmploymentType(career.employment_type) }}
                                    </div>
                                    <div v-if="career.salary_range" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ career.salary_range }}
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ career.description }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div v-if="career.deadline" class="text-sm text-gray-500">
                                        Application deadline: {{ new Date(career.deadline).toLocaleDateString() }}
                                    </div>
                                    <button
                                        @click="openApplicationModal(career)"
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                    >
                                        Apply Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- No Listings -->
                <div v-else class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A8.997 8.997 0 0112 15a8.997 8.997 0 01-9-1.745M11 21h2m-5.343-5.657a8 8 0 1113.657 0A5.98 5.98 0 0117 21H7a5.98 5.98 0 01-2.343-5.657z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No opportunities available</h3>
                    <p class="text-gray-500">Check back soon for new job and volunteer opportunities.</p>
                </div>
            </div>
        </section>
        
        <!-- Application Modal -->
        <div v-if="showApplicationModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" @click="closeApplicationModal">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-2xl font-bold text-gray-900">Apply for {{ selectedJob?.title }}</h3>
                            <p class="mt-2 text-sm text-gray-600">Fill out the form below to submit your application.</p>
                        </div>
                        
                        <form @submit.prevent="submitApplication" class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                    placeholder="+254 700 000 000"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.phone }"
                                />
                                <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                            </div>
                            
                            <!-- Cover Letter -->
                            <div>
                                <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cover Letter
                                </label>
                                <textarea
                                    id="cover_letter"
                                    v-model="form.cover_letter"
                                    rows="4"
                                    placeholder="Tell us why you're interested in this position..."
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.cover_letter }"
                                ></textarea>
                                <p v-if="form.errors.cover_letter" class="mt-1 text-sm text-red-600">{{ form.errors.cover_letter }}</p>
                            </div>
                            
                            <!-- CV Upload -->
                            <div>
                                <label for="cv" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload CV/Resume <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="cv"
                                    type="file"
                                    accept=".pdf,.doc,.docx"
                                    required
                                    @change="handleFileUpload"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.cv }"
                                />
                                <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                                <p v-if="form.errors.cv" class="mt-1 text-sm text-red-600">{{ form.errors.cv }}</p>
                            </div>
                        </form>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            @click="submitApplication"
                            :disabled="form.processing"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Submitting...</span>
                            <span v-else>Submit Application</span>
                        </button>
                        <button
                            @click="closeApplicationModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>