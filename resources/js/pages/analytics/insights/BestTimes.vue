<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed, ref } from 'vue';

import InsightsTabs from '@/components/analytics/InsightsTabs.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { getPlatformLabel } from '@/composables/usePlatformLogo';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatNumber } from '@/lib/utils';

interface Account {
    platform: string;
    username: string;
    display_name: string | null;
    avatar_url: string | null;
}

type Num = number | string | null;

interface BestTimeCell {
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
    accounts: Account[];
    cells: BestTimeCell[];
}>();

const dayparts = ['morning', 'afternoon', 'evening', 'night'] as const;
const daytypes = ['weekday', 'weekend'] as const;

// Postgres numeric/bigint columns can arrive as strings over the wire, so
// every arithmetic touchpoint goes through this coercion rather than
// trusting the prop type.
const toNum = (value: Num): number => {
    if (value === null || value === undefined) return 0;
    const n = typeof value === 'number' ? value : parseFloat(value);
    return Number.isFinite(n) ? n : 0;
};

const accountKey = (platform: string, username: string) => `${platform}::${username}`;

const selectedKey = ref<string>(
    props.accounts[0] ? accountKey(props.accounts[0].platform, props.accounts[0].username) : '',
);

const selectedAccount = computed(() =>
    props.accounts.find((a) => accountKey(a.platform, a.username) === selectedKey.value) ?? null,
);

const selectedLabel = computed(() => {
    const account = selectedAccount.value;
    return account ? (account.display_name || account.username) : '';
});

const cellsForSelectedAccount = computed(() => {
    const account = selectedAccount.value;
    if (!account) return [];
    return props.cells.filter((c) => c.platform === account.platform && c.username === account.username);
});

const cellFor = (daypart: string, daytype: string): BestTimeCell | undefined =>
    cellsForSelectedAccount.value.find((c) => c.daypart === daypart && c.daytype === daytype);

interface GridRow {
    daypart: string;
    cells: (BestTimeCell | undefined)[];
}

const gridRows = computed<GridRow[]>(() =>
    dayparts.map((daypart) => ({
        daypart,
        cells: daytypes.map((daytype) => cellFor(daypart, daytype)),
    })),
);

const daypartLabel = (daypart: string): string => trans(`insights.best_times.dayparts.${daypart}`);

const isRanked = (cell: BestTimeCell): boolean => cell.slot_rank !== null && cell.slot_rank !== undefined;
const isBest = (cell: BestTimeCell): boolean => toNum(cell.slot_rank) === 1;
</script>

<template>
    <Head :title="trans('insights.best_times.title')" />

    <AppLayout>
        <div class="mx-auto flex h-full w-full max-w-6xl flex-col gap-6 px-6 py-8">
            <InsightsTabs />

            <div class="flex flex-wrap items-center justify-between gap-3">
                <PageHeader :title="$t('insights.best_times.title')" :description="$t('insights.best_times.description')" />

                <Select v-if="accounts.length > 1" v-model="selectedKey">
                    <SelectTrigger class="w-full sm:w-64">
                        <SelectValue>{{ selectedLabel }}</SelectValue>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="account in accounts"
                            :key="accountKey(account.platform, account.username)"
                            :value="accountKey(account.platform, account.username)"
                        >
                            {{ account.display_name || account.username }} ({{ getPlatformLabel(account.platform) }})
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div v-if="accounts.length === 0" class="flex flex-1 items-center justify-center text-sm font-medium text-muted-foreground">
                {{ $t('insights.no_accounts') }}
            </div>

            <Card v-else>
                <CardContent class="p-0">
                    <Table class="border-0 shadow-none">
                        <TableHeader>
                            <TableRow>
                                <TableHead></TableHead>
                                <TableHead class="text-center">{{ $t('insights.best_times.columns.weekday') }}</TableHead>
                                <TableHead class="text-center">{{ $t('insights.best_times.columns.weekend') }}</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="row in gridRows" :key="row.daypart">
                                <TableCell class="whitespace-nowrap font-bold text-foreground">{{ daypartLabel(row.daypart) }}</TableCell>
                                <TableCell v-for="(cell, index) in row.cells" :key="daytypes[index]" class="align-top">
                                    <div
                                        v-if="cell"
                                        class="rounded-xl border-2 p-3"
                                        :class="{
                                            'border-foreground bg-amber-100': isBest(cell),
                                            'border-foreground bg-violet-50': isRanked(cell) && !isBest(cell),
                                            'border-dashed border-foreground/30 bg-transparent': !isRanked(cell),
                                        }"
                                    >
                                        <template v-if="isRanked(cell)">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-lg font-bold tabular-nums text-neutral-900">
                                                    {{ formatNumber(toNum(cell.med_views_24h)) }}
                                                </span>
                                                <Badge v-if="isBest(cell)" variant="success">
                                                    {{ $t('insights.best_times.cell.best') }}
                                                </Badge>
                                            </div>
                                            <p class="mt-0.5 text-[11px] font-medium text-neutral-700">
                                                {{ $t('insights.best_times.cell.median_views_24h') }}
                                                &middot;
                                                {{ $t('insights.best_times.cell.n', { n: toNum(cell.n_scored) }) }}
                                            </p>
                                        </template>
                                        <p v-else class="text-xs font-medium text-subtle-foreground">
                                            {{ cell.note }}
                                        </p>
                                    </div>
                                    <p v-else class="text-xs font-medium italic text-subtle-foreground">
                                        {{ $t('insights.best_times.cell.empty') }}
                                    </p>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
