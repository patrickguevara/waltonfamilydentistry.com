import { useMediaQuery } from '@vueuse/core'
import { computed } from 'vue'

export function useReducedMotion() {
  const prefersReducedMotion = useMediaQuery('(prefers-reduced-motion: reduce)')

  const shouldReduceMotion = computed(() => prefersReducedMotion.value)

  return {
    shouldReduceMotion,
    prefersReducedMotion,
  }
}
