<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    listing: Object
});

// Convert HTML list back to line breaks for editing
const convertFromList = (html) => {
    if (!html) return '';
    // Remove HTML tags and convert list items to lines
    const div = document.createElement('div');
    div.innerHTML = html;
    const items = div.querySelectorAll('li');
    return Array.from(items).map(item => item.textContent.trim()).join('\n');
};

const form = useForm({
    title: props.listing.title,
    type: props.listing.type,
    department: props.listing.department || '',
    location: props.listing.location,
    employment_type: props.listing.employment_type,
    description: props.listing.description,
    requirements: convertFromList(props.listing.requirements),
    responsibilities: convertFromList(props.listing.responsibilities),
    salary_range: props.listing.salary_range || '',
    deadline: props.listing.deadline ? props.listing.deadline.split(' ')[0] : '',
    is_active: Boolean(props.listing.is_active),
    sort_order: props.listing.sort_order || 0,
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
    
    form.transform(() => data).put(route('admin.careers.update', props.listing.id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Edit Career Listing - Admin" />
        
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Career Listing</h1>
                    <p class="mt-1 text-sm text-gray-600">Update job or volunteer opportunity details</p>
                </div>
                <Link
                    :href="route('admin.careers.index')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Back to Listings
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
                            
                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sort Order
                                </label>
                                <input
                                    id="sort_order"
                                    v-model.number="form.sort_order"
                                    type="number"
                                    min="0"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.sort_order }"
                                />
                                <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                                <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">{{ form.errors.sort_order }}</p>
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
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.deadline }"
                                />
                                <p class="mt-1 text-xs text-gray-500">Leave empty for no deadline</p>
                                <p v-if="form.errors.deadline" class="mt-1 text-sm text-red-600">{{ form.errors.deadline }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Listing Info -->
                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                        <p><strong>Created:</strong> {{ new Date(listing.created_at).toLocaleDateString() }}</p>
                        <p><strong>Updated:</strong> {{ new Date(listing.updated_at).toLocaleDateString() }}</p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update Listing</span>
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