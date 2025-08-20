<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    categories: Object,
});

const deleteCategory = (category) => {
    if (confirm(`Are you sure you want to delete the category "${category.name}"?`)) {
        router.delete(route('admin.categories.destroy', category.id), {
            onError: (errors) => {
                if (errors.error) {
                    alert(errors.error);
                }
            }
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Categories - Admin" />
        
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Blog Categories</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage categories for your blog posts</p>
                </div>
                <Link
                    :href="route('admin.categories.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Category
                </Link>
            </div>
        </template>
        
        <div class="bg-white rounded-lg shadow-sm">
            <div v-if="categories.data.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                <div class="mt-6">
                    <Link
                        :href="route('admin.categories.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Category
                    </Link>
                </div>
            </div>
            
            <div v-else class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Posts
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Color
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="category in categories.data" :key="category.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
                                    <div v-if="category.description" class="text-sm text-gray-500">{{ category.description }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ category.posts_count || 0 }} posts
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div 
                                        v-if="category.color"
                                        class="w-6 h-6 rounded-full border border-gray-300"
                                        :style="{ backgroundColor: category.color }"
                                    ></div>
                                    <span v-else class="text-sm text-gray-400">No color</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <Link
                                    :href="route('admin.categories.edit', category.id)"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteCategory(category)"
                                    :disabled="category.posts_count > 0"
                                    class="text-red-600 hover:text-red-900 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="categories.last_page > 1" class="bg-gray-50 px-6 py-3 border-t">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ categories.from }} to {{ categories.to }} of {{ categories.total }} results
                    </div>
                    <div class="flex space-x-2">
                        <Link
                            v-for="link in categories.links"
                            :key="link.label"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1 text-sm rounded',
                                link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100',
                                !link.url && 'opacity-50 cursor-not-allowed'
                            ]"
                            :disabled="!link.url"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>