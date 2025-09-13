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

// Toto Smart Move images slideshow
const totoImages = [
    'https://res.cloudinary.com/dliqth2su/image/upload/v1753966772/toto3_wlnuxp.jpg',
    'https://res.cloudinary.com/dliqth2su/image/upload/v1753966772/toto1_rer1px.jpg',
    'https://res.cloudinary.com/dliqth2su/image/upload/v1753966772/toto2_qxfph0.jpg'
];
const currentTotoImageIndex = ref(0);

// Start Toto Smart Move image slideshow
const startTotoSlideshow = () => {
    setInterval(() => {
        currentTotoImageIndex.value = (currentTotoImageIndex.value + 1) % totoImages.length;
    }, 3000); // Change image every 3 seconds
};

// Programs data - doubled for seamless loop
const basePrograms = [
    {
        id: 1,
        title: "Voice of the Silent Workers",
        icon: "🐴",
        image: "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670604/silent_workers_i2ycmh.jpg",
        color: "from-purple-500 to-purple-600",
        bgLight: "from-purple-50 to-purple-100",
        description: "An animal rights initiative advocating for the protection of donkeys and other working animals.",
        impact: "500+ animals protected"
    },
    {
        id: 2,
        title: "Community Paralegal Office",
        icon: "⚖️",
        image: "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670557/Community_Paralegal_ubccns.jpg",
        color: "from-blue-500 to-blue-600",
        bgLight: "from-blue-50 to-blue-100",
        description: "Legal awareness and support on land and inheritance rights for women and children.",
        impact: "300+ cases resolved"
    },
    {
        id: 3,
        title: "Harvest of Tomorrow",
        icon: "🌾",
        images: [
            "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670684/Harvest_of_tomorrow_copbsy.jpg",
            "https://i.imgur.com/WwnRTKy.jpeg",
            "https://i.imgur.com/kt0PBnb.jpeg",
            "https://i.imgur.com/JQ1BFn2.jpeg",
            "https://i.imgur.com/9AGbX9A.jpeg",
            "https://i.imgur.com/5E4fKAx.jpeg",
            "https://i.imgur.com/SWmiblK.jpeg"
        ],
        color: "from-green-500 to-green-600",
        bgLight: "from-green-50 to-green-100",
        description: "Agricultural program using Farmer Field School model for climate-smart farming.",
        impact: "6,000+ farmers trained"
    },
    {
        id: 4,
        title: "Toto Smart Move",
        icon: "🚸",
        image: totoImages[currentTotoImageIndex.value],
        color: "from-orange-500 to-orange-600",
        bgLight: "from-orange-50 to-orange-100",
        description: "Transport safety training for children aged 0-12 years.",
        impact: "2,000+ children trained"
    },
    {
        id: 5,
        title: "Waste To Art",
        icon: "🎨",
        image: "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670515/waste_to_art_wndver.jpg",
        color: "from-pink-500 to-pink-600",
        bgLight: "from-pink-50 to-pink-100",
        description: "Supporting young creatives in converting waste into art for environmental preservation.",
        impact: "100+ artists supported"
    },
    {
        id: 6,
        title: "Sanitary Pad Drives",
        icon: "🌸",
        image: "https://res.cloudinary.com/dliqth2su/image/upload/v1753993361/Sanitary_Pad_Drives_iuhljw.jpg",
        color: "from-red-500 to-red-600",
        bgLight: "from-red-50 to-red-100",
        description: "Distributing sanitary pads and educating girls on menstrual hygiene.",
        impact: "5,000+ girls reached"
    },
    {
        id: 7,
        title: "Tree Planting Campaigns",
        icon: "🌳",
        image: "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670896/tree_campaign_p0orad.jpg",
        color: "from-teal-500 to-teal-600",
        bgLight: "from-teal-50 to-teal-100",
        description: "Promoting agroforestry and biodiversity through tree planting initiatives.",
        impact: "10,000+ trees planted"
    },
    {
        id: 8,
        title: "Community Clean-up Campaigns",
        icon: "🧹",
        image: "/assets/Community Clean-up Campaigns.jpeg",
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
    
    // Start Toto Smart Move slideshow
    startTotoSlideshow();
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

// Team members data
const teamMembers = [
    {
        name: "Stephen Kiarie",
        role: "Chairperson & Program Development Lead",
        initials: "SK",
        color: "from-purple-500 to-purple-600"
    },
    {
        name: "Tumaini Kimathi Mutei",
        role: "Lead – Legal Empowerment & Policy Advocacy",
        initials: "TM",
        color: "from-blue-500 to-blue-600"
    },
    {
        name: "Raima Kochalle",
        role: "Lead – Nutrition, Gender & Community Resilience",
        initials: "RK",
        color: "from-green-500 to-green-600"
    },
    {
        name: "Ali Omar Dara",
        role: "Coordinator – Community Engagement & Gender Equity",
        initials: "AD",
        color: "from-orange-500 to-orange-600"
    },
    {
        name: "Kelvin Jacob Limasia Rotich",
        role: "Data & Social Impact Lead",
        initials: "KR",
        color: "from-teal-500 to-teal-600"
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
                                    <!-- Program Image -->
                                    <div class="relative h-48 overflow-hidden">
                                        <!-- Single Image -->
                                        <img 
                                            v-if="program.image"
                                            :src="program.image" 
                                            :alt="program.title"
                                            class="w-full h-full object-cover"
                                        />
                                        <!-- Multiple Images (for programs with image arrays) -->
                                        <img 
                                            v-else-if="program.images && program.images.length > 0"
                                            :src="program.images[0]" 
                                            :alt="program.title"
                                            class="w-full h-full object-cover"
                                        />
                                        <!-- Toto Smart Move Slideshow -->
                                        <div v-else-if="program.title === 'Toto Smart Move'">
                                            <img 
                                                v-for="(image, imgIndex) in totoImages" 
                                                :key="imgIndex"
                                                :src="image" 
                                                :alt="`Toto Smart Move ${imgIndex + 1}`"
                                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                                                :class="{ 'opacity-100': currentTotoImageIndex === imgIndex, 'opacity-0': currentTotoImageIndex !== imgIndex }"
                                            />
                                        </div>
                                        <!-- Video Preview for Voice of Silent Workers -->
                                        <div v-else-if="program.videoUrl" class="w-full h-full bg-purple-100 flex items-center justify-center">
                                            <div class="text-center">
                                                <span class="text-4xl">🎥</span>
                                                <p class="text-sm mt-2 text-gray-600">Video Available</p>
                                            </div>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="relative p-8">
                                        
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

            <!-- Team Leadership Section -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8 mt-32">
                <div class="text-center mb-16" :class="{ 'opacity-0 -translate-y-10': !isVisible, 'opacity-100 translate-y-0 transition-all duration-1000 delay-700': isVisible }">
                    <span class="inline-block text-4xl mb-6">👥</span>
                    <h3 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Our Leadership Team</h3>
                    <p class="text-xl text-gray-600">Meet the dedicated leaders driving our mission forward</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="(member, index) in teamMembers" 
                        :key="member.name"
                        class="group"
                        :class="{ 
                            'opacity-0 translate-y-10': !isVisible, 
                            'opacity-100 translate-y-0 transition-all duration-1000': isVisible 
                        }"
                        :style="{ transitionDelay: `${index * 100 + 800}ms` }"
                    >
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 group-hover:-translate-y-2 border border-gray-100 h-full flex flex-col">
                            <!-- Profile Picture -->
                            <div class="flex justify-center mb-6">
                                <div :class="`w-20 h-20 bg-gradient-to-br ${member.color} rounded-full flex items-center justify-center text-white text-2xl font-bold group-hover:scale-110 transition-transform duration-300 shadow-lg`">
                                    {{ member.initials }}
                                </div>
                            </div>
                            
                            <!-- Name and Role -->
                            <div class="text-center flex-1">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ member.name }}</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ member.role }}</p>
                            </div>
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
                        <p class="text-2xl mb-10 text-white/90">Join us in creating lasting impact in communities worldwide</p>
                        <div class="flex flex-col sm:flex-row gap-6 justify-center">
                            <Link 
                                href="/donate"
                                class="inline-block bg-white text-blue-600 px-10 py-5 rounded-full font-bold text-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-center"
                            >
                                Donate Now
                            </Link>
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