<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Partner images
const partners = [
    { id: 1, image: "/assets/partner1.jpg", alt: "Partner 1" },
    { id: 2, image: "/assets/partner2.jpg", alt: "Partner 2" }
];
</script>

<template>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Our Partners</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Working together with organizations that share our vision for community empowerment
                </p>
            </div>

            <!-- Partners Display -->
            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <div v-for="partner in partners" :key="partner.id" 
                     class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8">
                    <div class="flex items-center justify-center">
                        <img 
                            :src="partner.image" 
                            :alt="partner.alt"
                            class="w-full h-auto object-contain max-h-80"
                        />
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-12">
                <p class="text-gray-600 mb-6">Interested in partnering with us?</p>
                <Link href="/contact" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105">
                    Become a Partner →
                </Link>
            </div>
        </div>
    </section>
</template>