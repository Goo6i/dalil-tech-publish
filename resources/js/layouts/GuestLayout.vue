<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import dayjs from '@/dayjs';

defineProps<{
    title?: string;
}>();

const currentYear = dayjs().year();
</script>

<template>
    <div class="min-h-svh bg-background">
        <!-- Header -->
        <header class="border-b">
            <div class="mx-auto max-w-5xl px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <AppLogo />
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto max-w-5xl px-6 py-12">
            <h1 v-if="title" class="text-3xl font-bold mb-8">{{ title }}</h1>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t mt-auto">
            <div class="mx-auto max-w-5xl px-6 py-8">
                <p class="text-sm text-muted-foreground text-center">
                    &copy; {{ currentYear }} Dalil Tech. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>
