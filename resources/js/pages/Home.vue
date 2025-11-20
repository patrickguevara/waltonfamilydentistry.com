<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';

interface Service {
    id: number;
    title: string;
    slug: string;
    description: string;
    icon: string | null;
}

interface TeamMember {
    id: number;
    name: string;
    role: string;
    bio: string;
    photo: string | null;
    credentials: string | null;
}

interface Props {
    services: Service[];
    teamMembers: TeamMember[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout
        title="Walton Family Dentistry - Quality Dental Care in Austin, TX"
        description="Experience compassionate, comprehensive dental care for the whole family. Modern dentistry with a personal touch in Austin, Texas."
    >
        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="mb-16 text-center">
                    <h1
                        class="mb-6 text-4xl font-bold text-black sm:text-5xl md:text-6xl"
                    >
                        Welcome to Walton Family Dentistry
                    </h1>
                    <p class="mx-auto mb-8 max-w-3xl text-xl text-gray-700">
                        Experience compassionate, comprehensive dental care for
                        the whole family. Modern dentistry with a personal
                        touch.
                    </p>
                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <a
                            href="/contact"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-black px-8 py-3 text-base font-medium text-white transition-colors duration-200 hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        >
                            Schedule Appointment
                        </a>
                        <a
                            href="/services"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-lg border-2 border-black bg-white px-8 py-3 text-base font-medium text-black transition-colors duration-200 hover:bg-gray-50 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        >
                            Our Services
                        </a>
                    </div>
                </div>

                <!-- Services Preview -->
                <div v-if="services.length > 0" class="mb-16">
                    <h2 class="mb-8 text-center text-3xl font-bold text-black">
                        Our Services
                    </h2>
                    <div class="mb-8 grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div
                            v-for="service in services"
                            :key="service.id"
                            class="rounded-lg border border-gray-200 bg-white p-6 transition-shadow duration-200 hover:shadow-lg"
                        >
                            <h3 class="mb-3 text-xl font-semibold text-black">
                                {{ service.title }}
                            </h3>
                            <p class="mb-4 text-gray-700">
                                {{ service.description }}
                            </p>
                            <a
                                :href="`/services/${service.slug}`"
                                class="rounded font-medium text-black hover:underline focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                            >
                                Learn More →
                            </a>
                        </div>
                    </div>
                    <div class="text-center">
                        <a
                            href="/services"
                            class="inline-flex items-center justify-center rounded-lg px-6 py-2.5 text-base font-medium text-black transition-colors duration-200 hover:text-gray-700 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        >
                            View All Services →
                        </a>
                    </div>
                </div>

                <!-- Team Preview -->
                <div v-if="teamMembers.length > 0" class="mb-16">
                    <h2 class="mb-8 text-center text-3xl font-bold text-black">
                        Meet Our Team
                    </h2>
                    <div
                        class="mb-8 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <div
                            v-for="member in teamMembers"
                            :key="member.id"
                            class="text-center"
                        >
                            <div class="mb-4 flex justify-center">
                                <img
                                    :src="
                                        member.photo ||
                                        '/images/placeholder-avatar.png'
                                    "
                                    :alt="member.name"
                                    class="h-48 w-48 rounded-full border-2 border-gray-200 object-cover"
                                />
                            </div>
                            <h3 class="mb-1 text-xl font-semibold text-black">
                                {{ member.name }}
                            </h3>
                            <p class="mb-2 text-gray-600">{{ member.role }}</p>
                            <p
                                v-if="member.credentials"
                                class="text-sm text-gray-500"
                            >
                                {{ member.credentials }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center">
                        <a
                            href="/about"
                            class="inline-flex items-center justify-center rounded-lg px-6 py-2.5 text-base font-medium text-black transition-colors duration-200 hover:text-gray-700 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        >
                            Meet the Full Team →
                        </a>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="rounded-lg bg-gray-50 p-8 text-center md:p-12">
                    <h2 class="mb-4 text-3xl font-bold text-black">
                        Ready to Schedule Your Visit?
                    </h2>
                    <p class="mx-auto mb-8 max-w-2xl text-xl text-gray-700">
                        We're accepting new patients and would love to be your
                        dental care provider. Contact us today to schedule your
                        appointment.
                    </p>
                    <a
                        href="/contact"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-black px-8 py-3 text-base font-medium text-white transition-colors duration-200 hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                    >
                        Contact Us Today
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
