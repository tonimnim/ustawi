<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Ways to get involved
const involvementOptions = [
    {
        title: "Volunteer",
        icon: "🙋‍♀️",
        description: "Join our team of dedicated volunteers making a difference in communities worldwide",
        action: "Sign Up to Volunteer",
        link: "/contact"
    },
    {
        title: "Donate",
        icon: "💝",
        description: "Your financial support helps us expand our programs and reach more beneficiaries",
        action: "Make a Donation",
        link: "/donate"
    },
    {
        title: "Partner",
        icon: "🤝",
        description: "Collaborate with us to create sustainable solutions for community development",
        action: "Become a Partner",
        link: "/contact"
    },
    {
        title: "Advocate",
        icon: "📢",
        description: "Help spread our message and advocate for positive change in your community",
        action: "Join Our Cause",
        link: "/contact"
    }
];
</script>

<template>
    <section id="contact" class="py-20 bg-gradient-to-b from-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Get Involved</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Be part of the change. There are many ways you can contribute to our mission
                </p>
            </div>

            <!-- Involvement Options Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <div v-for="option in involvementOptions" :key="option.title" 
                     class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="text-5xl mb-4">{{ option.icon }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ option.title }}</h3>
                    <p class="text-gray-600 mb-6">{{ option.description }}</p>
                    <Link :href="option.link" class="text-blue-600 font-semibold hover:text-blue-800 transition-colors duration-300">
                        {{ option.action }} →
                    </Link>
                </div>
            </div>

        </div>
    </section>
</template>