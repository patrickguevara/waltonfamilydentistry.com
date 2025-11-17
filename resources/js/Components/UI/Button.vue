<script setup lang="ts">
import { computed } from 'vue'
import { useReducedMotion } from '@/composables/useReducedMotion'

interface Props {
  variant?: 'primary' | 'secondary' | 'ghost'
  size?: 'sm' | 'md' | 'lg'
  as?: 'button' | 'a'
  href?: string
  disabled?: boolean
  type?: 'button' | 'submit' | 'reset'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  as: 'button',
  type: 'button',
  disabled: false,
})

const { shouldReduceMotion } = useReducedMotion()

const classes = computed(() => {
  const base = [
    'inline-flex items-center justify-center',
    'font-medium',
    'rounded-lg',
    'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black',
    'disabled:opacity-50 disabled:cursor-not-allowed',
  ]

  if (!shouldReduceMotion.value) {
    base.push('transition-all duration-200')
  }

  // Variant styles
  const variants = {
    primary: [
      'bg-black text-white',
      'hover:bg-gray-800',
      'active:bg-gray-900',
    ],
    secondary: [
      'bg-white text-black border-2 border-black',
      'hover:bg-gray-50',
      'active:bg-gray-100',
    ],
    ghost: [
      'bg-transparent text-black',
      'hover:bg-gray-100',
      'active:bg-gray-200',
    ],
  }

  // Size styles
  const sizes = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-base',
    lg: 'px-6 py-3 text-lg min-h-[44px]', // WCAG touch target
  }

  return [...base, ...variants[props.variant], sizes[props.size]].join(' ')
})

const component = computed(() => (props.as === 'a' ? 'a' : 'button'))
</script>

<template>
  <component
    :is="component"
    :type="as === 'button' ? type : undefined"
    :href="as === 'a' ? href : undefined"
    :disabled="disabled"
    :class="classes"
  >
    <slot />
  </component>
</template>
