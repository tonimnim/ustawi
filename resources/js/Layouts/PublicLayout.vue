<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import NewsletterSignup from '@/Components/NewsletterSignup.vue';

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
    { name: 'Contact', href: '/contact' },
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
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <img 
                            src="/images/ustawilogo.jpeg" 
                            alt="Ustawi Wa Jamii Logo" 
                            class="h-10 w-10 sm:h-12 sm:w-12 rounded-full"
                        >
                        <div>
                            <h1 
                                :class="[
                                    'text-lg sm:text-xl font-bold transition-colors duration-300',
                                    isScrolled ? 'text-gray-900' : 'text-white'
                                ]"
                            >
                                <span class="hidden sm:inline">{{ settings.organization_name }}</span>
                                <span class="sm:hidden">Ustawi Wa Jamii</span>
                            </h1>
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
                        <Link href="/donate" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Donate Now
                        </Link>
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
                                <Link href="/donate" class="block w-full text-center bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-full font-semibold transition-all duration-300">
                                    Donate Now
                                </Link>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    <!-- Organization Info -->
                    <div>
                        <div class="flex items-center space-x-3 mb-4">
                            <img 
                                src="/images/ustawilogo.jpeg" 
                                alt="Ustawi Wa Jamii Logo" 
                                class="h-10 w-10 rounded-full"
                            >
                            <div>
                                <h1 class="text-lg font-bold text-white">
                                    {{ settings.organization_name }}
                                </h1>
                            </div>
                        </div>
                        <p class="text-gray-300 text-sm mb-4">
                            Empowering communities through sustainable development and youth leadership.
                        </p>
                        <!-- Social Media Icons -->
                        <div class="flex space-x-3">
                            <a href="#" class="w-8 h-8 bg-gray-800 hover:bg-sky-600 rounded flex items-center justify-center transition-colors duration-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-gray-800 hover:bg-sky-600 rounded flex items-center justify-center transition-colors duration-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-8 h-8 bg-gray-800 hover:bg-sky-600 rounded flex items-center justify-center transition-colors duration-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- What We Do -->
                    <div>
                        <h3 class="text-base font-semibold mb-4">What We Do</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">›</span>
                                <Link href="/programs?category=economic-empowerment" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Youth Economic Empowerment</Link>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">›</span>
                                <Link href="/programs?category=environmental-conservation" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Climate Action</Link>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">›</span>
                                <Link href="/programs?category=community-development" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Community Development</Link>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">›</span>
                                <Link href="/programs?category=rights-empowerment" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Rights & Legal Support</Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-base font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">✓</span>
                                <Link href="/" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Home</Link>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">✓</span>
                                <Link href="/programs" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Our Programs</Link>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">✓</span>
                                <Link href="/contact" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Contact Us</Link>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">✓</span>
                                <Link href="/#news" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Latest News</Link>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-orange-500">✓</span>
                                <Link href="/careers" class="text-gray-300 hover:text-orange-500 transition-colors duration-300 text-sm">Careers</Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div>
                        <h3 class="text-base font-semibold mb-4">Newsletter</h3>
                        <p class="text-gray-300 text-sm mb-4">
                            Get updates on our latest programs and community initiatives.
                        </p>
                        <NewsletterSignup variant="inline" />
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