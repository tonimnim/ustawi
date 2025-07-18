<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    open: Boolean,
    isMobile: Boolean
});

const emit = defineEmits(['toggle']);

const page = usePage();

// Track which navigation sections are expanded
const expandedSections = ref(new Set());

// Navigation items with Ustawi Wa Jamii structure (removed Settings)
const navigationItems = [
    {
        name: 'Dashboard',
        href: '/admin/dashboard',
        icon: 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z',
        current: false
    },
    {
        name: 'Content Management',
        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        submenu: [
            { name: 'Pages', href: '/admin/pages' },
            { name: 'Blog Posts', href: '/admin/posts' },
            { name: 'Media Library', href: '/admin/media' },
            { name: 'Team Members', href: '/admin/team' },
        ]
    },
    {
        name: 'Projects',
        icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        submenu: [
            { name: 'All Projects', href: '/admin/projects' },
            { name: 'Impact Metrics', href: '/admin/metrics' },
            { name: 'Success Stories', href: '/admin/stories' },
        ]
    },
    {
        name: 'Donations & Finance',
        icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        submenu: [
            { name: 'All Donations', href: '/admin/donations' },
            { name: 'Donors', href: '/admin/donors' },
            { name: 'Reports', href: '/admin/reports' },
            { name: 'Payment Settings', href: '/admin/payments' },
        ]
    },
    {
        name: 'Users & Applications',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
        submenu: [
            { name: 'All Users', href: '/admin/users' },
            { name: 'Job Applications', href: '/admin/applications' },
            { name: 'Job Listings', href: '/admin/jobs' },
            { name: 'Newsletter', href: '/admin/newsletter' },
        ]
    },
    {
        name: 'Communications',
        icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        submenu: [
            { name: 'Contact Messages', href: '/admin/contacts' },
            { name: 'Email Campaigns', href: '/admin/campaigns' },
            { name: 'Comments', href: '/admin/comments' },
        ]
    },
    {
        name: 'Analytics',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        submenu: [
            { name: 'Website Stats', href: '/admin/analytics' },
            { name: 'Donation Analytics', href: '/admin/donation-analytics' },
            { name: 'Custom Reports', href: '/admin/custom-reports' },
        ]
    },
    {
        name: 'Settings',
        icon: 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        submenu: [
            { name: 'Site Settings', href: '/admin/settings' },
            { name: 'Admin Users', href: '/admin/admin-users' },
            { name: 'System Logs', href: '/admin/logs' },
        ]
    }
];

// Toggle section expansion
const toggleSection = (sectionName) => {
    if (expandedSections.value.has(sectionName)) {
        expandedSections.value.delete(sectionName);
    } else {
        expandedSections.value.add(sectionName);
    }
};

// Check if current route matches
const isCurrentRoute = (href) => {
    return page.url.startsWith(href);
};

const isParentActive = (item) => {
    if (item.href) return isCurrentRoute(item.href);
    if (item.submenu) {
        return item.submenu.some(subItem => isCurrentRoute(subItem.href));
    }
    return false;
};

// Auto-expand active sections on mount
const autoExpandActiveSection = () => {
    navigationItems.forEach(item => {
        if (item.submenu && isParentActive(item)) {
            expandedSections.value.add(item.name);
        }
    });
};

// Call on component mount
autoExpandActiveSection();
</script>

<template>
    <!-- Desktop Sidebar -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-10 lg:flex lg:w-64 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gradient-to-b from-sky-600 to-blue-700 px-6 pb-4">
            <!-- Logo -->
            <div class="flex h-16 shrink-0 items-center">
                <Link href="/admin/dashboard" class="flex items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <span class="text-sky-600 font-bold text-sm">UW</span>
                        </div>
                        <div class="text-white">
                            <div class="font-semibold text-sm">Ustawi Wa Jamii</div>
                            <div class="text-sky-100 text-xs">Admin Dashboard</div>
                        </div>
                    </div>
                </Link>
            </div>
            
            <!-- Navigation -->
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-1">
                    <li v-for="item in navigationItems" :key="item.name">
                        <!-- Items with submenu (collapsible sections) -->
                        <div v-if="item.submenu">
                            <button 
                                @click="toggleSection(item.name)"
                                :class="[
                                    isParentActive(item)
                                        ? 'bg-sky-700 text-white'
                                        : 'text-sky-100 hover:text-white hover:bg-sky-700',
                                    'group flex w-full items-center justify-between rounded-md p-2 text-sm leading-6 font-medium transition-colors duration-200'
                                ]"
                            >
                                <div class="flex items-center gap-x-3">
                                    <svg 
                                        class="h-5 w-5 shrink-0" 
                                        fill="none" 
                                        viewBox="0 0 24 24" 
                                        stroke-width="1.5" 
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                                    </svg>
                                    {{ item.name }}
                                </div>
                                <!-- Expand/Collapse Arrow -->
                                <svg 
                                    :class="[
                                        expandedSections.has(item.name) ? 'rotate-90' : '',
                                        'h-4 w-4 transition-transform duration-200'
                                    ]"
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke-width="2" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            
                            <!-- Submenu with slide animation -->
                            <div 
                                v-show="expandedSections.has(item.name)"
                                class="ml-6 mt-1 space-y-1 overflow-hidden transition-all duration-200 ease-in-out"
                            >
                                <Link 
                                    v-for="subItem in item.submenu" 
                                    :key="subItem.name"
                                    :href="subItem.href"
                                    :class="[
                                        isCurrentRoute(subItem.href)
                                            ? 'bg-sky-800 text-white border-l-2 border-sky-300'
                                            : 'text-sky-200 hover:text-white hover:bg-sky-800 border-l-2 border-transparent hover:border-sky-400',
                                        'group flex gap-x-3 rounded-r-md py-2 px-3 text-sm leading-6 font-medium transition-all duration-200'
                                    ]"
                                >
                                    <span class="w-1.5 h-1.5 bg-current rounded-full mt-2 opacity-70"></span>
                                    {{ subItem.name }}
                                </Link>
                            </div>
                        </div>
                        
                        <!-- Single items (like Dashboard) -->
                        <Link 
                            v-else
                            :href="item.href"
                            :class="[
                                isCurrentRoute(item.href)
                                    ? 'bg-sky-700 text-white'
                                    : 'text-sky-100 hover:text-white hover:bg-sky-700',
                                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium transition-colors duration-200'
                            ]"
                        >
                            <svg 
                                class="h-5 w-5 shrink-0" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke-width="1.5" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                            </svg>
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div 
        v-if="isMobile"
        :class="[
            open ? 'translate-x-0' : '-translate-x-full',
            'fixed inset-y-0 z-30 flex w-64 flex-col transition-transform duration-300 ease-in-out lg:hidden'
        ]"
    >
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gradient-to-b from-sky-600 to-blue-700 px-6 pb-4">
            <!-- Mobile Logo -->
            <div class="flex h-16 shrink-0 items-center justify-between">
                <Link href="/admin/dashboard" class="flex items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <span class="text-sky-600 font-bold text-sm">UW</span>
                        </div>
                        <div class="text-white">
                            <div class="font-semibold text-sm">Ustawi Wa Jamii</div>
                            <div class="text-sky-100 text-xs">Admin Dashboard</div>
                        </div>
                    </div>
                </Link>
                
                <button 
                    @click="$emit('toggle')"
                    class="text-sky-100 hover:text-white p-1"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-1">
                    <li v-for="item in navigationItems" :key="item.name">
                        <!-- Items with submenu (collapsible sections) -->
                        <div v-if="item.submenu">
                            <button 
                                @click="toggleSection(item.name)"
                                :class="[
                                    isParentActive(item)
                                        ? 'bg-sky-700 text-white'
                                        : 'text-sky-100 hover:text-white hover:bg-sky-700',
                                    'group flex w-full items-center justify-between rounded-md p-2 text-sm leading-6 font-medium transition-colors duration-200'
                                ]"
                            >
                                <div class="flex items-center gap-x-3">
                                    <svg 
                                        class="h-5 w-5 shrink-0" 
                                        fill="none" 
                                        viewBox="0 0 24 24" 
                                        stroke-width="1.5" 
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                                    </svg>
                                    {{ item.name }}
                                </div>
                                <!-- Expand/Collapse Arrow -->
                                <svg 
                                    :class="[
                                        expandedSections.has(item.name) ? 'rotate-90' : '',
                                        'h-4 w-4 transition-transform duration-200'
                                    ]"
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke-width="2" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            
                            <!-- Submenu with slide animation -->
                            <div 
                                v-show="expandedSections.has(item.name)"
                                class="ml-6 mt-1 space-y-1 overflow-hidden transition-all duration-200 ease-in-out"
                            >
                                <Link 
                                    v-for="subItem in item.submenu" 
                                    :key="subItem.name"
                                    :href="subItem.href"
                                    @click="$emit('toggle')"
                                    :class="[
                                        isCurrentRoute(subItem.href)
                                            ? 'bg-sky-800 text-white border-l-2 border-sky-300'
                                            : 'text-sky-200 hover:text-white hover:bg-sky-800 border-l-2 border-transparent hover:border-sky-400',
                                        'group flex gap-x-3 rounded-r-md py-2 px-3 text-sm leading-6 font-medium transition-all duration-200'
                                    ]"
                                >
                                    <span class="w-1.5 h-1.5 bg-current rounded-full mt-2 opacity-70"></span>
                                    {{ subItem.name }}
                                </Link>
                            </div>
                        </div>
                        
                        <!-- Single items (like Dashboard) -->
                        <Link 
                            v-else
                            :href="item.href"
                            @click="$emit('toggle')"
                            :class="[
                                isCurrentRoute(item.href)
                                    ? 'bg-sky-700 text-white'
                                    : 'text-sky-100 hover:text-white hover:bg-sky-700',
                                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium transition-colors duration-200'
                            ]"
                        >
                            <svg 
                                class="h-5 w-5 shrink-0" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke-width="1.5" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                            </svg>
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>