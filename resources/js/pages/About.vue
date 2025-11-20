<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';

interface TeamMember {
    id: number;
    name: string;
    role: string;
    bio: string;
    photo: string | null;
    credentials: string | null;
}

interface Props {
    teamMembers: TeamMember[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout
        title="About Us - Walton Family Dentistry"
        description="Meet our experienced dental team dedicated to providing exceptional care for you and your family."
    >
        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-16 text-center">
                    <h1 class="mb-6 text-4xl font-bold text-black sm:text-5xl">
                        About Our Practice
                    </h1>
                    <p class="mx-auto max-w-3xl text-xl text-gray-700">
                        At Walton Family Dentistry, we believe in providing
                        compassionate, comprehensive dental care in a
                        comfortable and welcoming environment. Our experienced
                        team is dedicated to helping you achieve and maintain
                        optimal oral health for a lifetime of healthy smiles.
                    </p>
                </div>

                <!-- Practice Values -->
                <div class="mb-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="text-center">
                        <div class="mb-4 text-4xl">🦷</div>
                        <h2 class="mb-2 text-xl font-semibold text-black">
                            Quality Care
                        </h2>
                        <p class="text-gray-700">
                            We use the latest technology and techniques to
                            provide the highest quality dental care.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 text-4xl">❤️</div>
                        <h2 class="mb-2 text-xl font-semibold text-black">
                            Compassionate Approach
                        </h2>
                        <p class="text-gray-700">
                            Your comfort and well-being are our top priorities.
                            We treat every patient like family.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 text-4xl">⭐</div>
                        <h2 class="mb-2 text-xl font-semibold text-black">
                            Comprehensive Services
                        </h2>
                        <p class="text-gray-700">
                            From preventive care to advanced procedures, we
                            offer everything you need under one roof.
                        </p>
                    </div>
                </div>

                <!-- Team Section -->
                <div
                    v-if="teamMembers.length > 0"
                    class="border-t border-gray-200 pt-16"
                >
                    <h2 class="mb-12 text-center text-3xl font-bold text-black">
                        Meet Our Team
                    </h2>
                    <div class="space-y-16">
                        <article
                            v-for="member in teamMembers"
                            :key="member.id"
                            class="grid grid-cols-1 items-start gap-8 md:grid-cols-3"
                        >
                            <!-- Photo -->
                            <div class="flex justify-center md:justify-start">
                                <img
                                    :src="
                                        member.photo ||
                                        '/images/placeholder-avatar.png'
                                    "
                                    :alt="member.name"
                                    class="h-64 w-64 rounded-lg border-2 border-gray-200 object-cover"
                                />
                            </div>

                            <!-- Bio -->
                            <div class="md:col-span-2">
                                <h3 class="mb-1 text-2xl font-bold text-black">
                                    {{ member.name }}
                                </h3>
                                <p class="mb-2 text-lg text-gray-600">
                                    {{ member.role }}
                                </p>
                                <p
                                    v-if="member.credentials"
                                    class="mb-4 text-sm text-gray-500"
                                >
                                    {{ member.credentials }}
                                </p>
                                <p
                                    class="leading-relaxed whitespace-pre-line text-gray-700"
                                >
                                    {{ member.bio }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="mt-16 border-t border-gray-200 py-12 text-center"
                >
                    <p class="text-lg text-gray-600">
                        Team information coming soon.
                    </p>
                </div>

                <!-- Call to Action -->
                <div
                    class="mt-16 rounded-lg bg-gray-50 p-8 text-center md:p-12"
                >
                    <h2 class="mb-4 text-3xl font-bold text-black">
                        Become Part of Our Dental Family
                    </h2>
                    <p class="mx-auto mb-8 max-w-2xl text-xl text-gray-700">
                        We're accepting new patients and would love to welcome
                        you to our practice. Schedule your appointment today and
                        experience the difference.
                    </p>
                    <a
                        href="/contact"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-black px-8 py-3 text-base font-medium text-white transition-colors duration-200 hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                    >
                        Schedule Your Visit
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
