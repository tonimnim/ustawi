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
    payment_method: '', // 'card', 'mpesa'
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
        description: 'Pay via M-Pesa mobile money',
        icon: '/assets/icons8-mpesa.svg',
        color: 'bg-green-100'
    },
    {
        id: 'card',
        name: 'Credit/Debit Card',
        description: 'Pay with Visa or Mastercard',
        icon: '/assets/icons8-debit-card-50.png',
        color: 'bg-blue-100'
    }
];



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

    // Minimum amount check
    if (parseFloat(form.amount) < 50) {
        alert('Minimum donation amount is KES 50');
        return;
    }

    // Validate payment method
    if (!form.payment_method) {
        alert('Please select a payment method');
        return;
    }
    
    // Validate phone number for M-Pesa
    if (form.payment_method === 'mpesa' && !form.donor_phone) {
        alert('Phone number is required for M-Pesa payments');
        return;
    }
    
    // Validate non-anonymous donations
    if (!form.is_anonymous) {
        if (!form.donor_name || form.donor_name.trim() === '') {
            alert('Please enter your name or choose to donate anonymously');
            return;
        }
        if (!form.donor_email || form.donor_email.trim() === '') {
            alert('Please enter your email or choose to donate anonymously');
            return;
        }
    }
    
    // Set default values for anonymous donations
    if (form.is_anonymous) {
        form.donor_name = 'Anonymous';
        form.donor_email = 'anonymous@donation.com';
    }

    // Log form data for debugging
    console.log('Submitting donation with data:', {
        amount: form.amount,
        payment_method: form.payment_method,
        frequency: form.frequency,
        is_anonymous: form.is_anonymous,
        donor_name: form.donor_name,
        donor_email: form.donor_email,
        donor_phone: form.donor_phone
    });

    // Submit to backend for payment processing
    form.post(route('donate.process'), {
        onSuccess: () => {
            // Payment will be processed via Paystack
            console.log('Donation submitted successfully, redirecting to payment...');
        },
        onError: (errors) => {
            console.error('Error submitting donation:', errors);
            if (errors.donor_name) {
                alert('Name error: ' + errors.donor_name);
            } else if (errors.donor_email) {
                alert('Email error: ' + errors.donor_email);
            } else if (errors.payment_method) {
                alert('Payment method error: ' + errors.payment_method);
            } else if (errors.amount) {
                alert('Amount error: ' + errors.amount);
            } else {
                alert('Error processing donation. Please check all fields and try again.');
            }
        }
    });
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
                    and create sustainable change.
                </p>
            </div>
        </section>

        <!-- Donation Form Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success/Error Messages -->
                <div v-if="$page.props.flash?.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-lg max-w-4xl">
                    <p class="font-medium">{{ $page.props.flash.success }}</p>
                </div>
                <div v-if="$page.props.flash?.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-lg max-w-4xl">
                    <p class="font-medium">{{ $page.props.flash.error }}</p>
                </div>
                
                <!-- Main Layout Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Side - Donation Type Selection (2 columns) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-xl p-6">
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
                    </div>
                    
                    <!-- Right Side - Payment Method Selection (1 column) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-4">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h2>
                            
                            <div class="space-y-3">
                                <button
                                    v-for="method in paymentMethods"
                                    :key="method.id"
                                    @click="form.payment_method = method.id"
                                    :class="[
                                        'w-full p-4 rounded-lg border-2 transition-all duration-300 text-left',
                                        form.payment_method === method.id 
                                            ? 'border-orange-500 bg-orange-50' 
                                            : 'border-gray-300 hover:border-gray-400'
                                    ]"
                                >
                                    <div class="flex items-center space-x-3">
                                        <div :class="[method.color, 'p-2 rounded-lg flex items-center justify-center']">
                                            <img :src="method.icon" :alt="method.name" class="h-6 w-6 object-contain" />
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold">{{ method.name }}</h4>
                                            <p class="text-xs text-gray-600">{{ method.description }}</p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <!-- Payment Method Info -->
                            <div v-if="form.payment_method === 'card'" class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <p class="text-xs text-gray-600">
                                    You'll be redirected to a secure payment page.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donor Information -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 mt-8 max-w-4xl">
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
                        </div>

                        <!-- Phone Number Field - Always visible when M-Pesa is selected -->
                        <div v-if="!form.is_anonymous || form.payment_method === 'mpesa'" 
                             :class="[
                                 'mt-6',
                                 form.payment_method === 'mpesa' ? 'p-4 bg-green-50 rounded-lg border-2 border-green-300' : ''
                             ]">
                            <label for="donor_phone" class="block text-sm font-medium mb-2"
                                   :class="form.payment_method === 'mpesa' ? 'text-green-800' : 'text-gray-700'">
                                Phone Number {{ form.payment_method === 'mpesa' ? '(Required for M-Pesa)' : '(Optional)' }} {{ form.payment_method === 'mpesa' ? '*' : '' }}
                            </label>
                            <input
                                id="donor_phone"
                                v-model="form.donor_phone"
                                type="tel"
                                :required="form.payment_method === 'mpesa'"
                                placeholder="0700000000"
                                :class="[
                                    'w-full px-4 py-3 border rounded-lg focus:ring-2 focus:border-transparent',
                                    form.payment_method === 'mpesa' 
                                        ? 'border-green-400 focus:ring-green-500' 
                                        : 'border-gray-300 focus:ring-orange-500'
                                ]"
                            />
                            <p v-if="form.payment_method === 'mpesa'" class="mt-1 text-sm text-green-700 font-medium">
                                Enter your M-Pesa registered phone number (e.g., 0700000000)
                            </p>
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

                <!-- Submit Button -->
                <div class="text-center max-w-4xl">
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