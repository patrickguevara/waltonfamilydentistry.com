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
    service: Service;
    relatedServices: Service[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout
        :title="`${service.title} - Walton Family Dentistry`"
        :description="service.description"
    >
        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav aria-label="Breadcrumb" class="mb-8">
                    <ol
                        class="flex items-center space-x-2 text-sm text-gray-600"
                    >
                        <li>
                            <a
                                href="/"
                                class="rounded transition-colors duration-200 hover:text-black focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                            >
                                Home
                            </a>
                        </li>
                        <li aria-hidden="true">/</li>
                        <li>
                            <a
                                href="/services"
                                class="rounded transition-colors duration-200 hover:text-black focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                            >
                                Services
                            </a>
                        </li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page" class="font-medium text-black">
                            {{ service.title }}
                        </li>
                    </ol>
                </nav>

                <!-- Service Detail -->
                <article class="mb-16">
                    <h1 class="mb-6 text-4xl font-bold text-black sm:text-5xl">
                        {{ service.title }}
                    </h1>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-xl leading-relaxed text-gray-700">
                            {{ service.description }}
                        </p>
                    </div>

                    <!-- CTA -->
                    <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                        <a
                            href="/contact"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-black px-8 py-3 text-base font-medium text-white transition-colors duration-200 hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        >
                            Schedule Appointment
                        </a>
                        <a
                            href="/contact"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-lg border-2 border-black bg-white px-8 py-3 text-base font-medium text-black transition-colors duration-200 hover:bg-gray-50 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        >
                            Ask a Question
                        </a>
                    </div>
                </article>

                <!-- Related Services -->
                <div
                    v-if="relatedServices.length > 0"
                    class="border-t border-gray-200 pt-12"
                >
                    <h2 class="mb-8 text-3xl font-bold text-black">
                        Other Services
                    </h2>
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <article
                            v-for="related in relatedServices"
                            :key="related.id"
                            class="rounded-lg border border-gray-200 bg-white p-6 transition-shadow duration-200 hover:shadow-lg"
                        >
                            <h3 class="mb-3 text-xl font-semibold text-black">
                                {{ related.title }}
                            </h3>
                            <p class="mb-4 line-clamp-3 text-gray-700">
                                {{ related.description }}
                            </p>
                            <a
                                :href="`/services/${related.slug}`"
                                class="rounded font-medium text-black hover:underline focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                            >
                                Learn More →
                            </a>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
