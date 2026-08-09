<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { onMounted, ref, watch } from 'vue';

import { insights } from '@/routes/app';

type Num = number | string | null;

interface HintRow {
    platform: string;
    username: string;
    daypart: string;
    daytype: string;
    n: Num;
    n_scored: Num;
    med_views_24h: Num;
    med_er_72h: Num;
    slot_rank: Num;
    note: string | null;
}

const props = defineProps<{
    platform: string;
    username: string | null;
}>();

const hint = ref<HintRow | null>(null);
const isLoading = ref(false);

const http = useHttp<Record<string, never>, { hint: HintRow | null }>({});

const toNum = (value: Num): number => {
    if (value === null || value === undefined) return 0;
    const n = typeof value === 'number' ? value : parseInt(value, 10);
    return Number.isFinite(n) ? n : 0;
};

const fetchHint = async () => {
    if (!props.username) {
        hint.value = null;
        return;
    }

    isLoading.value = true;
    hint.value = null;

    try {
        const response = await http.get(
            insights.hint.url({ query: { platform: props.platform, username: props.username } }),
        );
        hint.value = response?.hint ?? null;
    } catch {
        hint.value = null;
    } finally {
        isLoading.value = false;
    }
};

watch(() => [props.platform, props.username], fetchHint);

onMounted(() => {
    fetchHint();
});

const daypartLabel = (daypart: string): string => trans(`insights.hint.daypart.${daypart}`);
const daytypeLabel = (daytype: string): string => trans(`insights.hint.daytype.${daytype}`);
</script>

<template>
    <span
        v-if="!isLoading && hint"
        class="inline-flex items-center gap-1 rounded-full border border-foreground/20 bg-foreground/5 px-2 py-0.5 text-[11px] font-medium text-foreground/60"
    >
        {{
            $t('insights.hint.best_slot', {
                daypart: daypartLabel(hint.daypart),
                daytype: daytypeLabel(hint.daytype),
                n: toNum(hint.n_scored),
            })
        }}
    </span>
</template>
