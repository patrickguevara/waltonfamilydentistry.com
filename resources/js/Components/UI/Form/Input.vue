<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  id: string
  modelValue: string
  type?: 'text' | 'email' | 'tel' | 'url' | 'password'
  placeholder?: string
  required?: boolean
  disabled?: boolean
  error?: string
  autocomplete?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  required: false,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const inputClasses = computed(() => {
  const base = [
    'block w-full rounded-lg',
    'px-4 py-2.5',
    'text-base text-black',
    'border-2',
    'placeholder:text-gray-400',
    'focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2',
    'disabled:bg-gray-100 disabled:cursor-not-allowed',
    'transition-colors duration-200',
    'min-h-[44px]', // WCAG touch target
  ]

  if (props.error) {
    base.push('border-red-500')
  } else {
    base.push('border-gray-300 focus:border-black')
  }

  return base.join(' ')
})

const ariaDescribedby = computed(() => {
  if (props.error) {
    return `${props.id}-error`
  }
  return undefined
})
</script>

<template>
  <input
    :id="id"
    :type="type"
    :value="modelValue"
    :placeholder="placeholder"
    :required="required"
    :disabled="disabled"
    :autocomplete="autocomplete"
    :aria-invalid="!!error"
    :aria-describedby="ariaDescribedby"
    :class="inputClasses"
    @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
  />
</template>
