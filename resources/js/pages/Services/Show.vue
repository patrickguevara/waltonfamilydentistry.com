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
  service: Service
  relatedServices: Service[]
}

defineProps<Props>()
</script>

<template>
  <AppLayout :title="`${service.title} - Walton Family Dentistry`" :description="service.description">
    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="mb-8">
          <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li>
              <a
                href="/"
                class="hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded transition-colors duration-200"
              >
                Home
              </a>
            </li>
            <li aria-hidden="true">/</li>
            <li>
              <a
                href="/services"
                class="hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded transition-colors duration-200"
              >
                Services
              </a>
            </li>
            <li aria-hidden="true">/</li>
            <li aria-current="page" class="text-black font-medium">{{ service.title }}</li>
          </ol>
        </nav>

        <!-- Service Detail -->
        <article class="mb-16">
          <h1 class="text-4xl font-bold text-black sm:text-5xl mb-6">{{ service.title }}</h1>
          <div class="prose prose-lg max-w-none">
            <p class="text-xl text-gray-700 leading-relaxed">{{ service.description }}</p>
          </div>

          <!-- CTA -->
          <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <a
              href="/contact"
              class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
            >
              Schedule Appointment
            </a>
            <a
              href="/contact"
              class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-black bg-white border-2 border-black rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px]"
            >
              Ask a Question
            </a>
          </div>
        </article>

        <!-- Related Services -->
        <div v-if="relatedServices.length > 0" class="border-t border-gray-200 pt-12">
          <h2 class="text-3xl font-bold text-black mb-8">Other Services</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <article
              v-for="related in relatedServices"
              :key="related.id"
              class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200"
            >
              <h3 class="text-xl font-semibold text-black mb-3">{{ related.title }}</h3>
              <p class="text-gray-700 mb-4 line-clamp-3">{{ related.description }}</p>
              <a
                :href="`/services/${related.slug}`"
                class="text-black font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded"
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
