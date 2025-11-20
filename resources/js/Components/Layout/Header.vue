<script setup lang="ts">
import { useReducedMotion } from '@/composables/useReducedMotion';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import MobileNav from './MobileNav.vue';

interface OfficeInfo {
    phone: string;
    practice_name: string;
}

const page = usePage<{ officeInfo: OfficeInfo }>();
const officeInfo = computed(() => page.props.officeInfo);

const { shouldReduceMotion } = useReducedMotion();
const mobileMenuOpen = ref(false);

const navigation = [
    { name: 'Home', href: '/' },
    { name: 'About', href: '/about' },
    { name: 'Services', href: '/services' },
    { name: 'Contact', href: '/contact' },
];

function isCurrentRoute(href: string): boolean {
    return page.url === href;
}

function toggleMobileMenu() {
    mobileMenuOpen.value = !mobileMenuOpen.value;
}
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-gray-200 bg-white">
        <nav
            class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
            aria-label="Main navigation"
        >
            <div class="flex h-20 items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link
                        href="/"
                        class="rounded-lg text-xl font-bold text-black focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                    >
                        {{ officeInfo.practice_name }}
                    </Link>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :aria-current="
                            isCurrentRoute(item.href) ? 'page' : undefined
                        "
                        :class="[
                            'rounded-lg px-3 py-2 text-base font-medium',
                            'focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none',
                            isCurrentRoute(item.href)
                                ? 'text-black underline underline-offset-4'
                                : 'text-gray-700 hover:text-black',
                            !shouldReduceMotion &&
                                'transition-colors duration-200',
                        ]"
                    >
                        {{ item.name }}
                    </Link>
                </div>

                <!-- Phone Number (Desktop) -->
                <div class="hidden md:block">
                    <a
                        :href="`tel:${officeInfo.phone.replace(/[^0-9]/g, '')}`"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-black px-6 py-2.5 text-base font-medium text-white hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        :class="
                            !shouldReduceMotion &&
                            'transition-colors duration-200'
                        "
                    >
                        {{ officeInfo.phone }}
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex md:hidden">
                    <button
                        type="button"
                        class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg p-2 text-gray-700 hover:bg-gray-100 hover:text-black focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                        :class="
                            !shouldReduceMotion &&
                            'transition-colors duration-200'
                        "
                        :aria-expanded="mobileMenuOpen"
                        aria-controls="mobile-menu"
                        @click="toggleMobileMenu"
                    >
                        <span class="sr-only">{{
                            mobileMenuOpen ? 'Close menu' : 'Open menu'
                        }}</span>
                        <!-- Hamburger icon -->
                        <svg
                            class="h-6 w-6"
                            :class="mobileMenuOpen && 'hidden'"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                        <!-- Close icon -->
                        <svg
                            class="h-6 w-6"
                            :class="!mobileMenuOpen && 'hidden'"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Navigation -->
        <MobileNav
            :open="mobileMenuOpen"
            :navigation="navigation"
            :office-info="officeInfo"
            @close="mobileMenuOpen = false"
        />
    </header>
</template>
