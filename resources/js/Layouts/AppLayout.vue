<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import SkipLink from '@/Components/Layout/SkipLink.vue'
import Header from '@/Components/Layout/Header.vue'
import Footer from '@/Components/Layout/Footer.vue'

interface Props {
  title?: string
  description?: string
}

withDefaults(defineProps<Props>(), {
  title: 'Walton Family Dentistry',
  description: 'Quality family dental care in Austin, TX',
})
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Head>
      <title>{{ title }}</title>
      <meta name="description" :content="description" />
    </Head>

    <SkipLink />
    <Header />

    <main id="main-content" tabindex="-1" class="flex-1 focus:outline-none">
      <slot />
    </main>

    <Footer />
  </div>
</template>
