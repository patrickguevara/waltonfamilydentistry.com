<script setup lang="ts">
import { useReducedMotion } from '@/composables/useReducedMotion';
import { Link, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

interface Props {
    open: boolean;
    navigation: Array<{ name: string; href: string }>;
    officeInfo: { phone: string };
}

const { open, navigation, officeInfo } = defineProps<Props>();
const emit = defineEmits<{ close: [] }>();

const page = usePage();
const { shouldReduceMotion } = useReducedMotion();

function isCurrentRoute(href: string): boolean {
    return page.url === href;
}

// Close menu when route changes
watch(
    () => page.url,
    () => {
        emit('close');
    },
);
</script>

<template>
    <div
        id="mobile-menu"
        :class="[
            'overflow-hidden md:hidden',
            !shouldReduceMotion && 'transition-all duration-300 ease-in-out',
            open ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0',
        ]"
    >
        <div class="space-y-1 px-4 pt-2 pb-4">
            <Link
                v-for="item in navigation"
                :key="item.name"
                :href="item.href"
                :aria-current="isCurrentRoute(item.href) ? 'page' : undefined"
                :class="[
                    'block flex min-h-[44px] items-center rounded-lg px-3 py-2 text-base font-medium',
                    'focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none',
                    isCurrentRoute(item.href)
                        ? 'bg-gray-100 text-black'
                        : 'text-gray-700 hover:bg-gray-50 hover:text-black',
                    !shouldReduceMotion && 'transition-colors duration-200',
                ]"
            >
                {{ item.name }}
            </Link>

            <a
                :href="`tel:${officeInfo.phone.replace(/[^0-9]/g, '')}`"
                class="mt-4 block min-h-[44px] w-full rounded-lg bg-black px-3 py-2.5 text-center text-base font-medium text-white hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                :class="!shouldReduceMotion && 'transition-colors duration-200'"
            >
                {{ officeInfo.phone }}
            </a>
        </div>
    </div>
</template>
