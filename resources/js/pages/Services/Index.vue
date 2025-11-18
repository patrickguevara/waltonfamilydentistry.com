<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'

interface Service {
  id: number
  title: string
  slug: string
  description: string
  icon: string | null
}

interface Props {
  services: Service[]
}

defineProps<Props>()
</script>

<template>
  <AppLayout
    title="Our Services - Walton Family Dentistry"
    description="Comprehensive dental services for your whole family, from routine cleanings to advanced procedures."
  >
    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-bold text-black sm:text-5xl mb-4">Our Services</h1>
          <p class="text-xl text-gray-700 max-w-3xl mx-auto">
            We offer comprehensive dental care for patients of all ages. From preventive care
            to advanced procedures, we're here to help you achieve and maintain optimal oral health.
          </p>
        </div>

        <!-- Services Grid -->
        <div v-if="services.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article
            v-for="service in services"
            :key="service.id"
            class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200 flex flex-col"
          >
            <h2 class="text-2xl font-semibold text-black mb-4">{{ service.title }}</h2>
            <p class="text-gray-700 mb-6 flex-1">{{ service.description }}</p>
            <a
              :href="`/services/${service.slug}`"
              class="inline-flex items-center justify-center px-6 py-2.5 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
            >
              Learn More
            </a>
          </article>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <p class="text-gray-600 text-lg">No services available at this time.</p>
        </div>

        <!-- Call to Action -->
        <div class="mt-16 bg-gray-50 rounded-lg p-8 md:p-12 text-center">
          <h2 class="text-3xl font-bold text-black mb-4">
            Have Questions About Our Services?
          </h2>
          <p class="text-xl text-gray-700 mb-8 max-w-2xl mx-auto">
            Our friendly team is here to help answer any questions you may have.
            Contact us today to learn more or schedule your appointment.
          </p>
          <a
            href="/contact"
            class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
          >
            Contact Us
          </a>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
