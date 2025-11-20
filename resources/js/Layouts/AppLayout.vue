<script setup lang="ts">
import Footer from '@/Components/Layout/Footer.vue';
import Header from '@/Components/Layout/Header.vue';
import SkipLink from '@/Components/Layout/SkipLink.vue';
import { Head } from '@inertiajs/vue3';

interface Props {
    title?: string;
    description?: string;
}

withDefaults(defineProps<Props>(), {
    title: 'Walton Family Dentistry',
    description: 'Quality family dental care in Austin, TX',
});
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white">
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
