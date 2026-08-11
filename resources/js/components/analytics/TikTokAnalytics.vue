<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed, onMounted, ref, watch } from 'vue';

import MetricsGrid from '@/components/analytics/MetricsGrid.vue';
import dayjs from '@/dayjs';
import { show as showAnalytics } from '@/routes/app/analytics';

interface MetricItem {
    label: string;
    value: number;
}

const props = defineProps<{
    accountId: string;
}>();

const metrics = ref<MetricItem[]>([]);
const fetchedAt = ref<string | null>(null);
const isLoading = ref(false);

const http = useHttp<Record<string, never>, { metrics: MetricItem[]; fetched_at: string | null }>({});

const fetchMetrics = async () => {
    isLoading.value = true;
    metrics.value = [];

    try {
        const response = await http.get(showAnalytics.url(props.accountId));
        metrics.value = response?.metrics || [];
        fetchedAt.value = response?.fetched_at || null;
    } catch {
        metrics.value = [];
        fetchedAt.value = null;
    } finally {
        isLoading.value = false;
    }
};

const lastUpdatedLabel = computed(() => (fetchedAt.value ? dayjs(fetchedAt.value).fromNow() : null));

watch(
    () => props.accountId,
    () => {
        fetchMetrics();
    },
);

onMounted(() => {
    fetchMetrics();
});

defineExpose({ supportsDateRange: false });
</script>

<template>
    <div class="flex flex-col gap-3">
        <p v-if="lastUpdatedLabel && !isLoading" class="text-xs font-medium text-muted-foreground">
            {{ trans('analytics.last_updated') }}: {{ lastUpdatedLabel }}
        </p>
        <MetricsGrid :metrics="metrics" :loading="isLoading" :empty-label="trans('analytics.no_data')" />
    </div>
</template>
