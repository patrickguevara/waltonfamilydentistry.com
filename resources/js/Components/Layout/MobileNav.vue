<script setup lang="ts">
import { watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useReducedMotion } from '@/composables/useReducedMotion'

interface Props {
  open: boolean
  navigation: Array<{ name: string; href: string }>
  officeInfo: { phone: string }
}

const props = defineProps<Props>()
const emit = defineEmits<{ close: [] }>()

const page = usePage()
const { shouldReduceMotion } = useReducedMotion()

function isCurrentRoute(href: string): boolean {
  return page.url === href
}

// Close menu when route changes
watch(
  () => page.url,
  () => {
    emit('close')
  }
)
</script>

<template>
  <div
    id="mobile-menu"
    :class="[
      'md:hidden overflow-hidden',
      !shouldReduceMotion && 'transition-all duration-300 ease-in-out',
      open ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0',
    ]"
  >
    <div class="space-y-1 px-4 pb-4 pt-2">
      <Link
        v-for="item in navigation"
        :key="item.name"
        :href="item.href"
        :aria-current="isCurrentRoute(item.href) ? 'page' : undefined"
        :class="[
          'block rounded-lg px-3 py-2 text-base font-medium min-h-[44px] flex items-center',
          'focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2',
          isCurrentRoute(item.href)
            ? 'bg-gray-100 text-black'
            : 'text-gray-700 hover:bg-gray-50 hover:text-black',
          !shouldReduceMotion && 'transition-colors duration-200',
        ]"
      >
        {{ item.name }}
      </Link>

      <a
        :href="`tel:${officeInfo.phone.replace(/[^0-9]/g, '')}`"
        class="block w-full text-center rounded-lg px-3 py-2.5 text-base font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 min-h-[44px] mt-4"
        :class="!shouldReduceMotion && 'transition-colors duration-200'"
      >
        {{ officeInfo.phone }}
      </a>
    </div>
  </div>
</template>
