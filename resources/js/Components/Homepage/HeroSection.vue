<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Carousel state
const currentImageIndex = ref(0);
const imageInterval = ref(null);
const isLoading = ref(false); // Changed to false by default
const loadedImages = ref(new Set());
const isPaused = ref(false);
const isTransitioning = ref(false);


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
        const imageUrl = `/media/${image.path || 'homepage/' + image.filename}`;
        
        img.onload = () => {
            loadedCount++;
            loadedImages.value.add(index);
            if (loadedCount === totalImages) {
                isLoading.value = false;
            }
        };
        img.onerror = (error) => {
            loadedCount++;
            // Still mark as loaded to prevent infinite loading
            if (loadedCount === totalImages) {
                isLoading.value = false;
            }
        };
        img.src = imageUrl;
    });
    
    // Fallback timeout to prevent infinite loading
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
        }, 6000); // 6 seconds to allow for full animation
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
    }, 3000); // Match the CSS transition duration
};

// Go to specific image
const goToImage = (index) => {
    if (index === currentImageIndex.value || isTransitioning.value) return;
    
    isTransitioning.value = true;
    setTimeout(() => {
        currentImageIndex.value = index;
        // Reset interval
        stopImageCarousel();
        setTimeout(() => {
            isTransitioning.value = false;
            startImageCarousel();
        }, 300);
    }, 300);
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
});

onUnmounted(() => {
    stopImageCarousel();
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
    <section class="relative min-h-screen flex items-center bg-gradient-to-br from-blue-600 via-teal-500 to-green-400 overflow-hidden lg:max-h-screen">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.4) 1px, transparent 0); background-size: 20px 20px;"></div>
        </div>
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/30 via-transparent to-green-300/20"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-10 right-10 md:top-20 md:right-20 w-20 h-20 md:w-32 md:h-32 bg-green-300/30 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute bottom-10 left-10 md:bottom-20 md:left-20 w-24 h-24 md:w-40 md:h-40 bg-blue-400/30 rounded-full blur-xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 right-1/4 md:right-1/3 w-16 h-16 md:w-24 md:h-24 bg-teal-300/20 rounded-full blur-2xl animate-pulse delay-500"></div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 md:py-16 lg:py-20 xl:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-12 items-center w-full">
                
                <!-- Left Content -->
                <div class="text-white space-y-3 sm:space-y-4 md:space-y-5 lg:space-y-6">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full border border-white/30 text-xs sm:text-sm mx-auto lg:mx-0">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        <span class="font-medium text-white">Making Impact Since 2024</span>
                    </div>

                    <!-- Main Heading -->
                    <div class="space-y-2 sm:space-y-3 md:space-y-4">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl xl:text-6xl font-bold leading-tight text-center lg:text-left">
                            <span class="block">Empowering</span>
                            <span class="block bg-gradient-to-r from-blue-200 to-sky-100 bg-clip-text text-transparent">
                                Communities
                            </span>
                            <span class="block">Worldwide</span>
                        </h1>
                        
                        <p class="text-sm sm:text-base md:text-lg text-white/90 leading-relaxed max-w-2xl text-center lg:text-left mx-auto lg:mx-0">
                            Building sustainable futures through youth leadership, innovation, and community-driven development that creates lasting positive change.
                        </p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-3 md:gap-4 py-2 sm:py-3 md:py-4">
                        <div class="text-center lg:text-left">
                            <div class="text-xl sm:text-2xl md:text-3xl font-bold text-white">1,250+</div>
                            <div class="text-xs sm:text-sm text-white/80">Lives Transformed</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-xl sm:text-2xl md:text-3xl font-bold text-white">45+</div>
                            <div class="text-xs sm:text-sm text-white/80">Projects Completed</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-xl sm:text-2xl md:text-3xl font-bold text-white">12</div>
                            <div class="text-xs sm:text-sm text-white/80">Locations Reached</div>
                        </div>
                    </div>

                    <!-- Call-to-Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2 sm:pt-3 md:pt-4">
                        <Link href="/donate" class="group relative px-5 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-white text-blue-700 rounded-xl font-semibold text-sm sm:text-base shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 inline-block text-center">
                            <span class="relative z-10">Donate Now</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </Link>
                        <a href="#about" class="group px-5 sm:px-6 md:px-8 py-2.5 sm:py-3 border-2 border-white/40 text-white rounded-xl font-semibold text-sm sm:text-base backdrop-blur-sm bg-white/10 hover:bg-white/20 hover:border-white/60 transition-all duration-300 inline-block text-center">
                            Learn Our Story
                            <svg class="inline-block ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right Side - Hero Image/Visual -->
                <div class="relative flex items-center justify-center lg:justify-end mt-6 sm:mt-8 lg:mt-0">
                    <!-- Image Carousel -->
                    <div 
                        v-if="hasImages" 
                        class="relative w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl"
                        @mouseenter="pauseCarousel"
                        @mouseleave="resumeCarousel"
                    >
                        <!-- Loading State -->
                        <div v-if="isLoading && loadedImages.size === 0" class="absolute inset-0 z-20 flex items-center justify-center">
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                                <div class="animate-spin rounded-full h-12 w-12 border-4 border-white/20 border-t-white"></div>
                                <p class="text-white mt-4">Loading images...</p>
                            </div>
                        </div>
                        
                        <!-- Main Image Container -->
                        <div class="relative rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl group">
                            <!-- Images with slide animation -->
                            <div class="relative w-full overflow-hidden aspect-[4/3] sm:aspect-[4/3] md:aspect-[4/3] lg:aspect-[4/3]">
                                <div 
                                    v-for="(image, index) in settings.homepage_images"
                                    :key="image.id || index"
                                    class="absolute inset-0 transition-all duration-[3000ms] ease-in-out"
                                    :class="{
                                        'translate-x-0 opacity-100': index === currentImageIndex,
                                        'translate-x-full opacity-0': index > currentImageIndex || (currentImageIndex === settings.homepage_images.length - 1 && index === 0),
                                        '-translate-x-full opacity-0': index < currentImageIndex && !(currentImageIndex === 0 && index === settings.homepage_images.length - 1)
                                    }"
                                >
                                    <img 
                                        :src="`/media/${image.path || 'homepage/' + image.filename}`"
                                        :alt="`${settings.organization_name} - Image ${index + 1}`"
                                        class="absolute inset-0 w-full h-full object-cover"
                                    />
                                </div>
                            </div>
                            
                            <!-- Enhanced Gradient Overlays -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent opacity-60"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-black/30 via-transparent to-transparent"></div>
                            
                            
                            <!-- Progress Bar -->
                            <div v-if="imageCount > 1" class="absolute bottom-0 left-0 right-0 h-1 bg-black/20">
                                <div 
                                    class="h-full bg-white/80 transition-all duration-300"
                                    :style="{ 
                                        width: ((currentImageIndex + 1) / imageCount * 100) + '%',
                                        transition: isTransitioning ? 'none' : 'width 5s linear'
                                    }"
                                ></div>
                            </div>
                            
                            <!-- Navigation Dots -->
                            <div v-if="imageCount > 1" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex items-center space-x-3">
                                <button 
                                    v-for="(image, index) in settings.homepage_images"
                                    :key="index"
                                    @click="goToImage(index)"
                                    class="group/dot relative"
                                >
                                    <div 
                                        :class="[
                                            'w-2 h-2 rounded-full transition-all duration-500 transform',
                                            index === currentImageIndex 
                                                ? 'bg-white w-10 scale-100' 
                                                : 'bg-white/40 hover:bg-white/60 hover:scale-125'
                                        ]"
                                    ></div>
                                    <!-- Tooltip -->
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 group-hover/dot:opacity-100 transition-opacity duration-200 pointer-events-none">
                                        <div class="bg-black/80 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                            Image {{ index + 1 }}
                                        </div>
                                    </div>
                                </button>
                            </div>
                            
                            <!-- Pause Indicator -->
                            <transition
                                enter-active-class="transition-opacity duration-300"
                                leave-active-class="transition-opacity duration-300"
                                enter-from-class="opacity-0"
                                leave-to-class="opacity-0"
                            >
                                <div v-if="isPaused && imageCount > 1" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-4">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </transition>
                        </div>
                        
                        <!-- Floating Decorative Elements -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-green-400/20 to-teal-400/20 rounded-full blur-2xl animate-pulse"></div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-tr from-blue-400/20 to-indigo-400/20 rounded-full blur-2xl animate-pulse delay-1000"></div>
                    </div>
                    
                    <!-- Fallback when no images -->
                    <div v-else class="text-center text-white/80 px-4">
                        <div class="w-full max-w-xs sm:max-w-sm mx-auto">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 transform hover:scale-105 transition-transform duration-300">
                                <div class="animate-pulse">
                                    <svg class="w-20 h-20 mx-auto text-white/60 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No images uploaded</p>
                                    <p class="text-sm text-white/60 mt-2">Images will appear here once uploaded</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 text-white/70 animate-bounce">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>
</template>

<style scoped>
/* Base responsive adjustments */
section {
    min-height: 100vh;
    min-height: 100dvh; /* Dynamic viewport height for mobile browsers */
}

/* Height adjustments for different screens */
@media (max-height: 900px) and (min-width: 1024px) {
    section {
        min-height: 100vh;
    }
}

@media (max-height: 700px) {
    section {
        min-height: auto;
        height: auto;
    }
}

/* Mobile specific adjustments */
@media (max-width: 640px) {
    section {
        min-height: 100vh;
        min-height: 100dvh;
    }
}

/* Very small screens */
@media (max-width: 375px) {
    h1 span {
        font-size: 1.75rem !important;
        line-height: 2rem !important;
    }
}

/* Landscape mobile adjustments */
@media (max-height: 500px) and (orientation: landscape) {
    section {
        min-height: auto;
    }
    
    .py-8 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
}

/* iPad and tablet specific */
@media (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    section {
        min-height: 100vh;
    }
}

/* Slide animation keyframes */
@keyframes slideFromRight {
    0% {
        transform: translateX(100%);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    70% {
        opacity: 1;
    }
    90% {
        opacity: 0;
        transform: translateX(-20%);
    }
    100% {
        transform: translateX(-100%);
        opacity: 0;
    }
}

/* Pulse animation with delay */
.animate-pulse.delay-1000 {
    animation-delay: 1s;
}

/* Enhanced shadow for depth */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Group hover effects */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* Custom translate classes for better control */
.translate-x-full {
    transform: translateX(100%);
}

.-translate-x-full {
    transform: translateX(-100%);
}

.translate-x-0 {
    transform: translateX(0);
}

/* Prevent layout shift during loading */
img {
    will-change: transform;
}
</style>