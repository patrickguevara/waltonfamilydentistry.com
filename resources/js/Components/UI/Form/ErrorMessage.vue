<script setup lang="ts">
interface Props {
  id: string
  error?: string
}

defineProps<Props>()
</script>

<template>
  <p
    v-if="error"
    :id="id"
    class="mt-1.5 text-sm text-red-600"
    role="alert"
  >
    {{ error }}
  </p>
</template>
