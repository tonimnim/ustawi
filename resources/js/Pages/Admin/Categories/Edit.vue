<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    description: props.category.description || '',
    color: props.category.color || '#3B82F6',
});

const presetColors = [
    '#EF4444', // red
    '#F97316', // orange
    '#F59E0B', // amber
    '#EAB308', // yellow
    '#84CC16', // lime
    '#22C55E', // green
    '#10B981', // emerald
    '#14B8A6', // teal
    '#06B6D4', // cyan
    '#0EA5E9', // sky
    '#3B82F6', // blue
    '#6366F1', // indigo
    '#8B5CF6', // violet
    '#A855F7', // purple
    '#D946EF', // fuchsia
    '#EC4899', // pink
    '#F43F5E', // rose
    '#64748B', // slate
];

const submit = () => {
    form.put(route('admin.categories.update', props.category.id), {
        onError: (errors) => {
            if (errors.message && errors.message.includes('419')) {
                alert('Your session has expired. The page will refresh.');
                window.location.reload();
            }
        }
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Edit Category - Admin" />
        
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
                    <p class="mt-1 text-sm text-gray-600">Update category information</p>
                </div>
                <Link
                    :href="route('admin.categories.index')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Cancel
                </Link>
            </div>
        </template>
        
        <form @submit.prevent="submit">
            <div class="max-w-2xl">
                <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            :class="{ 'border-red-500': form.errors.name }"
                            placeholder="e.g., Community Development"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            :class="{ 'border-red-500': form.errors.description }"
                            placeholder="Brief description of this category..."
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                    </div>
                    
                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Color
                        </label>
                        <div class="space-y-3">
                            <!-- Color Picker -->
                            <div class="flex items-center space-x-3">
                                <input
                                    id="color"
                                    v-model="form.color"
                                    type="color"
                                    class="h-10 w-20 border border-gray-300 rounded cursor-pointer"
                                />
                                <input
                                    v-model="form.color"
                                    type="text"
                                    class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.color }"
                                    placeholder="#3B82F6"
                                    maxlength="7"
                                />
                            </div>
                            
                            <!-- Preset Colors -->
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="color in presetColors"
                                    :key="color"
                                    type="button"
                                    @click="form.color = color"
                                    class="w-8 h-8 rounded-full border-2 transition-all"
                                    :class="form.color === color ? 'border-gray-900 scale-110' : 'border-gray-300'"
                                    :style="{ backgroundColor: color }"
                                    :title="color"
                                />
                            </div>
                        </div>
                        <p v-if="form.errors.color" class="mt-1 text-sm text-red-600">{{ form.errors.color }}</p>
                    </div>
                    
                    <!-- Preview -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <span 
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                                :style="{ backgroundColor: form.color || '#3B82F6' }"
                            >
                                {{ form.name || 'Category Name' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <Link
                            :href="route('admin.categories.index')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update Category</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>