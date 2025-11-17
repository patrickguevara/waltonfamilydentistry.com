<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  name: string
  size?: 'sm' | 'md' | 'lg'
  decorative?: boolean
  label?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  decorative: false,
})

const sizes = {
  sm: 'w-4 h-4',
  md: 'w-6 h-6',
  lg: 'w-8 h-8',
}

const sizeClass = computed(() => sizes[props.size])

// For decorative icons, use aria-hidden
// For meaningful icons, use aria-label
const ariaAttrs = computed(() => {
  if (props.decorative) {
    return { 'aria-hidden': 'true' }
  }
  if (props.label) {
    return { 'aria-label': props.label, role: 'img' }
  }
  return {}
})
</script>

<template>
  <span :class="['inline-flex', sizeClass]" v-bind="ariaAttrs">
    <slot />
  </span>
</template>
