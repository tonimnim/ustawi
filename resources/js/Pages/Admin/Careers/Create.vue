<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    title: '',
    type: 'job',
    department: '',
    location: '',
    employment_type: 'full-time',
    description: '',
    requirements: '',
    responsibilities: '',
    salary_range: '',
    deadline: '',
    is_active: true,
});

// Convert textarea line breaks to list items
const convertToList = (text) => {
    if (!text) return '';
    const lines = text.split('\n').filter(line => line.trim());
    return '<ul class="list-disc list-inside">' + 
           lines.map(line => `<li>${line.trim()}</li>`).join('') + 
           '</ul>';
};

// Submit form
const submit = () => {
    // Convert requirements and responsibilities to HTML lists
    const data = {
        ...form.data(),
        requirements: convertToList(form.requirements),
        responsibilities: convertToList(form.responsibilities),
    };
    
    form.transform(() => data).post(route('admin.careers.store'), {
        onError: (errors) => {
            // Handle 419 CSRF token error
            if (errors.message && errors.message.includes('419')) {
                alert('Your session has expired. The page will refresh to continue.');
                window.location.reload();
            }
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Create Career Listing - Admin" />
        
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create Career Listing</h1>
                    <p class="mt-1 text-sm text-gray-600">Add a new job or volunteer opportunity</p>
                </div>
                <Link
                    :href="route('admin.careers.index')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Cancel
                </Link>
            </div>
        </template>
        
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Position Title <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.title }"
                                    placeholder="e.g., Program Manager, Youth Mentor"
                                />
                                <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                            </div>
                            
                            <!-- Type and Employment Type -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="type"
                                        v-model="form.type"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.type }"
                                    >
                                        <option value="job">Job</option>
                                        <option value="volunteer">Volunteer</option>
                                    </select>
                                    <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
                                </div>
                                
                                <div>
                                    <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Employment Type <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="employment_type"
                                        v-model="form.employment_type"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.employment_type }"
                                    >
                                        <option value="full-time">Full Time</option>
                                        <option value="part-time">Part Time</option>
                                        <option value="contract">Contract</option>
                                        <option value="volunteer">Volunteer</option>
                                    </select>
                                    <p v-if="form.errors.employment_type" class="mt-1 text-sm text-red-600">{{ form.errors.employment_type }}</p>
                                </div>
                            </div>
                            
                            <!-- Department and Location -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                        Department
                                    </label>
                                    <input
                                        id="department"
                                        v-model="form.department"
                                        type="text"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.department }"
                                        placeholder="e.g., Programs, Administration"
                                    />
                                    <p v-if="form.errors.department" class="mt-1 text-sm text-red-600">{{ form.errors.department }}</p>
                                </div>
                                
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                        Location <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="location"
                                        v-model="form.location"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.location }"
                                        placeholder="e.g., Nairobi"
                                    />
                                    <p v-if="form.errors.location" class="mt-1 text-sm text-red-600">{{ form.errors.location }}</p>
                                </div>
                            </div>
                            
                            <!-- Salary Range -->
                            <div v-if="form.type === 'job'">
                                <label for="salary_range" class="block text-sm font-medium text-gray-700 mb-2">
                                    Salary Range
                                </label>
                                <input
                                    id="salary_range"
                                    v-model="form.salary_range"
                                    type="text"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.salary_range }"
                                    placeholder="e.g., KES 50,000 - 80,000 per month"
                                />
                                <p v-if="form.errors.salary_range" class="mt-1 text-sm text-red-600">{{ form.errors.salary_range }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Job Details -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Job Details</h3>
                        
                        <div class="space-y-4">
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="4"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.description }"
                                    placeholder="Provide a detailed description of the role..."
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>
                            
                            <!-- Requirements -->
                            <div>
                                <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                                    Requirements <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="requirements"
                                    v-model="form.requirements"
                                    rows="6"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.requirements }"
                                    placeholder="Enter each requirement on a new line:&#10;Bachelor's degree in relevant field&#10;3+ years of experience&#10;Strong communication skills"
                                ></textarea>
                                <p class="mt-1 text-xs text-gray-500">Enter each requirement on a new line</p>
                                <p v-if="form.errors.requirements" class="mt-1 text-sm text-red-600">{{ form.errors.requirements }}</p>
                            </div>
                            
                            <!-- Responsibilities -->
                            <div>
                                <label for="responsibilities" class="block text-sm font-medium text-gray-700 mb-2">
                                    Responsibilities <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="responsibilities"
                                    v-model="form.responsibilities"
                                    rows="6"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.responsibilities }"
                                    placeholder="Enter each responsibility on a new line:&#10;Develop and implement program strategies&#10;Manage team of volunteers&#10;Report to senior management"
                                ></textarea>
                                <p class="mt-1 text-xs text-gray-500">Enter each responsibility on a new line</p>
                                <p v-if="form.errors.responsibilities" class="mt-1 text-sm text-red-600">{{ form.errors.responsibilities }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Publishing Options -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing Options</h3>
                        
                        <div class="space-y-4">
                            <!-- Status -->
                            <div>
                                <label class="flex items-center">
                                    <input
                                        v-model="form.is_active"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <span class="ml-2 text-sm text-gray-700">Active (visible to applicants)</span>
                                </label>
                            </div>
                            
                            <!-- Application Deadline -->
                            <div>
                                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                                    Application Deadline
                                </label>
                                <input
                                    id="deadline"
                                    v-model="form.deadline"
                                    type="date"
                                    :min="new Date().toISOString().split('T')[0]"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.deadline }"
                                />
                                <p class="mt-1 text-xs text-gray-500">Leave empty for no deadline</p>
                                <p v-if="form.errors.deadline" class="mt-1 text-sm text-red-600">{{ form.errors.deadline }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Creating...</span>
                            <span v-else>Create Listing</span>
                        </button>
                        
                        <Link
                            :href="route('admin.careers.index')"
                            class="block text-center mt-3 text-sm text-gray-600 hover:text-gray-900"
                        >
                            Cancel
                        </Link>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>