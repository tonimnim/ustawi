<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    settings: Object,
});

// Animation states
const isVisible = ref(false);

// Intersection observer for animations
const observerCallback = (entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            isVisible.value = true;
        }
    });
};

let observer = null;

onMounted(() => {
    observer = new IntersectionObserver(observerCallback, {
        threshold: 0.1
    });
    
    const section = document.querySelector('#about');
    if (section) {
        observer.observe(section);
    }
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
});
</script>

<template>
    <section id="about" class="relative overflow-hidden">
        <!-- Main background -->
        <div class="relative bg-white py-20">
            <!-- Decorative elements -->
            <div class="absolute top-10 left-10 w-72 h-72 bg-blue-200/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-green-200/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16" :class="{ 'opacity-0 translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000': isVisible }">
                    <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                        Building Stronger Communities
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-500 mt-2">
                            Together
                        </span>
                    </h2>
                    <div class="max-w-3xl mx-auto">
                        <p class="text-xl text-gray-600">We are a community-driven organization dedicated to creating sustainable development and empowering youth across Kenya through innovative programs and collective action.</p>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="space-y-16">
                    <!-- Mission, Vision & Objectives Grid -->
                    <div class="grid lg:grid-cols-3 gap-8" :class="{ 'opacity-0 translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000 delay-300': isVisible }">
                        <!-- Mission Card -->
                        <div class="group relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-8 h-full shadow-lg hover:shadow-2xl transition-all duration-300 border border-blue-100">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 ml-4">Our Mission</h3>
                                </div>
                                <p class="text-gray-600 leading-relaxed">{{ settings.mission_statement }}</p>
                            </div>
                        </div>

                        <!-- Vision Card -->
                        <div class="group relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-8 h-full shadow-lg hover:shadow-2xl transition-all duration-300 border border-green-100">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 ml-4">Our Vision</h3>
                                </div>
                                <p class="text-gray-600 leading-relaxed">A Kenya where every community has access to sustainable development opportunities, where youth are empowered as leaders of change, and where collective action creates lasting positive impact for generations to come.</p>
                            </div>
                        </div>

                        <!-- Objectives Card -->
                        <div class="group relative lg:row-span-2">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-8 h-full shadow-lg hover:shadow-2xl transition-all duration-300 border border-purple-100">
                                <div class="flex items-center mb-6">
                                    <span class="text-3xl mr-3">🎯</span>
                                    <h3 class="text-2xl font-bold text-gray-900">Our Objectives</h3>
                                </div>
                                <ul class="space-y-4">
                                    <li class="flex items-start">
                                        <div class="w-2 h-2 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <p class="text-gray-600">Promote climate-smart and sustainable agricultural practices.</p>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-2 h-2 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <p class="text-gray-600">Increase access to legal services for vulnerable and marginalized populations.</p>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-2 h-2 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <p class="text-gray-600">Enhance youth and women's economic participation through agribusiness and social innovation.</p>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-2 h-2 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <p class="text-gray-600">Strengthen community-led climate and environmental action.</p>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-2 h-2 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <p class="text-gray-600">Facilitate access to basic education, sanitation, and dignity for underserved communities.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Core Values Card -->
                        <div class="lg:col-span-2 group relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-8 h-full shadow-lg hover:shadow-2xl transition-all duration-300 border border-teal-100">
                                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">Our Core Values</h3>
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="text-center group/item">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover/item:scale-110 transition-transform duration-300">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Sustainability</h4>
                                        <p class="text-sm text-gray-600">Long-term solutions</p>
                                    </div>
                                    <div class="text-center group/item">
                                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover/item:scale-110 transition-transform duration-300">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Empowerment</h4>
                                        <p class="text-sm text-gray-600">Building leaders</p>
                                    </div>
                                    <div class="text-center group/item">
                                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover/item:scale-110 transition-transform duration-300">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Innovation</h4>
                                        <p class="text-sm text-gray-600">Creative solutions</p>
                                    </div>
                                    <div class="text-center group/item">
                                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover/item:scale-110 transition-transform duration-300">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Integrity</h4>
                                        <p class="text-sm text-gray-600">Transparency</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</template>