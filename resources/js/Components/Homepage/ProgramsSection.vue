<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Animation states
const isVisible = ref(false);
const isPaused = ref(false);
const carouselPosition = ref(0);
const animationId = ref(null);

// Programs data - doubled for seamless loop
const basePrograms = [
    {
        id: 1,
        title: "Voice of the Silent Workers",
        icon: "🐴",
        color: "from-purple-500 to-purple-600",
        bgLight: "from-purple-50 to-purple-100",
        description: "An animal rights initiative advocating for the protection of donkeys and other working animals.",
        impact: "500+ animals protected"
    },
    {
        id: 2,
        title: "Community Paralegal Office",
        icon: "⚖️",
        color: "from-blue-500 to-blue-600",
        bgLight: "from-blue-50 to-blue-100",
        description: "Legal awareness and support on land and inheritance rights for women and children.",
        impact: "300+ cases resolved"
    },
    {
        id: 3,
        title: "Harvest of Tomorrow",
        icon: "🌾",
        color: "from-green-500 to-green-600",
        bgLight: "from-green-50 to-green-100",
        description: "Agricultural program using Farmer Field School model for climate-smart farming.",
        impact: "6,000+ farmers trained"
    },
    {
        id: 4,
        title: "Toto Smart Move",
        icon: "🚸",
        color: "from-orange-500 to-orange-600",
        bgLight: "from-orange-50 to-orange-100",
        description: "Transport safety training for children aged 0-12 years.",
        impact: "2,000+ children trained"
    },
    {
        id: 5,
        title: "Waste To Art",
        icon: "🎨",
        color: "from-pink-500 to-pink-600",
        bgLight: "from-pink-50 to-pink-100",
        description: "Supporting young creatives in converting waste into art for environmental preservation.",
        impact: "100+ artists supported"
    },
    {
        id: 6,
        title: "Sanitary Pad Drives",
        icon: "🌸",
        color: "from-red-500 to-red-600",
        bgLight: "from-red-50 to-red-100",
        description: "Distributing sanitary pads and educating girls on menstrual hygiene.",
        impact: "5,000+ girls reached"
    },
    {
        id: 7,
        title: "Tree Planting Campaigns",
        icon: "🌳",
        color: "from-teal-500 to-teal-600",
        bgLight: "from-teal-50 to-teal-100",
        description: "Promoting agroforestry and biodiversity through tree planting initiatives.",
        impact: "10,000+ trees planted"
    },
    {
        id: 8,
        title: "Community Clean-up Campaigns",
        icon: "🧹",
        color: "from-indigo-500 to-indigo-600",
        bgLight: "from-indigo-50 to-indigo-100",
        description: "Regular clean-up drives in informal settlements and marketplaces.",
        impact: "50+ clean-ups organized"
    }
];

// Double the programs for seamless loop
const programs = ref([...basePrograms, ...basePrograms]);

// Animation function
const animate = () => {
    if (!isPaused.value) {
        carouselPosition.value -= 1; // Move 1 pixel at a time for smooth movement
        
        // Reset position when first set of 8 cards is completely out of view
        // Each card is 384px (w-96) + 24px gap = 408px
        // 8 cards = 3264px total
        if (carouselPosition.value <= -3264) {
            carouselPosition.value = 0;
        }
    }
    animationId.value = requestAnimationFrame(animate);
};

// Start/stop animation
const startAnimation = () => {
    animate();
};

const stopAnimation = () => {
    if (animationId.value) {
        cancelAnimationFrame(animationId.value);
    }
};

// Pause/resume carousel
const pauseCarousel = () => {
    isPaused.value = true;
};

const resumeCarousel = () => {
    isPaused.value = false;
};

// Intersection observer
const observerCallback = (entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            isVisible.value = true;
            startAnimation();
        }
    });
};

let observer = null;

onMounted(() => {
    observer = new IntersectionObserver(observerCallback, {
        threshold: 0.1
    });
    
    const section = document.querySelector('#programs');
    if (section) {
        observer.observe(section);
    }
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
    stopAnimation();
});

// Get program slug from title
const getProgramSlug = (title) => {
    const slugMap = {
        "Voice of the Silent Workers": "voice-of-silent-workers",
        "Community Paralegal Office": "community-paralegal",
        "Harvest of Tomorrow": "harvest-of-tomorrow",
        "Toto Smart Move": "toto-smart-move",
        "Waste To Art": "waste-to-art",
        "Sanitary Pad Drives": "sanitary-pad-drives",
        "Tree Planting Campaigns": "tree-planting",
        "Community Clean-up Campaigns": "community-cleanup"
    };
    return slugMap[title] || "";
};

// County coordinators data
const coordinators = [
    {
        name: "Stephen Kiarie Kimani",
        county: "Nairobi",
        email: "stephenkiarie@ustawiwajamii.org",
        initials: "SK"
    },
    {
        name: "Raima Kochalle",
        county: "Marsabit",
        email: "raima@ustawiwajamii.org",
        initials: "RK"
    },
    {
        name: "Ali Omar Dara",
        county: "Mombasa",
        email: "ali.dara@ustawiwajamii.org",
        initials: "AD"
    },
    {
        name: "Tumaini Kimathi",
        county: "Kirinyaga",
        email: "tumaini@ustawiwajamii.org",
        initials: "TK"
    },
    {
        name: "Kelvin Limasia Rotich",
        county: "West Pokot",
        email: "kelvin@ustawiwajamii.org",
        initials: "KR"
    }
];
</script>

<template>
    <section id="programs" class="py-32 bg-white relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-to-b from-gray-50/50 to-white"></div>
        
        <div class="relative z-10">
            <!-- Section Header -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center mb-20" :class="{ 'opacity-0 -translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000': isVisible }">
                <h2 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    What We Do
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Transforming communities through innovative programs that address real challenges and create lasting impact
                </p>
            </div>

            <!-- Programs Carousel -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8" :class="{ 'opacity-0': !isVisible, 'opacity-100 transition-opacity duration-1000 delay-500': isVisible }">
                <div class="relative">
                    <!-- Gradient overlays for smooth edges -->
                    <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
                    
                    <!-- Carousel Container with fixed viewport showing only 4 cards -->
                    <div 
                        class="overflow-hidden mx-auto"
                        style="max-width: 1608px"
                        @mouseenter="pauseCarousel"
                        @mouseleave="resumeCarousel"
                    >
                        <div 
                            class="flex gap-6 py-8 transition-transform duration-300 ease-linear"
                            :style="{ transform: `translateX(${carouselPosition}px)` }"
                        >
                            <div 
                                v-for="(program, index) in programs" 
                                :key="`${program.id}-${index}`"
                                class="group flex-shrink-0 w-96"
                            >
                                <Link 
                                    :href="`/programs?program=${getProgramSlug(program.title)}`"
                                    class="block relative h-full"
                                >
                                <!-- Card -->
                                <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 h-full overflow-hidden group-hover:scale-105">
                                    <!-- Background gradient -->
                                    <div :class="`absolute inset-0 bg-gradient-to-br ${program.bgLight} opacity-50`"></div>
                                    
                                    <!-- Content -->
                                    <div class="relative p-8">
                                        <!-- Icon -->
                                        <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">
                                            {{ program.icon }}
                                        </div>
                                        
                                        <!-- Title -->
                                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                                            {{ program.title }}
                                        </h3>
                                        
                                        <!-- Description -->
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                            {{ program.description }}
                                        </p>
                                        
                                        <!-- Impact -->
                                        <div class="mb-4">
                                            <span :class="`text-base font-bold bg-gradient-to-r ${program.color} bg-clip-text text-transparent`">
                                                {{ program.impact }}
                                            </span>
                                        </div>
                                        
                                        <!-- Learn More Button - Hidden by default, shown on hover -->
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <Link 
                                                :href="`/programs?program=${getProgramSlug(program.title)}`"
                                                :class="`block w-full py-3 bg-gradient-to-r ${program.color} text-white rounded-lg font-semibold transform hover:scale-105 transition-all duration-300 shadow-lg text-sm text-center`"
                                            >
                                                Learn More →
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- County Coordinators Section -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8 mt-32">
                <div class="text-center mb-16" :class="{ 'opacity-0 -translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000 delay-700': isVisible }">
                    <span class="inline-block text-4xl mb-6">🗺️</span>
                    <h3 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">County Project Coordinators</h3>
                    <p class="text-xl text-gray-600">Our dedicated team leaders across Kenya</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <div 
                        v-for="(coordinator, index) in coordinators" 
                        :key="coordinator.email"
                        class="group"
                        :class="{ 
                            'opacity-0 translate-y-10': !isVisible, 
                            'opacity-100 translate-y-0 transition-all duration-1000': isVisible 
                        }"
                        :style="{ transitionDelay: `${index * 100 + 800}ms` }"
                    >
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 p-10 group-hover:-translate-y-2 border border-gray-100">
                            <div class="flex items-center mb-8">
                                <!-- Profile Picture -->
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold mr-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    {{ coordinator.initials }}
                                </div>
                                <div>
                                    <h4 class="text-2xl font-bold text-gray-900">{{ coordinator.name }}</h4>
                                    <p class="text-blue-600 font-semibold text-lg">{{ coordinator.county }} County</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-6 h-6 mr-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <a :href="`mailto:${coordinator.email}`" class="hover:text-blue-600 transition-colors duration-300">
                                        {{ coordinator.email }}
                                    </a>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-6 h-6 mr-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ coordinator.county }}, Kenya</span>
                                </div>
                            </div>

                            <button class="mt-8 w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                Contact Coordinator
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom CTA -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8 mt-24" :class="{ 'opacity-0': !isVisible, 'opacity-100 transition-opacity duration-1000 delay-1000': isVisible }">
                <div class="bg-gradient-to-r from-blue-600 via-teal-600 to-green-600 rounded-3xl p-16 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 text-center">
                        <h3 class="text-4xl font-bold mb-6">Want to Support Our Programs?</h3>
                        <p class="text-2xl mb-10 text-white/90">Join us in creating lasting impact in communities across Kenya</p>
                        <div class="flex flex-col sm:flex-row gap-6 justify-center">
                            <button class="bg-white text-blue-600 px-10 py-5 rounded-full font-bold text-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                Donate Now
                            </button>
                            <Link 
                                href="/programs"
                                class="inline-block border-2 border-white text-white px-10 py-5 rounded-full font-bold text-lg hover:bg-white hover:text-blue-600 transition-all duration-300 text-center"
                            >
                                View All Programs
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>