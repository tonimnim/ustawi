<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Mock dashboard data (will be replaced with real data)
const stats = [
    {
        name: 'Total Donations (This Month)',
        value: 'KES 2,450,000',
        change: '+12.5%',
        changeType: 'increase',
        icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        name: 'Active Donors',
        value: '1,245',
        change: '+8.2%',
        changeType: 'increase',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'
    },
    {
        name: 'Newsletter Subscribers',
        value: '3,842',
        change: '+15.1%',
        changeType: 'increase',
        icon: 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 2.25l-8.8 5.28a2.25 2.25 0 01-2.4 0L2.25 9M15 10.5a3 3 0 11-6 0 3 3 0 016 0z'
    },
    {
        name: 'Website Visitors (Today)',
        value: '892',
        change: '+3.8%',
        changeType: 'increase',
        icon: 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
    }
];

const impactMetrics = [
    { name: 'Farmers Trained', value: '6,247', target: '7,000', progress: 89 },
    { name: 'Trees Planted', value: '5,892', target: '6,000', progress: 98 },
    { name: 'Students Sponsored', value: '2,156', target: '2,500', progress: 86 },
    { name: 'Legal Cases Resolved', value: '342', target: '400', progress: 86 }
];

const recentActivities = [
    {
        id: 1,
        type: 'donation',
        title: 'New donation received',
        description: 'KES 15,000 from Mary Wanjiku for Harvest of Tomorrow',
        time: '2 minutes ago',
        icon: 'donation'
    },
    {
        id: 2,
        type: 'application',
        title: 'Job application submitted',
        description: 'Application for Agricultural Officer in Kirinyaga',
        time: '1 hour ago',
        icon: 'application'
    },
    {
        id: 3,
        type: 'blog',
        title: 'New blog post published',
        description: 'Success story from Marsabit County published',
        time: '3 hours ago',
        icon: 'blog'
    },
    {
        id: 4,
        type: 'contact',
        title: 'Contact form submission',
        description: 'Partnership inquiry from Green Earth Foundation',
        time: '5 hours ago',
        icon: 'contact'
    }
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600">Welcome back, {{ user?.name }}! Here's what's happening at Ustawi Wa Jamii.</p>
                </div>
                <div class="text-sm text-gray-500">
                    Last updated: {{ new Date().toLocaleString() }}
                </div>
            </div>
        </template>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div 
                v-for="stat in stats" 
                :key="stat.name"
                class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow"
            >
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ stat.name }}</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ stat.value }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <svg class="h-4 w-4 flex-shrink-0 self-center" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        {{ stat.change }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Metrics & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Impact Metrics -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Impact Metrics</h3>
                    <p class="text-gray-600 text-sm">Progress towards our 2025 goals</p>
                </div>
                <div class="p-6 space-y-6">
                    <div v-for="metric in impactMetrics" :key="metric.name" class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">{{ metric.name }}</span>
                            <span class="text-sm text-gray-600">{{ metric.value }} / {{ metric.target }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div 
                                class="bg-gradient-to-r from-sky-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                :style="{ width: `${metric.progress}%` }"
                            ></div>
                        </div>
                        <div class="text-xs text-gray-500">{{ metric.progress }}% complete</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    <p class="text-gray-600 text-sm">Latest updates across the platform</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-sky-600 rounded-full"></div>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
                                <p class="text-sm text-gray-600">{{ activity.description }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ activity.time }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="/admin/activities" class="text-sm text-sky-600 hover:text-sky-700 font-medium">
                            View all activity →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                <p class="text-gray-600 text-sm">Common tasks and shortcuts</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="/admin/posts/create" class="flex flex-col items-center p-4 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors">
                        <svg class="h-8 w-8 text-sky-600 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900">New Post</span>
                    </a>
                    
                    <a href="/admin/donations" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <svg class="h-8 w-8 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900">View Donations</span>
                    </a>
                    
                    <a href="/admin/applications" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <svg class="h-8 w-8 text-purple-600 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Applications</span>
                    </a>
                    
                    <a href="/admin/reports" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                        <svg class="h-8 w-8 text-orange-600 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Reports</span>
                    </a>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
