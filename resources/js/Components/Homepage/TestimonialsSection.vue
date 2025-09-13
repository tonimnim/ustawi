<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    settings: Object,
});

// Animation state
const isPaused = ref(false);
const carouselPosition = ref(0);
const animationId = ref(null);

// Success stories data
const successStories = [
    {
        id: 1,
        image: "/assets/Economic Empowerment & Food Security1.jpeg",
        title: "Empowering Farmers",
        story: "Through our Harvest of Tomorrow program, Mary Wanjiru increased her crop yield by 40% using climate-smart techniques.",
        location: "Kirinyaga County"
    },
    {
        id: 2,
        image: "/assets/youth education.jpeg",
        title: "Youth Education Impact",
        story: "Over 2,000 children trained in road safety through Toto Smart Move, reducing accidents by 30% in target areas.",
        location: "Nairobi County"
    },
    {
        id: 3,
        image: "/assets/treeplanting.JPG",
        title: "Environmental Champions",
        story: "Communities planted 10,000+ trees, creating green spaces and improving air quality for future generations.",
        location: "Multiple Counties"
    },
    {
        id: 4,
        image: "/assets/Community Clean-up Campaigns.jpeg",
        title: "Cleaner Communities",
        story: "50+ clean-up campaigns organized, transforming informal settlements into healthier living spaces.",
        location: "Mombasa County"
    },
    {
        id: 5,
        image: "/assets/Economic Empowerment & Food Security2.jpeg",
        title: "Legal Justice Delivered",
        story: "300+ land disputes resolved through our Community Paralegal Office, securing rights for vulnerable families.",
        location: "West Pokot County"
    },
    {
        id: 6,
        image: "/assets/Economic Empowerment & Food Security3.jpeg",
        title: "Artistic Expression",
        story: "100+ young artists supported through Waste to Art, turning recycled materials into income-generating crafts.",
        location: "Marsabit County"
    }
];

// Double the stories for seamless loop
const stories = ref([...successStories, ...successStories]);

// Animation function
const animate = () => {
    if (!isPaused.value) {
        carouselPosition.value -= 0.5; // Slower movement
        
        // Reset when first set is scrolled out
        const cardWidth = 400; // Approximate width of each card
        const totalWidth = successStories.length * cardWidth;
        if (Math.abs(carouselPosition.value) >= totalWidth) {
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

onMounted(() => {
    startAnimation();
});

onUnmounted(() => {
    stopAnimation();
});
</script>

<template>
    <section class="py-8 bg-gradient-to-b from-white to-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <!-- Section Header -->
            <div class="text-center">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Success Stories
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Real impact, real people, real change. These are the stories of transformation from communities worldwide.
                </p>
            </div>
        </div>

        <!-- Scrolling Carousel -->
        <div 
            class="relative"
            @mouseenter="pauseCarousel"
            @mouseleave="resumeCarousel"
        >
            <!-- Gradient overlays -->
            <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white via-white/50 to-transparent z-10"></div>
            <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white via-white/50 to-transparent z-10"></div>
            
            <!-- Stories Container -->
            <div class="flex transition-transform duration-300 ease-linear"
                 :style="{ transform: `translateX(${carouselPosition}px)` }">
                <div 
                    v-for="(story, index) in stories" 
                    :key="`${story.id}-${index}`"
                    class="flex-shrink-0 w-96 mx-4"
                >
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                        <!-- Image -->
                        <div class="h-56 overflow-hidden">
                            <img 
                                :src="story.image" 
                                :alt="story.title"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                            />
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ story.title }}</h3>
                            <p class="text-gray-600 mb-4">{{ story.story }}</p>
                            <div class="flex items-center text-sm text-blue-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ story.location }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Summary -->
        <div class="max-w-4xl mx-auto mt-16 px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-600 to-teal-600 rounded-2xl p-8 text-white text-center">
                <h3 class="text-2xl font-bold mb-4">Together, We're Making a Difference</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div>
                        <p class="text-3xl font-bold">1,250+</p>
                        <p class="text-sm">Lives Transformed</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">45+</p>
                        <p class="text-sm">Projects Completed</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">12</p>
                        <p class="text-sm">Counties Reached</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">100%</p>
                        <p class="text-sm">Commitment</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>