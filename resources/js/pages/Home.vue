<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'

interface Service {
  id: number
  title: string
  slug: string
  description: string
  icon: string | null
}

interface TeamMember {
  id: number
  name: string
  role: string
  bio: string
  photo: string | null
  credentials: string | null
}

interface Props {
  services: Service[]
  teamMembers: TeamMember[]
}

defineProps<Props>()
</script>

<template>
  <AppLayout
    title="Walton Family Dentistry - Quality Dental Care in Austin, TX"
    description="Experience compassionate, comprehensive dental care for the whole family. Modern dentistry with a personal touch in Austin, Texas."
  >
    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-16">
          <h1 class="text-4xl font-bold text-black sm:text-5xl md:text-6xl mb-6">
            Welcome to Walton Family Dentistry
          </h1>
          <p class="text-xl text-gray-700 max-w-3xl mx-auto mb-8">
            Experience compassionate, comprehensive dental care for the whole family.
            Modern dentistry with a personal touch.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="/contact"
              class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
            >
              Schedule Appointment
            </a>
            <a
              href="/services"
              class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-black bg-white border-2 border-black rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
            >
              Our Services
            </a>
          </div>
        </div>

        <!-- Services Preview -->
        <div v-if="services.length > 0" class="mb-16">
          <h2 class="text-3xl font-bold text-black text-center mb-8">Our Services</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div
              v-for="service in services"
              :key="service.id"
              class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200"
            >
              <h3 class="text-xl font-semibold text-black mb-3">{{ service.title }}</h3>
              <p class="text-gray-700 mb-4">{{ service.description }}</p>
              <a
                :href="`/services/${service.slug}`"
                class="text-black font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded"
              >
                Learn More →
              </a>
            </div>
          </div>
          <div class="text-center">
            <a
              href="/services"
              class="inline-flex items-center justify-center px-6 py-2.5 text-base font-medium text-black hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded-lg transition-colors duration-200"
            >
              View All Services →
            </a>
          </div>
        </div>

        <!-- Team Preview -->
        <div v-if="teamMembers.length > 0" class="mb-16">
          <h2 class="text-3xl font-bold text-black text-center mb-8">Meet Our Team</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            <div
              v-for="member in teamMembers"
              :key="member.id"
              class="text-center"
            >
              <div class="mb-4 flex justify-center">
                <img
                  :src="member.photo || '/images/placeholder-avatar.png'"
                  :alt="member.name"
                  class="w-48 h-48 rounded-full object-cover border-2 border-gray-200"
                />
              </div>
              <h3 class="text-xl font-semibold text-black mb-1">{{ member.name }}</h3>
              <p class="text-gray-600 mb-2">{{ member.role }}</p>
              <p v-if="member.credentials" class="text-sm text-gray-500">
                {{ member.credentials }}
              </p>
            </div>
          </div>
          <div class="text-center">
            <a
              href="/about"
              class="inline-flex items-center justify-center px-6 py-2.5 text-base font-medium text-black hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded-lg transition-colors duration-200"
            >
              Meet the Full Team →
            </a>
          </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gray-50 rounded-lg p-8 md:p-12 text-center">
          <h2 class="text-3xl font-bold text-black mb-4">
            Ready to Schedule Your Visit?
          </h2>
          <p class="text-xl text-gray-700 mb-8 max-w-2xl mx-auto">
            We're accepting new patients and would love to be your dental care provider.
            Contact us today to schedule your appointment.
          </p>
          <a
            href="/contact"
            class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
          >
            Contact Us Today
          </a>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
