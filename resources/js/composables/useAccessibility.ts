import { nextTick } from 'vue'

export function useAccessibility() {
  /**
   * Announce a message to screen readers using an ARIA live region
   */
  function announceToScreenReader(message: string, priority: 'polite' | 'assertive' = 'polite') {
    const announcement = document.createElement('div')
    announcement.setAttribute('role', 'status')
    announcement.setAttribute('aria-live', priority)
    announcement.setAttribute('aria-atomic', 'true')
    announcement.className = 'sr-only'
    announcement.textContent = message

    document.body.appendChild(announcement)

    setTimeout(() => {
      document.body.removeChild(announcement)
    }, 1000)
  }

  /**
   * Focus the first element with an error in a form
   */
  async function focusFirstError(formElement?: HTMLElement) {
    await nextTick()

    const errorElement =
      formElement?.querySelector('[aria-invalid="true"]') ||
      document.querySelector('[aria-invalid="true"]')

    if (errorElement instanceof HTMLElement) {
      errorElement.focus()
    }
  }

  /**
   * Get focusable elements within a container
   */
  function getFocusableElements(container: HTMLElement): HTMLElement[] {
    const focusableSelectors = [
      'a[href]',
      'button:not([disabled])',
      'textarea:not([disabled])',
      'input:not([disabled])',
      'select:not([disabled])',
      '[tabindex]:not([tabindex="-1"])',
    ]

    return Array.from(container.querySelectorAll(focusableSelectors.join(',')))
  }

  return {
    announceToScreenReader,
    focusFirstError,
    getFocusableElements,
  }
}
