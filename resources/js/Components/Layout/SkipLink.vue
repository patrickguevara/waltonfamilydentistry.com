<script setup lang="ts">
function skipToMain() {
    const main = document.getElementById('main-content');
    if (main) {
        main.focus();
        main.scrollIntoView();
    }
}
</script>

<template>
    <a
        href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-lg focus:bg-black focus:px-6 focus:py-3 focus:text-white focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-black"
        @click.prevent="skipToMain"
    >
        Skip to main content
    </a>
</template>

<style scoped>
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

.focus\:not-sr-only:focus {
    position: static;
    width: auto;
    height: auto;
    padding: revert;
    margin: revert;
    overflow: visible;
    clip: auto;
    white-space: normal;
}
</style>
