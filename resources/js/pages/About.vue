<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'

interface TeamMember {
  id: number
  name: string
  role: string
  bio: string
  photo: string | null
  credentials: string | null
}

interface Props {
  teamMembers: TeamMember[]
}

defineProps<Props>()
</script>

<template>
  <AppLayout
    title="About Us - Walton Family Dentistry"
    description="Meet our experienced dental team dedicated to providing exceptional care for you and your family."
  >
    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-16">
          <h1 class="text-4xl font-bold text-black sm:text-5xl mb-6">About Our Practice</h1>
          <p class="text-xl text-gray-700 max-w-3xl mx-auto">
            At Walton Family Dentistry, we believe in providing compassionate, comprehensive dental
            care in a comfortable and welcoming environment. Our experienced team is dedicated to
            helping you achieve and maintain optimal oral health for a lifetime of healthy smiles.
          </p>
        </div>

        <!-- Practice Values -->
        <div class="mb-16 grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="text-4xl mb-4">🦷</div>
            <h2 class="text-xl font-semibold text-black mb-2">Quality Care</h2>
            <p class="text-gray-700">
              We use the latest technology and techniques to provide the highest quality dental care.
            </p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">❤️</div>
            <h2 class="text-xl font-semibold text-black mb-2">Compassionate Approach</h2>
            <p class="text-gray-700">
              Your comfort and well-being are our top priorities. We treat every patient like family.
            </p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">⭐</div>
            <h2 class="text-xl font-semibold text-black mb-2">Comprehensive Services</h2>
            <p class="text-gray-700">
              From preventive care to advanced procedures, we offer everything you need under one roof.
            </p>
          </div>
        </div>

        <!-- Team Section -->
        <div v-if="teamMembers.length > 0" class="border-t border-gray-200 pt-16">
          <h2 class="text-3xl font-bold text-black text-center mb-12">Meet Our Team</h2>
          <div class="space-y-16">
            <article
              v-for="member in teamMembers"
              :key="member.id"
              class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start"
            >
              <!-- Photo -->
              <div class="flex justify-center md:justify-start">
                <img
                  :src="member.photo || '/images/placeholder-avatar.png'"
                  :alt="member.name"
                  class="w-64 h-64 rounded-lg object-cover border-2 border-gray-200"
                />
              </div>

              <!-- Bio -->
              <div class="md:col-span-2">
                <h3 class="text-2xl font-bold text-black mb-1">{{ member.name }}</h3>
                <p class="text-lg text-gray-600 mb-2">{{ member.role }}</p>
                <p v-if="member.credentials" class="text-sm text-gray-500 mb-4">
                  {{ member.credentials }}
                </p>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ member.bio }}</p>
              </div>
            </article>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12 border-t border-gray-200 mt-16">
          <p class="text-gray-600 text-lg">Team information coming soon.</p>
        </div>

        <!-- Call to Action -->
        <div class="mt-16 bg-gray-50 rounded-lg p-8 md:p-12 text-center">
          <h2 class="text-3xl font-bold text-black mb-4">
            Become Part of Our Dental Family
          </h2>
          <p class="text-xl text-gray-700 mb-8 max-w-2xl mx-auto">
            We're accepting new patients and would love to welcome you to our practice.
            Schedule your appointment today and experience the difference.
          </p>
          <a
            href="/contact"
            class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
          >
            Schedule Your Visit
          </a>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
