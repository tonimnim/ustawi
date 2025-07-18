<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Mobile menu state
const showMobileMenu = ref(false);
const isScrolled = ref(false);

// Navigation items
const navigation = [
    { name: 'Home', href: '/' },
    { name: 'About', href: '/#about' },
    { name: 'Programs', href: '/programs' },
    { name: 'Impact', href: '/#impact' },
    { name: 'News', href: '/#news' },
    { name: 'Contact', href: '/#contact' },
];

// Handle scroll for header styling
const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

// Smooth scroll to section
const scrollToSection = (href) => {
    if (href.startsWith('#')) {
        const element = document.querySelector(href);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    }
    showMobileMenu.value = false;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <!-- Header -->
        <header 
            :class="[
                'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
                isScrolled 
                    ? 'bg-white/95 backdrop-blur-md shadow-lg' 
                    : 'bg-transparent'
            ]"
        >
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <img 
                            src="/images/ustawilogo.jpeg" 
                            alt="Ustawi Wa Jamii Logo" 
                            class="h-12 w-12 rounded-full"
                        >
                        <div>
                            <h1 
                                :class="[
                                    'text-xl font-bold transition-colors duration-300',
                                    isScrolled ? 'text-gray-900' : 'text-white'
                                ]"
                            >
                                {{ settings.organization_name }}
                            </h1>
                            <p 
                                :class="[
                                    'text-sm transition-colors duration-300',
                                    isScrolled ? 'text-gray-600' : 'text-white/90'
                                ]"
                            >
                                Est. 2024
                            </p>
                        </div>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <template v-for="item in navigation" :key="item.name">
                            <Link
                                v-if="item.href.startsWith('/')"
                                :href="item.href"
                                :class="[
                                    'font-medium transition-colors duration-300 hover:text-sky-500',
                                    isScrolled ? 'text-gray-900' : 'text-white'
                                ]"
                            >
                                {{ item.name }}
                            </Link>
                            <button
                                v-else
                                @click="scrollToSection(item.href)"
                                :class="[
                                    'font-medium transition-colors duration-300 hover:text-sky-500',
                                    isScrolled ? 'text-gray-900' : 'text-white'
                                ]"
                            >
                                {{ item.name }}
                            </button>
                        </template>
                        
                        <!-- Donate Button -->
                        <button class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Donate Now
                        </button>
                    </div>

                    <!-- Mobile menu button -->
                    <button
                        @click="showMobileMenu = !showMobileMenu"
                        :class="[
                            'md:hidden p-2 rounded-md transition-colors duration-300',
                            isScrolled ? 'text-gray-900' : 'text-white'
                        ]"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation -->
                <transition
                    enter-active-class="duration-200 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="duration-100 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div v-if="showMobileMenu" class="md:hidden">
                        <div class="px-2 pt-2 pb-3 space-y-1 bg-white rounded-lg shadow-lg mt-2">
                            <template v-for="item in navigation" :key="item.name">
                                <Link
                                    v-if="item.href.startsWith('/')"
                                    :href="item.href"
                                    class="block px-3 py-2 text-gray-900 font-medium hover:text-sky-500 transition-colors duration-300"
                                    @click="showMobileMenu = false"
                                >
                                    {{ item.name }}
                                </Link>
                                <button
                                    v-else
                                    @click="scrollToSection(item.href)"
                                    class="block w-full text-left px-3 py-2 text-gray-900 font-medium hover:text-sky-500 transition-colors duration-300"
                                >
                                    {{ item.name }}
                                </button>
                            </template>
                            <div class="px-3 py-2">
                                <button class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-full font-semibold transition-all duration-300">
                                    Donate Now
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>
            </nav>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Organization Info -->
                    <div class="col-span-1 lg:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <img 
                                src="/images/ustawilogo.jpeg" 
                                alt="Ustawi Wa Jamii Logo" 
                                class="h-12 w-12 rounded-full"
                            >
                            <div>
                                <h1 class="text-xl font-bold text-white">
                                    {{ settings.organization_name }}
                                </h1>
                                <p class="text-gray-400 text-sm">Est. 2024</p>
                            </div>
                        </div>
                        <p class="text-gray-300 mb-6 max-w-md">
                            {{ settings.organization_description }}
                        </p>
                        <div class="flex space-x-4">
                            <a v-if="settings.facebook_url" :href="settings.facebook_url" class="text-gray-400 hover:text-sky-400 transition-colors duration-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a v-if="settings.twitter_url" :href="settings.twitter_url" class="text-gray-400 hover:text-sky-400 transition-colors duration-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a v-if="settings.instagram_url" :href="settings.instagram_url" class="text-gray-400 hover:text-sky-400 transition-colors duration-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.33-1.297L3.897 16.3c.49.735 1.225 1.297 2.142 1.542.49.122.98.184 1.47.184 2.448 0 4.467-1.999 4.467-4.467s-2.019-4.467-4.467-4.467c-.49 0-.98.062-1.47.184-.917.245-1.652.807-2.142 1.542L4.619 8.82c.882-.807 2.033-1.297 3.33-1.297 2.693 0 4.896 2.203 4.896 4.896s-2.203 4.896-4.896 4.896zm7.598 0c-1.297 0-2.448-.49-3.33-1.297l-1.222.612c.49.735 1.225 1.297 2.142 1.542.49.122.98.184 1.47.184 2.448 0 4.467-1.999 4.467-4.467s-2.019-4.467-4.467-4.467c-.49 0-.98.062-1.47.184-.917.245-1.652.807-2.142 1.542l1.222.612c.882-.807 2.033-1.297 3.33-1.297 2.693 0 4.896 2.203 4.896 4.896s-2.203 4.896-4.896 4.896z"/>
                                </svg>
                            </a>
                            <a v-if="settings.linkedin_url" :href="settings.linkedin_url" class="text-gray-400 hover:text-sky-400 transition-colors duration-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><button @click="scrollToSection('#about')" class="text-gray-300 hover:text-sky-400 transition-colors duration-300">About Us</button></li>
                            <li><button @click="scrollToSection('#programs')" class="text-gray-300 hover:text-sky-400 transition-colors duration-300">Our Programs</button></li>
                            <li><button @click="scrollToSection('#impact')" class="text-gray-300 hover:text-sky-400 transition-colors duration-300">Our Impact</button></li>
                            <li><button @click="scrollToSection('#news')" class="text-gray-300 hover:text-sky-400 transition-colors duration-300">Latest News</button></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start space-x-2">
                                <svg class="h-5 w-5 text-sky-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-gray-300">{{ settings.physical_address }}</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="h-5 w-5 text-sky-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a :href="`mailto:${settings.contact_email}`" class="text-gray-300 hover:text-sky-400 transition-colors duration-300">{{ settings.contact_email }}</a>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="h-5 w-5 text-sky-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a :href="`tel:${settings.contact_phone}`" class="text-gray-300 hover:text-sky-400 transition-colors duration-300">{{ settings.contact_phone }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400">
                        © {{ new Date().getFullYear() }} {{ settings.organization_name }}. All rights reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-sky-400 transition-colors duration-300">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-sky-400 transition-colors duration-300">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>