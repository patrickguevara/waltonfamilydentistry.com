<script setup lang="ts">
import { computed } from 'vue'
import { useReducedMotion } from '@/composables/useReducedMotion'

interface Props {
  as?: 'div' | 'article' | 'section'
  hoverable?: boolean
  padding?: 'none' | 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  as: 'div',
  hoverable: false,
  padding: 'md',
})

const { shouldReduceMotion } = useReducedMotion()

const classes = computed(() => {
  const base = ['bg-white rounded-lg']

  // Padding
  const paddings = {
    none: '',
    sm: 'p-4',
    md: 'p-6',
    lg: 'p-8',
  }

  if (props.hoverable) {
    base.push('shadow-sm hover:shadow-md')
    if (!shouldReduceMotion.value) {
      base.push('transition-shadow duration-200')
    }
  } else {
    base.push('shadow-sm')
  }

  return [...base, paddings[props.padding]].join(' ')
})
</script>

<template>
  <component :is="as" :class="classes">
    <slot />
  </component>
</template>
