import { onMounted, onUnmounted, ref } from 'vue'
import { useAccessibility } from './useAccessibility'

export function useFocusTrap(containerRef: globalThis.Ref<HTMLElement | null>) {
  const { getFocusableElements } = useAccessibility()
  const previouslyFocusedElement = ref<HTMLElement | null>(null)

  function trapFocus(event: KeyboardEvent) {
    if (event.key !== 'Tab' || !containerRef.value) return

    const focusableElements = getFocusableElements(containerRef.value)
    const firstElement = focusableElements[0]
    const lastElement = focusableElements[focusableElements.length - 1]

    if (event.shiftKey) {
      // Shift + Tab
      if (document.activeElement === firstElement) {
        event.preventDefault()
        lastElement?.focus()
      }
    } else {
      // Tab
      if (document.activeElement === lastElement) {
        event.preventDefault()
        firstElement?.focus()
      }
    }
  }

  function activate() {
    previouslyFocusedElement.value = document.activeElement as HTMLElement

    if (containerRef.value) {
      const focusableElements = getFocusableElements(containerRef.value)
      focusableElements[0]?.focus()
    }

    document.addEventListener('keydown', trapFocus)
  }

  function deactivate() {
    document.removeEventListener('keydown', trapFocus)
    previouslyFocusedElement.value?.focus()
  }

  onMounted(() => {
    activate()
  })

  onUnmounted(() => {
    deactivate()
  })

  return {
    activate,
    deactivate,
  }
}
