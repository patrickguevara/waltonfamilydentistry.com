<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface OfficeInfo {
    practice_name: string;
    phone: string;
    email?: string;
    full_address: string;
    hours_array: Record<string, string | null>;
}

const page = usePage<{ officeInfo: OfficeInfo }>();
const officeInfo = computed(() => page.props.officeInfo);

const navigation = [
    { name: 'Home', href: '/' },
    { name: 'About', href: '/about' },
    { name: 'Services', href: '/services' },
    { name: 'Contact', href: '/contact' },
];

const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="border-t border-gray-200 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Practice Info -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-black">
                        {{ officeInfo.practice_name }}
                    </h3>
                    <address class="space-y-2 text-gray-700 not-italic">
                        <p>{{ officeInfo.full_address }}</p>
                        <p>
                            <a
                                :href="`tel:${officeInfo.phone.replace(/[^0-9]/g, '')}`"
                                class="rounded transition-colors duration-200 hover:text-black focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                            >
                                {{ officeInfo.phone }}
                            </a>
                        </p>
                        <p v-if="officeInfo.email">
                            <a
                                :href="`mailto:${officeInfo.email}`"
                                class="rounded transition-colors duration-200 hover:text-black focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                            >
                                {{ officeInfo.email }}
                            </a>
                        </p>
                    </address>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-black">
                        Quick Links
                    </h3>
                    <nav aria-label="Footer navigation">
                        <ul class="space-y-2">
                            <li v-for="item in navigation" :key="item.name">
                                <Link
                                    :href="item.href"
                                    class="rounded text-gray-700 transition-colors duration-200 hover:text-black focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none"
                                >
                                    {{ item.name }}
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Office Hours -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-black">
                        Office Hours
                    </h3>
                    <dl class="space-y-1 text-gray-700">
                        <div
                            v-for="(hours, day) in officeInfo.hours_array"
                            :key="day"
                            class="flex justify-between"
                        >
                            <dt class="font-medium">{{ day }}:</dt>
                            <dd>{{ hours || 'Closed' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-8 border-t border-gray-200 pt-8">
                <p class="text-center text-sm text-gray-600">
                    &copy; {{ currentYear }} {{ officeInfo.practice_name }}. All
                    rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
