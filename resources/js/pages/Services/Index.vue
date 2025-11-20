<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';

interface Service {
    id: number;
    title: string;
    slug: string;
    description: string;
    icon: string | null;
}

interface Props {
    services: Service[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout
        title="Our Services - Walton Family Dentistry"
        description="Comprehensive dental services for your whole family, from routine cleanings to advanced procedures."
    >
        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-12 text-center">
                    <h1 class="mb-4 text-4xl font-bold text-black sm:text-5xl">
                        Our Services
                    </h1>
                    <p class="mx-auto max-w-3xl text-xl text-gray-700">
                        We offer comprehensive dental care for patients of all
                        ages. From preventive care to advanced procedures, we're
                        here to help you achieve and maintain optimal oral
                        health.
                    </p>
                </div>

                <!-- Services Grid -->
                <div
                    v-if="services.length > 0"
                    class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3"
                >
                    <article
                        v-for="service in services"
                        :key="service.id"
                        class="flex flex-col rounded-lg border border-gray-200 bg-white p-6 transition-shadow duration-200 hover:shadow-lg"
                    >
                        <h2 class="mb-4 text-2xl font-semibold text-black">
                            {{ service.title }}
                        </h2>
                        <p class="mb-6 flex-1 text-gray-700">
                            {{ service.description }}
                        </p>
                        <a
                            :href="`/services/${service.slug}`"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-black px-6 py-2.5 text-base font-medium text-white transition-colors duration-200 hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        >
                            Learn More
                        </a>
                    </article>
                </div>

                <!-- Empty State -->
                <div v-else class="py-12 text-center">
                    <p class="text-lg text-gray-600">
                        No services available at this time.
                    </p>
                </div>

                <!-- Call to Action -->
                <div
                    class="mt-16 rounded-lg bg-gray-50 p-8 text-center md:p-12"
                >
                    <h2 class="mb-4 text-3xl font-bold text-black">
                        Have Questions About Our Services?
                    </h2>
                    <p class="mx-auto mb-8 max-w-2xl text-xl text-gray-700">
                        Our friendly team is here to help answer any questions
                        you may have. Contact us today to learn more or schedule
                        your appointment.
                    </p>
                    <a
                        href="/contact"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-black px-8 py-3 text-base font-medium text-white transition-colors duration-200 hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                    >
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
