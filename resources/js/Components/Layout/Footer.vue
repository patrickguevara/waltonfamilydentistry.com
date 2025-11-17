<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

interface OfficeInfo {
  practice_name: string
  phone: string
  email?: string
  full_address: string
  hours_array: Record<string, string | null>
}

const page = usePage<{ officeInfo: OfficeInfo }>()
const officeInfo = computed(() => page.props.officeInfo)

const navigation = [
  { name: 'Home', href: '/' },
  { name: 'About', href: '/about' },
  { name: 'Services', href: '/services' },
  { name: 'Contact', href: '/contact' },
]

const currentYear = new Date().getFullYear()
</script>

<template>
  <footer class="bg-gray-50 border-t border-gray-200">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <!-- Practice Info -->
        <div>
          <h3 class="text-lg font-semibold text-black mb-4">{{ officeInfo.practice_name }}</h3>
          <address class="not-italic text-gray-700 space-y-2">
            <p>{{ officeInfo.full_address }}</p>
            <p>
              <a
                :href="`tel:${officeInfo.phone.replace(/[^0-9]/g, '')}`"
                class="hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded transition-colors duration-200"
              >
                {{ officeInfo.phone }}
              </a>
            </p>
            <p v-if="officeInfo.email">
              <a
                :href="`mailto:${officeInfo.email}`"
                class="hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded transition-colors duration-200"
              >
                {{ officeInfo.email }}
              </a>
            </p>
          </address>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-semibold text-black mb-4">Quick Links</h3>
          <nav aria-label="Footer navigation">
            <ul class="space-y-2">
              <li v-for="item in navigation" :key="item.name">
                <Link
                  :href="item.href"
                  class="text-gray-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded transition-colors duration-200"
                >
                  {{ item.name }}
                </Link>
              </li>
            </ul>
          </nav>
        </div>

        <!-- Office Hours -->
        <div>
          <h3 class="text-lg font-semibold text-black mb-4">Office Hours</h3>
          <dl class="space-y-1 text-gray-700">
            <div
              v-for="(hours, day) in officeInfo.hours_array"
              :key="day"
              class="flex justify-between"
            >
              <dt class="font-medium">{{ day }}:</dt>
              <dd>{{ hours || 'Closed' }}</dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Copyright -->
      <div class="mt-8 pt-8 border-t border-gray-200">
        <p class="text-center text-gray-600 text-sm">
          &copy; {{ currentYear }} {{ officeInfo.practice_name }}. All rights reserved.
        </p>
      </div>
    </div>
  </footer>
</template>
