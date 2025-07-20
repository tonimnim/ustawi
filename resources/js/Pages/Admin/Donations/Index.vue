<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    activeTab: {
        type: String,
        default: 'donations'
    }
});

// Tab management
const currentTab = ref(props.activeTab);

const switchTab = (tab) => {
    currentTab.value = tab;
};

// Mock data for donations (will be replaced with real data when backend is integrated)
const donations = ref([
    {
        id: 1,
        donor_name: 'John Doe',
        donor_email: 'john@example.com',
        amount: 50000,
        currency: 'KES',
        payment_method: 'M-Pesa',
        transaction_id: 'QH45JKRTE3',
        status: 'completed',
        purpose: 'General Support',
        date: '2025-01-19T10:30:00',
        anonymous: false
    },
    {
        id: 2,
        donor_name: 'Anonymous',
        donor_email: 'donor2@example.com',
        amount: 25000,
        currency: 'KES',
        payment_method: 'Bank Transfer',
        transaction_id: 'BNK-2025-0023',
        status: 'completed',
        purpose: 'Youth Programs',
        date: '2025-01-18T14:20:00',
        anonymous: true
    },
    {
        id: 3,
        donor_name: 'Sarah Johnson',
        donor_email: 'sarah.j@example.com',
        amount: 100,
        currency: 'USD',
        payment_method: 'PayPal',
        transaction_id: 'PP-5Y6789KLM',
        status: 'pending',
        purpose: 'Environmental Projects',
        date: '2025-01-18T09:15:00',
        anonymous: false
    }
]);

// Mock data for donors
const donors = ref([
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        phone: '+254 712 345 678',
        total_donated: 150000,
        donation_count: 5,
        first_donation: '2024-06-15',
        last_donation: '2025-01-19',
        status: 'active',
        type: 'individual'
    },
    {
        id: 2,
        name: 'ABC Foundation',
        email: 'info@abcfoundation.org',
        phone: '+254 720 987 654',
        total_donated: 1500000,
        donation_count: 12,
        first_donation: '2024-01-10',
        last_donation: '2025-01-15',
        status: 'active',
        type: 'organization'
    },
    {
        id: 3,
        name: 'Sarah Johnson',
        email: 'sarah.j@example.com',
        phone: '+1 555 123 4567',
        total_donated: 500,
        donation_count: 3,
        first_donation: '2024-09-20',
        last_donation: '2025-01-18',
        status: 'active',
        type: 'individual'
    }
]);

// Statistics
const stats = computed(() => ({
    total_donations: donations.value.reduce((sum, d) => {
        if (d.status === 'completed') {
            if (d.currency === 'KES') return sum + d.amount;
            if (d.currency === 'USD') return sum + (d.amount * 150); // Rough conversion
            return sum;
        }
        return sum;
    }, 0),
    monthly_donations: 175000, // Mock data
    total_donors: donors.value.length,
    active_donors: donors.value.filter(d => d.status === 'active').length,
    pending_donations: donations.value.filter(d => d.status === 'pending').length,
    completed_donations: donations.value.filter(d => d.status === 'completed').length
}));

// Format currency
const formatCurrency = (amount, currency = 'KES') => {
    return new Intl.NumberFormat('en-KE', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0
    }).format(amount);
};

// Format date
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Get status badge
const getStatusBadge = (status) => {
    const badges = {
        completed: 'bg-green-100 text-green-800',
        pending: 'bg-yellow-100 text-yellow-800',
        failed: 'bg-red-100 text-red-800',
        refunded: 'bg-gray-100 text-gray-800'
    };
    return badges[status] || 'bg-gray-100 text-gray-800';
};

// Get donor type badge
const getDonorTypeBadge = (type) => {
    return type === 'organization' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800';
};
</script>

<template>
    <AdminLayout>
        <Head title="Donations Management - Admin" />
        
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Donations Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Track donations and manage donor relationships</p>
                </div>
                <button
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Record Donation
                </button>
            </div>
        </template>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Total Donations</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ formatCurrency(stats.total_donations) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">This Month</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ formatCurrency(stats.monthly_donations) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Total Donors</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.total_donors }}</p>
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
                        <p class="text-gray-500 text-sm">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.pending_donations }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button
                        @click="switchTab('donations')"
                        :class="[
                            currentTab === 'donations'
                                ? 'border-blue-500 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            'whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors'
                        ]"
                    >
                        Recent Donations
                    </button>
                    <button
                        @click="switchTab('donors')"
                        :class="[
                            currentTab === 'donors'
                                ? 'border-blue-500 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            'whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors'
                        ]"
                    >
                        Donors
                    </button>
                </nav>
            </div>
            
            <!-- Donations Tab -->
            <div v-if="currentTab === 'donations'" class="p-6">
                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input
                        type="text"
                        placeholder="Search donations..."
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Methods</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="paypal">PayPal</option>
                        <option value="card">Credit/Debit Card</option>
                    </select>
                    <input
                        type="date"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                
                <!-- Donations Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Donor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Method
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Purpose
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="donation in donations" :key="donation.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ donation.anonymous ? 'Anonymous Donor' : donation.donor_name }}
                                        </div>
                                        <div v-if="!donation.anonymous" class="text-sm text-gray-500">
                                            {{ donation.donor_email }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ formatCurrency(donation.amount, donation.currency) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ donation.payment_method }}</div>
                                    <div class="text-xs text-gray-500">{{ donation.transaction_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ donation.purpose }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusBadge(donation.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ donation.status.charAt(0).toUpperCase() + donation.status.slice(1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(donation.date) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                    <button class="text-gray-600 hover:text-gray-900">Receipt</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Donors Tab -->
            <div v-else-if="currentTab === 'donors'" class="p-6">
                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input
                        type="text"
                        placeholder="Search donors..."
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="individual">Individual</option>
                        <option value="organization">Organization</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Donor
                    </button>
                </div>
                
                <!-- Donors Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Donor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Donated
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Donations
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Donation
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="donor in donors" :key="donor.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ donor.name }}</div>
                                        <div class="text-sm text-gray-500">{{ donor.email }}</div>
                                        <div v-if="donor.phone" class="text-sm text-gray-500">{{ donor.phone }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getDonorTypeBadge(donor.type)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ donor.type.charAt(0).toUpperCase() + donor.type.slice(1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ formatCurrency(donor.total_donated) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ donor.donation_count }} {{ donor.donation_count === 1 ? 'donation' : 'donations' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ new Date(donor.last_donation).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="donor.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" 
                                          class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ donor.status.charAt(0).toUpperCase() + donor.status.slice(1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                    <button class="text-gray-600 hover:text-gray-900">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Note about integration -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="flex-shrink-0 h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> This is a frontend preview. Payment integration will be implemented when payment methods are configured.
                    </p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>