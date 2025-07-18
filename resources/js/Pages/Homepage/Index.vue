<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Homepage/HeroSection.vue';
import AboutSection from '@/Components/Homepage/AboutSection.vue';
import ImpactSection from '@/Components/Homepage/ImpactSection.vue';
import ProgramsSection from '@/Components/Homepage/ProgramsSection.vue';
import HowToHelpSection from '@/Components/Homepage/HowToHelpSection.vue';
import TestimonialsSection from '@/Components/Homepage/TestimonialsSection.vue';
import NewsSection from '@/Components/Homepage/NewsSection.vue';
import GetInvolvedSection from '@/Components/Homepage/GetInvolvedSection.vue';
import PartnersSection from '@/Components/Homepage/PartnersSection.vue';

const props = defineProps({
    settings: Object,
});
</script>

<template>
    <Head title="Home - Ustawi Wa Jamii" />
    
    <PublicLayout :settings="settings">
        <!-- Hero Section with Image Slider -->
        <HeroSection :settings="settings" />
        
        <!-- About Us Section -->
        <AboutSection :settings="settings" />
        
        <!-- Our Impact Section -->
        <ImpactSection :settings="settings" />
        
        <!-- Programs/Projects Section -->
        <ProgramsSection :settings="settings" />
        
        <!-- How You Can Help Section -->
        <HowToHelpSection :settings="settings" />
        
        <!-- Success Stories/Testimonials -->
        <TestimonialsSection :settings="settings" />
        
        <!-- Latest News/Updates -->
        <NewsSection :settings="settings" />
        
        <!-- Get Involved Section -->
        <GetInvolvedSection :settings="settings" />
        
        <!-- Partners/Supporters -->
        <PartnersSection :settings="settings" />
    </PublicLayout>
</template>