<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Carousel state
const currentImageIndex = ref(0);
const imageInterval = ref(null);
const isLoading = ref(false);
const loadedImages = ref(new Set());
const isPaused = ref(false);
const isTransitioning = ref(false);

// Text animation state
const currentTextIndex = ref(0);
const textInterval = ref(null);
const descriptions = [
    "Building sustainable futures through youth leadership, innovation, and community-driven development",
    "that creates lasting positive change."
];

// Computed
const hasImages = computed(() => props.settings?.homepage_images && props.settings.homepage_images.length > 0);
const imageCount = computed(() => props.settings?.homepage_images?.length || 0);

// Preload images
const preloadImages = () => {
    if (!hasImages.value) {
        isLoading.value = false;
        return;
    }
    
    let loadedCount = 0;
    const totalImages = props.settings.homepage_images.length;
    
    props.settings.homepage_images.forEach((image, index) => {
        const img = new Image();
        const imageUrl = image.url || `/storage/${image.path || 'homepage/' + image.filename}`;
        
        img.onload = () => {
            loadedCount++;
            loadedImages.value.add(index);
            if (loadedCount === totalImages) {
                isLoading.value = false;
            }
        };
        img.onerror = (error) => {
            loadedCount++;
            if (loadedCount === totalImages) {
                isLoading.value = false;
            }
        };
        img.src = imageUrl;
    });
    
    // Fallback timeout
    setTimeout(() => {
        if (isLoading.value) {
            isLoading.value = false;
        }
    }, 5000);
};

// Start image carousel
const startImageCarousel = () => {
    if (hasImages.value && imageCount.value > 1 && !isPaused.value) {
        imageInterval.value = setInterval(() => {
            nextImage();
        }, 6000);
    }
};

// Stop carousel
const stopImageCarousel = () => {
    if (imageInterval.value) {
        clearInterval(imageInterval.value);
        imageInterval.value = null;
    }
};

// Next image with transition
const nextImage = () => {
    if (isTransitioning.value) return;
    
    isTransitioning.value = true;
    currentImageIndex.value = (currentImageIndex.value + 1) % imageCount.value;
    
    setTimeout(() => {
        isTransitioning.value = false;
    }, 3000);
};

// Go to specific image
const goToImage = (index) => {
    if (index === currentImageIndex.value || isTransitioning.value) return;
    
    isTransitioning.value = true;
    setTimeout(() => {
        currentImageIndex.value = index;
        stopImageCarousel();
        setTimeout(() => {
            isTransitioning.value = false;
            startImageCarousel();
        }, 300);
    }, 300);
};

// Start text rotation
const startTextRotation = () => {
    textInterval.value = setInterval(() => {
        currentTextIndex.value = (currentTextIndex.value + 1) % descriptions.length;
    }, 3000);
};

// Stop text rotation
const stopTextRotation = () => {
    if (textInterval.value) {
        clearInterval(textInterval.value);
        textInterval.value = null;
    }
};

// Pause/resume on hover
const pauseCarousel = () => {
    isPaused.value = true;
    stopImageCarousel();
};

const resumeCarousel = () => {
    isPaused.value = false;
    startImageCarousel();
};

onMounted(() => {
    preloadImages();
    startImageCarousel();
    startTextRotation();
});

onUnmounted(() => {
    stopImageCarousel();
    stopTextRotation();
});

// Watch for loading complete
watch(isLoading, (newValue) => {
    if (!newValue) {
        startImageCarousel();
    }
});
</script>

<template>
    <!-- Hero Section -->
    <section class="relative min-h-screen overflow-hidden">
        
        <!-- Full-screen Background Images -->
        <div class="absolute inset-0">
            <!-- Image Carousel Background -->
            <div v-if="hasImages" class="absolute inset-0">
                <div 
                    v-for="(image, index) in settings.homepage_images"
                    :key="image.id || index"
                    class="absolute inset-0 transition-opacity duration-[3000ms] ease-in-out"
                    :class="{
                        'opacity-100': index === currentImageIndex,
                        'opacity-0': index !== currentImageIndex
                    }"
                >
                    <img 
                        :src="image.url || `/storage/${image.path || 'homepage/' + image.filename}`"
                        :alt="`${settings.organization_name} - Image ${index + 1}`"
                        class="w-full h-full object-cover"
                    />
                </div>
            </div>
            
            <!-- Fallback gradient when no images -->
            <div v-else class="absolute inset-0 bg-gradient-to-br from-blue-600 via-teal-500 to-green-400"></div>
            
            <!-- Dark overlay for text readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/50"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 w-full flex items-center justify-center min-h-screen py-20">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    
                    <!-- Left Content -->
                    <div class="text-white space-y-6 lg:mt-20">
                        <!-- Main Heading -->
                        <div class="space-y-4">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                                <span class="block">Empowering</span>
                                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400">
                                    Communities
                                </span>
                                <span class="block">Worldwide</span>
                            </h1>
                            
                            <!-- Animated Description -->
                            <div class="relative h-16 md:h-20 overflow-hidden">
                                <div 
                                    v-for="(text, index) in descriptions"
                                    :key="index"
                                    class="absolute inset-0 flex items-center transition-all duration-1000 ease-in-out"
                                    :class="{
                                        'opacity-100 transform translate-y-0': index === currentTextIndex,
                                        'opacity-0 transform -translate-y-full': index < currentTextIndex || (currentTextIndex === 0 && index === descriptions.length - 1),
                                        'opacity-0 transform translate-y-full': index > currentTextIndex && !(currentTextIndex === descriptions.length - 1 && index === 0)
                                    }"
                                >
                                    <p class="text-lg md:text-xl text-white/90 leading-relaxed max-w-xl">
                                        {{ text }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Call-to-Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <Link href="/donate" class="group relative px-6 py-2.5 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-full font-medium text-sm shadow-lg hover:shadow-orange-500/25 transform hover:-translate-y-0.5 transition-all duration-300 inline-block text-center">
                                <span class="relative z-10">Donate Now</span>
                            </Link>
                            <a href="#about" class="group px-6 py-2.5 border border-white/40 text-white rounded-full font-medium text-sm backdrop-blur-sm bg-white/5 hover:bg-white/10 hover:border-white/60 transition-all duration-300 inline-block text-center">
                                Learn Our Story
                                <svg class="inline-block ml-1.5 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right side - empty space for balance -->
                    <div class="hidden lg:block"></div>
                </div>
            </div>
        </div>

        <!-- Image Navigation (if multiple images) -->
        <div v-if="hasImages && imageCount > 1" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
            <div class="flex items-center space-x-3">
                <button 
                    v-for="(image, index) in settings.homepage_images"
                    :key="index"
                    @click="goToImage(index)"
                    class="group relative"
                >
                    <div 
                        :class="[
                            'transition-all duration-500',
                            index === currentImageIndex 
                                ? 'w-12 h-2 bg-white rounded-full' 
                                : 'w-2 h-2 bg-white/50 hover:bg-white/70 rounded-full'
                        ]"
                    ></div>
                </button>
            </div>
        </div>

        <!-- Progress Bar -->
        <div v-if="hasImages && imageCount > 1" class="absolute bottom-0 left-0 right-0 h-1 bg-black/20 z-20">
            <div 
                class="h-full bg-gradient-to-r from-orange-500 to-red-600 transition-all duration-300"
                :style="{ 
                    width: ((currentImageIndex + 1) / imageCount * 100) + '%',
                    transition: isTransitioning ? 'none' : 'width 5s linear'
                }"
            ></div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 text-white/70 animate-bounce z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>
</template>

<style scoped>
/* Base responsive adjustments */
section {
    min-height: 100vh;
    min-height: 100dvh;
}

/* Mobile specific adjustments */
@media (max-width: 640px) {
    section {
        min-height: 100vh;
        min-height: 100dvh;
    }
}

/* Landscape mobile adjustments */
@media (max-height: 500px) and (orientation: landscape) {
    section {
        min-height: auto;
    }
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* Prevent layout shift during loading */
img {
    will-change: transform;
}

/* Animation for bounce */
@keyframes bounce {
    0%, 100% {
        transform: translateX(-50%) translateY(0);
    }
    50% {
        transform: translateX(-50%) translateY(-25%);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
}

/* Text slide animation */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-100%);
    }
}
</style>