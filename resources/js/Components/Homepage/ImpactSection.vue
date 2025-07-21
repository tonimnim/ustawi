<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Counter animation state
const isVisible = ref(false);
const counters = ref({
    beneficiaries: 0,
    projects: 0,
    counties: 0,
    years: 0
});

// Target values
const targets = {
    beneficiaries: 1250,
    projects: 45,
    counties: 12,
    years: 2
};

// Animation function
const animateCounters = () => {
    const duration = 2000; // 2 seconds
    const steps = 60;
    const stepDuration = duration / steps;
    
    let currentStep = 0;
    
    const interval = setInterval(() => {
        currentStep++;
        const progress = currentStep / steps;
        
        // Easing function (ease-out)
        const easeOut = 1 - Math.pow(1 - progress, 3);
        
        counters.value.beneficiaries = Math.floor(targets.beneficiaries * easeOut);
        counters.value.projects = Math.floor(targets.projects * easeOut);
        counters.value.counties = Math.floor(targets.counties * easeOut);
        counters.value.years = Math.floor(targets.years * easeOut);
        
        if (currentStep >= steps) {
            clearInterval(interval);
            // Ensure final values are set
            counters.value = { ...targets };
        }
    }, stepDuration);
};

// Intersection observer for triggering animation
const observerCallback = (entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !isVisible.value) {
            isVisible.value = true;
            animateCounters();
        }
    });
};

let observer = null;

onMounted(() => {
    observer = new IntersectionObserver(observerCallback, {
        threshold: 0.3
    });
    
    const section = document.querySelector('#impact');
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
    <section id="impact" class="relative py-12 sm:py-16 lg:py-24 bg-gradient-to-br from-blue-600 via-sky-500 to-blue-400 overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-48 h-48 sm:w-72 sm:h-72 lg:w-96 lg:h-96 bg-white/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-48 h-48 sm:w-72 sm:h-72 lg:w-96 lg:h-96 bg-white/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.2) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-10 sm:mb-12 lg:mb-16" :class="{ 'opacity-0 -translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000': isVisible }">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4">
                    Our <span class="text-yellow-300">Impact</span>
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-white/90 max-w-3xl mx-auto px-4">
                    Measuring success through the lives we've touched and communities we've transformed
                </p>
            </div>

            <!-- Impact Statistics -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-10 sm:mb-12 lg:mb-16">
                <!-- Beneficiaries Served -->
                <div class="group" :class="{ 'opacity-0 translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000': isVisible }">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 h-full transition-all duration-300 hover:bg-white/30 hover:scale-105 hover:shadow-2xl">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 bg-white/30 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 lg:mb-6 group-hover:rotate-6 transition-transform duration-300">
                            <svg class="w-8 h-8 sm:w-9 sm:h-9 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold text-white mb-1 sm:mb-2 lg:mb-3">{{ counters.beneficiaries.toLocaleString() }}+</div>
                        <div class="text-white text-sm sm:text-base lg:text-lg font-semibold">Beneficiaries</div>
                        <div class="text-white/80 text-xs sm:text-sm hidden sm:block">Across all programs</div>
                    </div>
                </div>

                <!-- Projects Completed -->
                <div class="group" :class="{ 'opacity-0 translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000 delay-100': isVisible }">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-8 h-full transition-all duration-300 hover:bg-white/30 hover:scale-105 hover:shadow-2xl">
                        <div class="w-20 h-20 bg-white/30 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-6 transition-transform duration-300">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="text-4xl lg:text-5xl font-bold text-white mb-3">{{ counters.projects }}+</div>
                        <div class="text-white text-lg font-semibold mb-1">Projects Completed</div>
                        <div class="text-white/80 text-sm">Successfully implemented</div>
                    </div>
                </div>

                <!-- Counties Reached -->
                <div class="group" :class="{ 'opacity-0 translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000 delay-200': isVisible }">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-8 h-full transition-all duration-300 hover:bg-white/30 hover:scale-105 hover:shadow-2xl">
                        <div class="w-20 h-20 bg-white/30 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-6 transition-transform duration-300">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="text-4xl lg:text-5xl font-bold text-white mb-3">{{ counters.counties }}</div>
                        <div class="text-white text-lg font-semibold mb-1">Counties Reached</div>
                        <div class="text-white/80 text-sm">Across Kenya</div>
                    </div>
                </div>

                <!-- Years Active -->
                <div class="group" :class="{ 'opacity-0 translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000 delay-300': isVisible }">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-8 h-full transition-all duration-300 hover:bg-white/30 hover:scale-105 hover:shadow-2xl">
                        <div class="w-20 h-20 bg-white/30 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-6 transition-transform duration-300">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-4xl lg:text-5xl font-bold text-white mb-3">{{ counters.years }}+</div>
                        <div class="text-white text-lg font-semibold mb-1">Years Active</div>
                        <div class="text-white/80 text-sm">Making a difference</div>
                    </div>
                </div>
            </div>

            <!-- Impact Highlights -->
            <div class="mb-10 sm:mb-12 lg:mb-16">
                <h3 class="text-2xl sm:text-3xl font-bold text-center text-white mb-8 sm:mb-10 lg:mb-12">Impact Highlights</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Youth Leadership -->
                    <div class="bg-white rounded-xl p-6 sm:p-8 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="h-32 sm:h-40 lg:h-48 bg-gradient-to-br from-sky-400 to-sky-600 rounded-lg flex items-center justify-center mb-4 sm:mb-6">
                            <svg class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h4 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Youth Leadership</h4>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Empowering young leaders through capacity building and leadership training programs.</p>
                        <div class="text-sky-500 font-semibold text-sm sm:text-base">500+ Youth Trained</div>
                    </div>

                    <!-- Community Development -->
                    <div class="bg-white rounded-xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Community Development</h4>
                        <p class="text-gray-600 mb-4">Implementing sustainable development projects that create lasting change in communities.</p>
                        <div class="text-green-500 font-semibold">30+ Communities</div>
                    </div>

                    <!-- Innovation Hub -->
                    <div class="bg-white rounded-xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Innovation Hub</h4>
                        <p class="text-gray-600 mb-4">Fostering innovation and creative solutions to address community challenges.</p>
                        <div class="text-purple-500 font-semibold">15+ Innovations</div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                <div class="bg-gradient-to-r from-sky-500 to-sky-600 rounded-xl sm:rounded-2xl p-8 sm:p-10 lg:p-12 text-white">
                    <h3 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">Be Part of the Change</h3>
                    <p class="text-base sm:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90 px-4 sm:px-0">Join us in creating sustainable impact in communities across Kenya</p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                        <Link href="/donate" class="bg-white text-sky-600 px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-block text-center text-sm sm:text-base">
                            Donate Now
                        </Link>
                        <button class="border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold hover:bg-white hover:text-sky-600 transition-all duration-300 text-sm sm:text-base">
                            Volunteer With Us
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>