<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { useActiveUrl } from '@/composables/useActiveUrl';
import { cn } from '@/lib/utils';
import { analytics, insights } from '@/routes/app';

const { urlIsActive } = useActiveUrl();

const tabClass = (active: boolean) =>
    cn(
        'inline-flex h-10 cursor-pointer items-center justify-center gap-1.5 whitespace-nowrap rounded-md border-2 border-foreground px-3 text-sm font-bold text-foreground shadow-xs transition-all hover:shadow-sm',
        active ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-card hover:bg-accent',
    );
</script>

<template>
    <nav
        class="inline-flex w-fit max-w-full items-center gap-2 overflow-x-auto p-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        :aria-label="$t('insights.tabs.nav_label')"
    >
        <Link :href="analytics.url()" :class="tabClass(urlIsActive(analytics.url(), { exact: true }))">
            {{ $t('insights.tabs.live') }}
        </Link>
        <Link :href="insights.url()" :class="tabClass(urlIsActive(insights.url(), { exclude: [insights.best_times.url()] }))">
            {{ $t('insights.tabs.insights') }}
        </Link>
        <Link :href="insights.best_times.url()" :class="tabClass(urlIsActive(insights.best_times.url(), { exact: true }))">
            {{ $t('insights.tabs.best_times') }}
        </Link>
    </nav>
</template>
