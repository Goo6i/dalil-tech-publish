<script setup lang="ts">
import { IconDeviceDesktop, IconMoon, IconSun } from '@tabler/icons-vue';

import { useAppearance, type Appearance } from '@/composables/useAppearance';

const { appearance, setAppearance } = useAppearance();

const options: { value: Appearance; icon: typeof IconSun; label: string }[] = [
    { value: 'light', icon: IconSun, label: 'Light' },
    { value: 'dark', icon: IconMoon, label: 'Dark' },
    { value: 'system', icon: IconDeviceDesktop, label: 'System' },
];
</script>

<template>
    <div class="mx-1 mb-1 flex items-center gap-1 rounded-md border border-sidebar-border bg-sidebar p-1">
        <button
            v-for="option in options"
            :key="option.value"
            type="button"
            :aria-label="option.label"
            :aria-pressed="appearance === option.value"
            :class="[
                'inline-flex flex-1 items-center justify-center rounded-sm p-1.5 transition-colors',
                appearance === option.value
                    ? 'bg-sidebar-accent text-sidebar-accent-foreground'
                    : 'text-muted-foreground hover:text-foreground',
            ]"
            @click="setAppearance(option.value)"
        >
            <component :is="option.icon" class="size-4" />
        </button>
    </div>
</template>
