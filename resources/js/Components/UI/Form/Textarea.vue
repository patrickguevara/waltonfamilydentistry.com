<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  id: string
  modelValue: string
  placeholder?: string
  required?: boolean
  disabled?: boolean
  error?: string
  rows?: number
}

const props = withDefaults(defineProps<Props>(), {
  required: false,
  disabled: false,
  rows: 4,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const textareaClasses = computed(() => {
  const base = [
    'block w-full rounded-lg',
    'px-4 py-2.5',
    'text-base text-black',
    'border-2',
    'placeholder:text-gray-400',
    'focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2',
    'disabled:bg-gray-100 disabled:cursor-not-allowed',
    'transition-colors duration-200',
    'resize-vertical',
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
  <textarea
    :id="id"
    :value="modelValue"
    :placeholder="placeholder"
    :required="required"
    :disabled="disabled"
    :rows="rows"
    :aria-invalid="!!error"
    :aria-describedby="ariaDescribedby"
    :class="textareaClasses"
    @input="emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
  />
</template>
