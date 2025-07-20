<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    settings: Object,
    projects: {
        type: Array,
        default: () => []
    }
});

// Donation form
const form = useForm({
    amount: '',
    custom_amount: '',
    frequency: 'one-time', // 'one-time' or 'monthly'
    payment_method: '', // 'paypal', 'card', 'mpesa', 'wire'
    project_designation: 'where-needed-most',
    donor_name: '',
    donor_email: '',
    donor_phone: '',
    donor_message: '',
    is_anonymous: false,
});

// Predefined donation amounts
const donationAmounts = [
    { value: 1000, label: 'KES 1,000' },
    { value: 2500, label: 'KES 2,500' },
    { value: 5000, label: 'KES 5,000' },
    { value: 10000, label: 'KES 10,000' },
    { value: 25000, label: 'KES 25,000' },
    { value: 50000, label: 'KES 50,000' },
];

// Projects for designation
const projectOptions = [
    { value: 'where-needed-most', label: 'Where Needed Most' },
    { value: 'youth-empowerment', label: 'Youth Economic Empowerment' },
    { value: 'climate-action', label: 'Climate Action & Tree Planting' },
    { value: 'community-development', label: 'Community Development' },
    { value: 'legal-support', label: 'Rights & Legal Support' },
    { value: 'education', label: 'Education Programs' },
];

// Selected amount
const selectedAmount = ref('');
const showCustomAmount = ref(false);

// Computed actual amount
const actualAmount = computed(() => {
    if (showCustomAmount.value && form.custom_amount) {
        return parseFloat(form.custom_amount) || 0;
    }
    return parseFloat(selectedAmount.value) || 0;
});

// Watch for amount selection
watch(selectedAmount, (newValue) => {
    if (newValue === 'custom') {
        showCustomAmount.value = true;
        form.amount = '';
    } else {
        showCustomAmount.value = false;
        form.amount = newValue;
        form.custom_amount = '';
    }
});

// Payment method details
const paymentMethods = [
    {
        id: 'mpesa',
        name: 'M-Pesa',
        description: 'Pay via M-Pesa Paybill or STK Push',
        icon: '/assets/icons8-mpesa.svg',
        color: 'bg-green-100'
    },
    {
        id: 'card',
        name: 'Credit/Debit Card',
        description: 'Pay with Visa or Mastercard',
        icon: '/assets/icons8-debit-card-50.png',
        color: 'bg-blue-100'
    },
    {
        id: 'paypal',
        name: 'PayPal',
        description: 'Secure payment via PayPal',
        icon: '/assets/icons8-paypal.svg',
        color: 'bg-indigo-100'
    },
    {
        id: 'wire',
        name: 'Wire Transfer',
        description: 'Direct bank transfer',
        icon: '/assets/wiretransfer.jpg',
        color: 'bg-gray-100'
    }
];

// Wire transfer details
const wireTransferDetails = {
    bank_name: 'Kenya Commercial Bank',
    account_name: 'Ustawi Wa Jamii',
    account_number: '1234567890',
    branch: 'Nairobi Main Branch',
    swift_code: 'KCBLKENX'
};

// M-Pesa details
const mpesaDetails = {
    paybill: '123456',
    account_name: 'Ustawi Wa Jamii'
};

// Submit donation
const submitDonation = () => {
    // Set the final amount
    if (showCustomAmount.value) {
        form.amount = form.custom_amount;
    }

    // Validate amount
    if (!form.amount || parseFloat(form.amount) <= 0) {
        alert('Please enter a valid donation amount');
        return;
    }

    // Validate payment method
    if (!form.payment_method) {
        alert('Please select a payment method');
        return;
    }

    // Submit based on payment method
    if (form.payment_method === 'wire') {
        // For wire transfer, just show the details
        alert('Wire transfer details will be shown. Please make the transfer using the provided details.');
        // In real implementation, you might want to save this as a pending donation
    } else {
        // For other methods, submit to backend
        form.post(route('donate.process'), {
            onSuccess: () => {
                // Handle success based on payment method
                console.log('Donation submitted successfully');
            },
            onError: (errors) => {
                console.error('Error submitting donation:', errors);
            }
        });
    }
};
</script>

<template>
    <Head title="Donate - Ustawi Wa Jamii" />
    
    <PublicLayout :settings="settings">
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-orange-600 to-red-600 text-white py-20 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl lg:text-5xl font-bold text-center mb-6">Make a Difference Today</h1>
                <p class="text-xl text-center max-w-3xl mx-auto">
                    Your generous donation helps us empower communities, support youth development, 
                    and create sustainable change across Kenya.
                </p>
            </div>
        </section>

        <!-- Donation Form Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success/Error Messages -->
                <div v-if="$page.props.flash?.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-lg">
                    <p class="font-medium">{{ $page.props.flash.success }}</p>
                </div>
                <div v-if="$page.props.flash?.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-lg">
                    <p class="font-medium">{{ $page.props.flash.error }}</p>
                </div>
                <!-- Donation Type Selection -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Choose Your Donation Type</h2>
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-8">
                        <button
                            @click="form.frequency = 'one-time'"
                            :class="[
                                'p-4 rounded-lg border-2 transition-all duration-300',
                                form.frequency === 'one-time' 
                                    ? 'border-orange-500 bg-orange-50 text-orange-700' 
                                    : 'border-gray-300 hover:border-gray-400'
                            ]"
                        >
                            <div class="font-semibold text-lg mb-1">One-Time Donation</div>
                            <div class="text-sm text-gray-600">Make a single contribution</div>
                        </button>
                        
                        <button
                            @click="form.frequency = 'monthly'"
                            :class="[
                                'p-4 rounded-lg border-2 transition-all duration-300',
                                form.frequency === 'monthly' 
                                    ? 'border-orange-500 bg-orange-50 text-orange-700' 
                                    : 'border-gray-300 hover:border-gray-400'
                            ]"
                        >
                            <div class="font-semibold text-lg mb-1">Monthly Donation</div>
                            <div class="text-sm text-gray-600">Become a recurring supporter</div>
                        </button>
                    </div>

                    <!-- Amount Selection -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Select or Enter Amount</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <button
                            v-for="amount in donationAmounts"
                            :key="amount.value"
                            @click="selectedAmount = amount.value"
                            :class="[
                                'p-4 rounded-lg border-2 transition-all duration-300 font-semibold',
                                selectedAmount == amount.value 
                                    ? 'border-orange-500 bg-orange-50 text-orange-700' 
                                    : 'border-gray-300 hover:border-gray-400'
                            ]"
                        >
                            {{ amount.label }}
                        </button>
                        
                        <button
                            @click="selectedAmount = 'custom'"
                            :class="[
                                'p-4 rounded-lg border-2 transition-all duration-300 font-semibold',
                                selectedAmount === 'custom' 
                                    ? 'border-orange-500 bg-orange-50 text-orange-700' 
                                    : 'border-gray-300 hover:border-gray-400'
                            ]"
                        >
                            Other Amount
                        </button>
                    </div>

                    <!-- Custom Amount Input -->
                    <div v-if="showCustomAmount" class="mb-6">
                        <label for="custom_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter Amount (KES)
                        </label>
                        <input
                            id="custom_amount"
                            v-model="form.custom_amount"
                            type="number"
                            min="1"
                            placeholder="Enter amount"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Project Designation -->
                    <div class="mb-6">
                        <label for="project" class="block text-sm font-medium text-gray-700 mb-2">
                            Designate Your Donation (Optional)
                        </label>
                        <select
                            id="project"
                            v-model="form.project_designation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                            <option v-for="project in projectOptions" :key="project.value" :value="project.value">
                                {{ project.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Payment Method</h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <button
                            v-for="method in paymentMethods"
                            :key="method.id"
                            @click="form.payment_method = method.id"
                            :class="[
                                'p-6 rounded-lg border-2 transition-all duration-300 text-left',
                                form.payment_method === method.id 
                                    ? 'border-orange-500 bg-orange-50' 
                                    : 'border-gray-300 hover:border-gray-400'
                            ]"
                        >
                            <div class="flex items-start space-x-4">
                                <div :class="[method.color, 'p-3 rounded-lg flex items-center justify-center']">
                                    <img :src="method.icon" :alt="method.name" class="h-8 w-8 object-contain" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg">{{ method.name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ method.description }}</p>
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Payment Method Details -->
                    <div v-if="form.payment_method === 'wire'" class="mt-6 p-6 bg-gray-100 rounded-lg">
                        <h4 class="font-semibold text-lg mb-4">Wire Transfer Details</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>Bank Name:</strong> {{ wireTransferDetails.bank_name }}</p>
                            <p><strong>Account Name:</strong> {{ wireTransferDetails.account_name }}</p>
                            <p><strong>Account Number:</strong> {{ wireTransferDetails.account_number }}</p>
                            <p><strong>Branch:</strong> {{ wireTransferDetails.branch }}</p>
                            <p><strong>SWIFT Code:</strong> {{ wireTransferDetails.swift_code }}</p>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">
                            Please use your name as the reference when making the transfer.
                        </p>
                    </div>

                    <div v-if="form.payment_method === 'mpesa'" class="mt-6 p-6 bg-gray-100 rounded-lg">
                        <h4 class="font-semibold text-lg mb-4">M-Pesa Payment</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>Paybill Number:</strong> {{ mpesaDetails.paybill }}</p>
                            <p><strong>Account Name:</strong> {{ mpesaDetails.account_name }}</p>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">
                            You can also use STK Push for instant payment. Click continue to proceed.
                        </p>
                    </div>
                </div>

                <!-- Donor Information -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Information</h2>
                    
                    <div class="space-y-6">
                        <!-- Anonymous Donation Option -->
                        <div class="flex items-center">
                            <input
                                id="anonymous"
                                v-model="form.is_anonymous"
                                type="checkbox"
                                class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                            />
                            <label for="anonymous" class="ml-2 text-sm text-gray-700">
                                Make this donation anonymous
                            </label>
                        </div>

                        <div v-if="!form.is_anonymous" class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="donor_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name*
                                </label>
                                <input
                                    id="donor_name"
                                    v-model="form.donor_name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label for="donor_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address*
                                </label>
                                <input
                                    id="donor_email"
                                    v-model="form.donor_email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                />
                            </div>

                            <div class="md:col-span-2">
                                <label for="donor_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number (Optional)
                                </label>
                                <input
                                    id="donor_phone"
                                    v-model="form.donor_phone"
                                    type="tel"
                                    placeholder="+254 700 000 000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                />
                            </div>
                        </div>

                        <div>
                            <label for="donor_message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message or Special Instructions (Optional)
                            </label>
                            <textarea
                                id="donor_message"
                                v-model="form.donor_message"
                                rows="4"
                                placeholder="Share why you're supporting our cause..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Donation Summary -->
                <div class="bg-orange-50 rounded-2xl p-8 mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Donation Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Donation Amount:</span>
                            <span class="font-semibold">KES {{ actualAmount.toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Frequency:</span>
                            <span class="font-semibold">{{ form.frequency === 'monthly' ? 'Monthly' : 'One-Time' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Designation:</span>
                            <span class="font-semibold">
                                {{ projectOptions.find(p => p.value === form.project_designation)?.label }}
                            </span>
                        </div>
                        <div v-if="form.payment_method" class="flex justify-between">
                            <span class="text-gray-700">Payment Method:</span>
                            <span class="font-semibold">
                                {{ paymentMethods.find(m => m.id === form.payment_method)?.name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button
                        @click="submitDonation"
                        :disabled="!actualAmount || !form.payment_method || form.processing"
                        class="px-12 py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold text-lg rounded-full transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105"
                    >
                        <span v-if="form.processing">Processing...</span>
                        <span v-else>Complete Donation</span>
                    </button>
                    
                    <p class="mt-4 text-sm text-gray-600">
                        Your donation is secure and will be processed immediately.
                    </p>
                </div>
            </div>
        </section>

        <!-- Trust Section -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Your Trust Matters</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <div class="mb-4 flex justify-center">
                            <img src="/assets/secure-payment-icon.png" alt="Secure Payments" class="h-16 w-16 object-contain" />
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Secure Payments</h3>
                        <p class="text-gray-600">All transactions are encrypted and secure</p>
                    </div>
                    <div>
                        <div class="mb-4 flex justify-center">
                            <img src="/assets/transparent.png" alt="Transparent Use" class="h-16 w-16 object-contain" />
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Transparent Use</h3>
                        <p class="text-gray-600">We provide regular updates on how funds are used</p>
                    </div>
                    <div>
                        <div class="mb-4 flex justify-center">
                            <img src="/assets/tax.png" alt="Tax Deductible" class="h-16 w-16 object-contain" />
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Tax Deductible</h3>
                        <p class="text-gray-600">Your donation is tax-deductible as allowed by law</p>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>