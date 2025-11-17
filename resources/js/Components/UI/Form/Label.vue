<script setup lang="ts">
interface Props {
  for: string
  required?: boolean
}

defineProps<Props>()
</script>

<template>
  <label
    :for="for"
    class="block text-sm font-medium text-black mb-1.5"
  >
    <slot />
    <span v-if="required" class="text-black" aria-label="required">*</span>
  </label>
</template>
