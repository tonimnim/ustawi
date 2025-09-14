<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, reactive } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    settings: Object,
});

// Active category state
const activeCategory = ref('environmental');

// Image carousel state
const imageIndices = reactive({});

// Initialize carousel interval
onMounted(() => {
    // Set up image carousel intervals
    setInterval(() => {
        // Update image indices for programs with multiple images
        Object.keys(categories).forEach(catKey => {
            categories[catKey].programs.forEach(program => {
                if (program.images && program.images.length > 1) {
                    const key = program.id;
                    if (!imageIndices[key]) imageIndices[key] = 0;
                    imageIndices[key] = (imageIndices[key] + 1) % program.images.length;
                }
            });
        });
    }, 3000); // Change image every 3 seconds
});

// Scroll to section when category changes
const scrollToSection = () => {
    const element = document.getElementById('programs-content');
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

// Program categories
const categories = {
    environmental: {
        title: "Environmental & Climate Action",
        icon: "🌍",
        description: "Protecting our planet through sustainable initiatives that combat climate change and preserve natural resources for future generations.",
        programs: [
            {
                id: 'tree-planting',
                title: "Tree Planting Campaigns",
                icon: "🌳",
                shortDesc: "Promoting agroforestry and biodiversity by planting indigenous and fruit trees.",
                fullDesc: "We promote agroforestry and biodiversity by planting indigenous and fruit trees in schools, farms, and community spaces. These activities foster climate action and food sustainability while creating green spaces that benefit entire communities.",
                impact: "10,000+ trees planted",
                activities: [
                    "Community tree planting events",
                    "School greening programs",
                    "Indigenous tree nursery establishment",
                    "Agroforestry training for farmers"
                ],
                images: [
                    "/assets/treeplanting.JPG",
                    "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670896/tree_campaign_p0orad.jpg"
                ]
            },
            {
                id: 'waste-to-art',
                title: "Waste To Art",
                icon: "🎨",
                shortDesc: "Supporting young creatives in converting waste into artistic expressions.",
                fullDesc: "We support young creatives in converting waste into art to highlight the value of recycling and environmental preservation. This initiative blends climate action, youth innovation, and community awareness.",
                impact: "100+ artists supported",
                activities: [
                    "Art workshops using recycled materials",
                    "Youth artist mentorship programs",
                    "Community art exhibitions",
                    "Marketing support for eco-artists"
                ],
                image: "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670515/waste_to_art_wndver.jpg"
            },
            {
                id: 'community-cleanup',
                title: "Community Clean-up Campaigns",
                icon: "🧹",
                shortDesc: "Regular clean-up drives in informal settlements and marketplaces.",
                fullDesc: "Ustawi Wa Jamii organizes regular clean-up drives in informal settlements and marketplaces to promote hygiene, public health, and civic responsibility.",
                impact: "50+ clean-ups organized",
                activities: [
                    "Monthly community clean-up drives",
                    "Waste segregation education",
                    "Distribution of cleaning equipment",
                    "Public health awareness campaigns"
                ],
                image: "/assets/Community Clean-up Campaigns.jpeg"
            }
        ]
    },
    rights: {
        title: "Rights & Legal Empowerment",
        icon: "⚖️",
        description: "Championing justice and equality by advocating for the rights of vulnerable groups and providing legal support to those who need it most.",
        programs: [
            {
                id: 'voice-of-silent-workers',
                title: "Voice of the Silent Workers",
                icon: "🐴",
                shortDesc: "Advocating for the protection of donkeys and other working animals.",
                fullDesc: "An animal rights initiative advocating for the protection of donkeys and other working animals. We raise awareness, lobby for ethical treatment, and promote legislation to prevent animal cruelty.",
                impact: "500+ animals protected",
                activities: [
                    "Community education on animal welfare",
                    "Free veterinary clinics",
                    "Distribution of proper harnesses",
                    "Advocacy for animal protection laws"
                ],
                images: [
                    "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670604/silent_workers_i2ycmh.jpg"
                ]
            },
            {
                id: 'community-paralegal',
                title: "Community Paralegal Office",
                icon: "⚖️",
                shortDesc: "Providing legal awareness and support on land and inheritance rights.",
                fullDesc: "This initiative provides legal awareness and support on land and inheritance rights—particularly for women and children born out of wedlock. Our trained paralegal youth volunteers facilitate mediations, offer legal referrals, and conduct civic education forums in underserved communities.",
                impact: "300+ cases resolved",
                activities: [
                    "Free legal clinics in rural communities",
                    "Mediation services for disputes",
                    "Legal awareness workshops",
                    "Referrals to pro bono lawyers"
                ],
                images: [
                    "https://res.cloudinary.com/dpbheqr2n/image/upload/v1757670557/Community_Paralegal_ubccns.jpg",
                    "/assets/Economic Empowerment & Food Security1.jpeg",
                    "/assets/Economic Empowerment & Food Security2.jpeg",
                    "/assets/Economic Empowerment & Food Security3.jpeg",
                    "/assets/Economic Empowerment & Food Security4.jpeg"
                ]
            }
        ]
    },
    education: {
        title: "Education & Youth Development",
        icon: "📚",
        description: "Empowering the next generation through innovative educational programs that build skills, confidence, and opportunities for young people.",
        programs: [
            {
                id: 'toto-smart-move',
                title: "Toto Smart Move",
                icon: "🚸",
                shortDesc: "Child-focused transport safety training for ages 0-12.",
                fullDesc: "A child-focused transport safety training initiative that teaches children (0–12 years) how to safely navigate roads, vehicles, ferries, and trains. Caregivers and teachers are trained alongside children for long-term impact.",
                impact: "2,000+ children trained",
                activities: [
                    "Interactive safety workshops",
                    "Teacher training programs",
                    "Distribution of reflective materials",
                    "Road safety demonstrations"
                ],
                images: [
                    "https://res.cloudinary.com/dliqth2su/image/upload/v1753966772/toto3_wlnuxp.jpg",
                    "https://res.cloudinary.com/dliqth2su/image/upload/v1753966772/toto1_rer1px.jpg",
                    "https://res.cloudinary.com/dliqth2su/image/upload/v1753966772/toto2_qxfph0.jpg"
                ]
            },
            {
                id: 'sanitary-pad-drives',
                title: "Sanitary Pad Drives",
                icon: "🌸",
                shortDesc: "Distributing sanitary pads and educating girls on menstrual hygiene.",
                fullDesc: "We distribute sanitary pads and educate girls on menstrual hygiene to reduce absenteeism and promote dignity in schools, especially in under-resourced areas.",
                impact: "5,000+ girls reached",
                activities: [
                    "Distribution of sanitary pads in schools",
                    "Menstrual hygiene workshops",
                    "Training on reusable pad making",
                    "Boys' sensitization programs"
                ],
                image: "https://res.cloudinary.com/dliqth2su/image/upload/v1753993361/Sanitary_Pad_Drives_iuhljw.jpg"
            }
        ]
    },
    economic: {
        title: "Economic Empowerment & Food Security",
        icon: "🌾",
        description: "Building sustainable livelihoods and ensuring food security through innovative agricultural programs and skills development.",
        programs: [
            {
                id: 'harvest-of-tomorrow',
                title: "Harvest of Tomorrow",
                icon: "🌾",
                shortDesc: "Training smallholder farmers in climate-smart agriculture.",
                fullDesc: "Our flagship agricultural program that uses the Farmer Field School (FFS) model to train smallholder farmers in climate-smart agriculture, agroforestry, nutrition, and legal land rights. Over 6,000 farmers have benefited from this program.",
                impact: "6,000+ farmers trained",
                activities: [
                    "Farmer Field School training sessions",
                    "Distribution of quality seeds",
                    "Demonstration plots for best practices",
                    "Market linkage facilitation"
                ],
                images: [
                    "https://i.imgur.com/WwnRTKy.jpeg",
                    "https://i.imgur.com/kt0PBnb.jpeg",
                    "https://i.imgur.com/JQ1BFn2.jpeg",
                    "https://i.imgur.com/9AGbX9A.jpeg",
                    "https://i.imgur.com/5E4fKAx.jpeg",
                    "https://i.imgur.com/SWmiblK.jpeg"
                ]
            }
        ]
    }
};
</script>

<template>
    <Head title="Our Programs - Ustawi Wa Jamii" />
    
    <PublicLayout :settings="settings">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-600 via-teal-500 to-green-400 py-20">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6">Our Programs</h1>
                <p class="text-xl text-white/90 max-w-3xl mx-auto">
                    Explore our comprehensive initiatives designed to create lasting positive change in communities worldwide
                </p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-full px-4 lg:px-8">
                <div class="flex gap-8">
                    <!-- Sidebar - Moved closer to left -->
                    <div class="w-80 flex-shrink-0">
                        <div class="sticky top-8 space-y-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Program Categories</h3>
                            <nav class="space-y-2">
                                <button
                                    v-for="(category, key) in categories"
                                    :key="key"
                                    @click="activeCategory = key; scrollToSection()"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-lg transition-all duration-300 flex items-center space-x-3',
                                        activeCategory === key 
                                            ? 'bg-gradient-to-r from-blue-500 to-teal-500 text-white shadow-lg' 
                                            : 'bg-white text-gray-700 hover:shadow-md hover:bg-gray-50'
                                    ]"
                                >
                                    <span class="text-2xl">{{ category.icon }}</span>
                                    <span class="font-semibold text-sm">{{ category.title }}</span>
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-1" id="programs-content">
                        <!-- Category Header -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                            <div class="flex items-center mb-6">
                                <span class="text-5xl mr-4">{{ categories[activeCategory].icon }}</span>
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900">{{ categories[activeCategory].title }}</h2>
                                </div>
                            </div>
                            <p class="text-lg text-gray-600">{{ categories[activeCategory].description }}</p>
                        </div>

                        <!-- All Programs in Category -->
                        <div class="space-y-8">
                            <div
                                v-for="program in categories[activeCategory].programs"
                                :key="program.id"
                                class="bg-white rounded-2xl shadow-lg overflow-hidden"
                            >
                                <div class="grid md:grid-cols-2 gap-0">
                                    <!-- Program Image/Video Section -->
                                    <div class="h-full md:h-96 relative overflow-hidden bg-gray-100">
                                        <!-- Video embed for Voice of Silent Workers -->
                                        <iframe 
                                            v-if="program.videoUrl" 
                                            :src="program.videoUrl"
                                            class="w-full h-full"
                                            style="min-height: 400px;"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                        <!-- Image carousel for programs with multiple images -->
                                        <div v-else-if="program.images && program.images.length > 0" class="relative h-full">
                                            <img 
                                                v-for="(img, idx) in program.images"
                                                :key="idx"
                                                :src="img" 
                                                :alt="`${program.title} - Image ${idx + 1}`"
                                                :class="['absolute inset-0 w-full h-full object-cover transition-opacity duration-1000', 
                                                    imageIndices[program.id] === idx || (!imageIndices[program.id] && idx === 0) ? 'opacity-100' : 'opacity-0']"
                                            />
                                            <!-- Image indicators -->
                                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                                <div 
                                                    v-for="(img, idx) in program.images"
                                                    :key="idx"
                                                    :class="['w-2 h-2 rounded-full transition-all duration-300',
                                                        imageIndices[program.id] === idx || (!imageIndices[program.id] && idx === 0) 
                                                            ? 'bg-white w-8' 
                                                            : 'bg-white/50']"
                                                ></div>
                                            </div>
                                        </div>
                                        <!-- Single image -->
                                        <img 
                                            v-else-if="program.image" 
                                            :src="program.image" 
                                            :alt="program.title"
                                            class="w-full h-full object-cover"
                                        />
                                        <!-- Fallback icon -->
                                        <div v-else class="h-full bg-gradient-to-br from-blue-100 to-teal-100 relative overflow-hidden p-12 flex items-center justify-center">
                                            <span class="text-8xl opacity-50">{{ program.icon }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Program Details -->
                                    <div class="p-8">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ program.title }}</h3>
                                        
                                        <p class="text-gray-600 mb-4">{{ program.fullDesc }}</p>
                                        
                                        <!-- Activities as paragraph -->
                                        <p class="text-gray-600 mb-6">
                                            Through our {{ program.impact.toLowerCase() }}, we focus on {{ program.activities.join(', ').toLowerCase() }}. These initiatives work together to create lasting positive change in communities.
                                        </p>
                                        
                                        <!-- CTA Button -->
                                        <div class="flex space-x-4">
                                            <Link href="/donate" class="inline-block bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-full text-sm font-semibold transition-all duration-300">
                                                Take Action
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </PublicLayout>
</template>