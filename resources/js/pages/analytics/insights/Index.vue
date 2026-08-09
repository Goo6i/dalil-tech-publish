<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { IconAlertTriangle, IconChevronDown, IconChevronUp, IconInfoCircle, IconSelector } from '@tabler/icons-vue';
import { CurveType } from '@unovis/ts';
import { VisArea, VisAxis, VisCrosshair, VisGroupedBar, VisLine, VisTooltip, VisXYContainer } from '@unovis/vue';
import { trans } from 'laravel-vue-i18n';
import { computed, ref } from 'vue';

import InsightsTabs from '@/components/analytics/InsightsTabs.vue';
import PageHeader from '@/components/PageHeader.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Badge, type BadgeVariants } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { getPlatformLabel } from '@/composables/usePlatformLogo';
import dayjs from '@/dayjs';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatNumber } from '@/lib/utils';
import { insights } from '@/routes/app';

interface Account {
    platform: string;
    username: string;
    display_name: string | null;
    avatar_url: string | null;
}

type Num = number | string | null;

interface ScorecardRow {
    platform: string;
    username: string;
    video_id: string;
    title: string | null;
    posted_at: string;
    views: Num;
    views_24h: Num;
    er_views: Num;
    share_rate: Num;
    rank_24h: string | null;
    trajectory: string | null;
    class: string | null;
}

interface MomentumRow {
    platform: string;
    username: string;
    day: string;
    followers: Num;
    gained: Num;
    posts_that_day: Num;
}

interface DataQualityRow {
    check_name: string;
    status: string;
    detail: string;
}

interface AlertRow {
    video_id: string;
    alert_type: string;
    sent_at: string;
    title: string | null;
    platform: string;
    username: string;
}

interface Received7d {
    views?: Num;
    likes?: Num;
    comments?: Num;
    shares?: Num;
}

const props = defineProps<{
    accounts: Account[];
    scorecard: ScorecardRow[];
    momentum: MomentumRow[];
    received7d: Received7d;
    dataQuality: DataQualityRow[];
    alerts: AlertRow[];
}>();

// Postgres numeric/bigint columns can arrive as strings over the wire, so
// every arithmetic/sort touchpoint goes through this coercion rather than
// trusting the prop type.
const toNum = (value: Num | undefined): number => {
    if (value === null || value === undefined) return 0;
    const n = typeof value === 'number' ? value : parseFloat(value);
    return Number.isFinite(n) ? n : 0;
};

const accountKey = (platform: string, username: string) => `${platform}::${username}`;

const selectedKey = ref<string>('all');

const selectedLabel = computed(() => {
    if (selectedKey.value === 'all') return trans('insights.filters.all_accounts');
    const account = props.accounts.find((a) => accountKey(a.platform, a.username) === selectedKey.value);
    return account ? (account.display_name || account.username) : trans('insights.filters.all_accounts');
});

const matchesSelection = (platform: string, username: string): boolean => {
    if (selectedKey.value === 'all') return true;
    return accountKey(platform, username) === selectedKey.value;
};

const filteredScorecard = computed(() =>
    props.scorecard.filter((row) => matchesSelection(row.platform, row.username)),
);

const filteredMomentum = computed(() =>
    props.momentum.filter((row) => matchesSelection(row.platform, row.username)),
);

const filteredAlerts = computed(() =>
    props.alerts.filter((row) => matchesSelection(row.platform, row.username)),
);

// One point per day: followers and posts summed across every account still
// in scope, so the chart reads the same whether one account or all of them
// are selected.
const chartData = computed(() => {
    const byDay = new Map<string, { day: string; followers: number; gained: number; postsThatDay: number }>();

    for (const row of filteredMomentum.value) {
        const entry = byDay.get(row.day) ?? { day: row.day, followers: 0, gained: 0, postsThatDay: 0 };
        entry.followers += toNum(row.followers);
        entry.gained += toNum(row.gained);
        entry.postsThatDay += toNum(row.posts_that_day);
        byDay.set(row.day, entry);
    }

    return Array.from(byDay.values()).sort((a, b) => a.day.localeCompare(b.day));
});

const followersLatest = computed(() => {
    const days = chartData.value;
    return days.length ? days[days.length - 1].followers : 0;
});

const net7d = computed(() => {
    const last7 = [...chartData.value].sort((a, b) => b.day.localeCompare(a.day)).slice(0, 7);
    return last7.reduce((sum, d) => sum + d.gained, 0);
});

const receivedViews7d = computed(() => toNum(props.received7d?.views));

const signed = (value: number): string => `${value > 0 ? '+' : ''}${formatNumber(value)}`;

// Chart series accessors and formatting.
const chartX = (_d: unknown, i: number) => i;
const chartYFollowers = (d: { followers: number }) => d.followers;
const chartYPosts = (d: { postsThatDay: number }) => d.postsThatDay;

const chartTickFormat = (value: number): string => {
    const point = chartData.value[Math.round(value)];
    return point ? dayjs(point.day).format('MMM D') : '';
};

const chartTooltip = (d: { day: string; followers: number; postsThatDay: number }): string =>
    `<div style="font-size:12px;line-height:1.5">
        <div style="font-weight:600;margin-bottom:2px">${dayjs(d.day).format('MMM D, YYYY')}</div>
        <div style="color:#7c3aed">&#9679; ${trans('insights.chart.followers')}: ${formatNumber(d.followers)}</div>
        <div style="color:#f59e0b">&#9679; ${trans('insights.chart.posts_that_day')}: ${formatNumber(d.postsThatDay)}</div>
    </div>`;

// Scorecard sorting.
type SortKey = 'title' | 'posted_at' | 'views' | 'views_24h' | 'er_views' | 'share_rate' | 'rank_24h' | 'trajectory' | 'class';

const sortKey = ref<SortKey>('posted_at');
const sortDir = ref<'asc' | 'desc'>('desc');

const toggleSort = (key: SortKey) => {
    if (sortKey.value === key) {
        sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
        return;
    }
    sortKey.value = key;
    sortDir.value = 'desc';
};

const rankValue = (rank: string | null): number => {
    if (!rank) return Number.POSITIVE_INFINITY;
    const n = parseInt(rank.split(' ')[0], 10);
    return Number.isFinite(n) ? n : Number.POSITIVE_INFINITY;
};

const sortedScorecard = computed(() => {
    const dir = sortDir.value === 'asc' ? 1 : -1;
    const rows = [...filteredScorecard.value];

    rows.sort((a, b) => {
        switch (sortKey.value) {
            case 'title':
                return (a.title ?? '').localeCompare(b.title ?? '') * dir;
            case 'posted_at':
                return (new Date(a.posted_at).getTime() - new Date(b.posted_at).getTime()) * dir;
            case 'trajectory':
                return (a.trajectory ?? '').localeCompare(b.trajectory ?? '') * dir;
            case 'class':
                return (a.class ?? '').localeCompare(b.class ?? '') * dir;
            case 'rank_24h':
                return (rankValue(a.rank_24h) - rankValue(b.rank_24h)) * dir;
            default:
                return (toNum(a[sortKey.value]) - toNum(b[sortKey.value])) * dir;
        }
    });

    return rows;
});

const trajectoryVariant = (label: string | null): BadgeVariants['variant'] => {
    switch (label) {
        case 'active':
            return 'success';
        case 'fading':
            return 'warning';
        case 'dead':
            return 'secondary';
        default:
            return 'outline';
    }
};

const trajectoryLabel = (label: string | null): string =>
    label ? trans(`insights.chips.trajectory.${label}`) : trans('insights.chips.unknown');

const classVariant = (value: string | null): BadgeVariants['variant'] => {
    switch (value) {
        case 'spike':
            return 'warning';
        case 'word-of-mouth':
            return 'secondary';
        case 'mixed':
            return 'outline';
        default:
            return 'outline';
    }
};

const classLabel = (value: string | null): string =>
    value ? trans(`insights.chips.class.${value.replace(/-/g, '_')}`) : trans('insights.chips.unknown');

const relativeTime = (date: string): string => dayjs.utc(date).local().fromNow();

const warnRows = computed(() => props.dataQuality.filter((row) => row.status === 'WARN'));
const hasDataQualityWarning = computed(() => warnRows.value.length > 0);

const alertTypeLabel = (type: string): string =>
    type
        .split('_')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
</script>

<template>
    <Head :title="trans('insights.title')" />

    <AppLayout>
        <div class="mx-auto flex h-full w-full max-w-6xl flex-col gap-6 px-6 py-8">
            <InsightsTabs />

            <div class="flex flex-wrap items-center justify-between gap-3">
                <PageHeader :title="$t('insights.title')" :description="$t('insights.description')" />

                <Select v-if="accounts.length > 1" v-model="selectedKey">
                    <SelectTrigger class="w-full sm:w-64">
                        <SelectValue>{{ selectedLabel }}</SelectValue>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">{{ $t('insights.filters.all_accounts') }}</SelectItem>
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

            <div v-if="accounts.length === 0" class="flex flex-1 items-center justify-center text-sm font-medium text-foreground/60">
                {{ $t('insights.no_accounts') }}
            </div>

            <template v-else>
                <Alert v-if="hasDataQualityWarning" variant="destructive">
                    <IconAlertTriangle class="h-4 w-4" />
                    <AlertTitle>{{ $t('insights.data_quality.title') }}</AlertTitle>
                    <AlertDescription>
                        <ul class="list-inside list-disc space-y-0.5">
                            <li v-for="row in warnRows" :key="row.check_name">{{ row.detail }}</li>
                        </ul>
                    </AlertDescription>
                </Alert>

                <div class="grid gap-4 sm:grid-cols-3">
                    <Card>
                        <CardHeader class="pb-2">
                            <CardTitle class="text-xs font-black uppercase tracking-widest text-foreground/60">
                                {{ $t('insights.stats.followers') }}
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-foreground">{{ formatNumber(followersLatest) }}</div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="pb-2">
                            <CardTitle class="text-xs font-black uppercase tracking-widest text-foreground/60">
                                {{ $t('insights.stats.net_7d') }}
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-foreground">{{ signed(net7d) }}</div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="pb-2">
                            <CardTitle class="text-xs font-black uppercase tracking-widest text-foreground/60">
                                {{ $t('insights.stats.received_views_7d') }}
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-foreground">{{ formatNumber(receivedViews7d) }}</div>
                            <p v-if="accounts.length > 1" class="mt-1 text-xs font-medium text-foreground/60">
                                {{ $t('insights.stats.received_views_7d_scope') }}
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>{{ $t('insights.chart.title') }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="chartData.length === 0" class="flex h-48 items-center justify-center text-sm font-medium text-foreground/60">
                            {{ $t('insights.chart.no_data') }}
                        </div>
                        <template v-else>
                            <VisXYContainer :data="chartData" :height="220" :margin="{ top: 12, right: 8, bottom: 4, left: 8 }">
                                <VisArea :x="chartX" :y="chartYFollowers" color="#7c3aed" :opacity="0.08" :curve-type="CurveType.MonotoneX" />
                                <VisLine :x="chartX" :y="chartYFollowers" color="#7c3aed" :line-width="2.5" :curve-type="CurveType.MonotoneX" />
                                <VisAxis
                                    type="x"
                                    :tick-format="chartTickFormat"
                                    :num-ticks="6"
                                    :grid-line="false"
                                    :domain-line="false"
                                    :tick-line="false"
                                    color="var(--color-foreground)"
                                />
                                <VisAxis type="y" :num-ticks="3" :grid-line="false" :domain-line="false" :tick-line="false" color="var(--color-foreground)" />
                                <VisCrosshair :template="chartTooltip" color="#7c3aed" />
                                <VisTooltip />
                            </VisXYContainer>
                            <VisXYContainer :data="chartData" :height="56" :margin="{ top: 0, right: 8, bottom: 4, left: 8 }">
                                <VisGroupedBar :x="chartX" :y="chartYPosts" color="#f59e0b" />
                                <VisAxis type="y" :num-ticks="2" :grid-line="false" :domain-line="false" :tick-line="false" color="var(--color-foreground)" />
                            </VisXYContainer>
                            <p class="mt-1 text-center text-[11px] font-medium text-foreground/50">{{ $t('insights.chart.posts_that_day') }}</p>
                        </template>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{{ $t('insights.scorecard.title') }}</CardTitle>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div v-if="sortedScorecard.length === 0" class="flex h-32 items-center justify-center text-sm font-medium text-foreground/60">
                            {{ $t('insights.scorecard.no_data') }}
                        </div>
                        <Table v-else class="border-0 shadow-none">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('title')">
                                            {{ $t('insights.scorecard.columns.title') }}
                                            <IconChevronUp v-if="sortKey === 'title' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'title' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead>
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('posted_at')">
                                            {{ $t('insights.scorecard.columns.posted_at') }}
                                            <IconChevronUp v-if="sortKey === 'posted_at' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'posted_at' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead class="text-right">
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('views')">
                                            {{ $t('insights.scorecard.columns.views') }}
                                            <IconChevronUp v-if="sortKey === 'views' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'views' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead class="text-right">
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('views_24h')">
                                            {{ $t('insights.scorecard.columns.views_24h') }}
                                            <IconChevronUp v-if="sortKey === 'views_24h' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'views_24h' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead class="text-right">
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('er_views')">
                                            {{ $t('insights.scorecard.columns.er_views') }}
                                            <IconChevronUp v-if="sortKey === 'er_views' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'er_views' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead class="text-right">
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('share_rate')">
                                            {{ $t('insights.scorecard.columns.share_rate') }}
                                            <IconChevronUp v-if="sortKey === 'share_rate' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'share_rate' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead>
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('rank_24h')">
                                            {{ $t('insights.scorecard.columns.rank_24h') }}
                                            <IconChevronUp v-if="sortKey === 'rank_24h' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'rank_24h' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead>
                                        <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('trajectory')">
                                            {{ $t('insights.scorecard.columns.trajectory') }}
                                            <IconChevronUp v-if="sortKey === 'trajectory' && sortDir === 'asc'" class="size-3" />
                                            <IconChevronDown v-else-if="sortKey === 'trajectory' && sortDir === 'desc'" class="size-3" />
                                            <IconSelector v-else class="size-3 opacity-40" />
                                        </button>
                                    </TableHead>
                                    <TableHead>
                                        <div class="inline-flex items-center gap-1">
                                            <button type="button" class="inline-flex cursor-pointer items-center gap-1" @click="toggleSort('class')">
                                                {{ $t('insights.scorecard.columns.class') }}
                                                <IconChevronUp v-if="sortKey === 'class' && sortDir === 'asc'" class="size-3" />
                                                <IconChevronDown v-else-if="sortKey === 'class' && sortDir === 'desc'" class="size-3" />
                                                <IconSelector v-else class="size-3 opacity-40" />
                                            </button>
                                            <TooltipProvider :delay-duration="200">
                                                <Tooltip>
                                                    <TooltipTrigger as-child>
                                                        <IconInfoCircle class="size-3.5 shrink-0 cursor-help text-foreground/50" />
                                                    </TooltipTrigger>
                                                    <TooltipContent>
                                                        <p class="max-w-64 text-xs">{{ $t('insights.scorecard.class_experimental_tooltip') }}</p>
                                                    </TooltipContent>
                                                </Tooltip>
                                            </TooltipProvider>
                                        </div>
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="row in sortedScorecard" :key="row.video_id">
                                    <TableCell class="max-w-[220px]">
                                        <Link
                                            :href="insights.video.url(row.video_id)"
                                            class="block truncate font-medium text-foreground hover:underline"
                                        >
                                            {{ row.title || row.video_id }}
                                        </Link>
                                    </TableCell>
                                    <TableCell class="whitespace-nowrap text-foreground/70">{{ relativeTime(row.posted_at) }}</TableCell>
                                    <TableCell class="text-right tabular-nums">{{ formatNumber(toNum(row.views)) }}</TableCell>
                                    <TableCell class="text-right tabular-nums">{{ formatNumber(toNum(row.views_24h)) }}</TableCell>
                                    <TableCell class="text-right tabular-nums">{{ toNum(row.er_views) }}%</TableCell>
                                    <TableCell class="text-right tabular-nums">{{ toNum(row.share_rate) }}%</TableCell>
                                    <TableCell class="whitespace-nowrap text-foreground/70">{{ row.rank_24h || $t('insights.chips.unknown') }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="trajectoryVariant(row.trajectory)">{{ trajectoryLabel(row.trajectory) }}</Badge>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="classVariant(row.class)">{{ classLabel(row.class) }}</Badge>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{{ $t('insights.alerts.title') }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p v-if="filteredAlerts.length === 0" class="py-6 text-center text-sm font-medium text-foreground/60">
                            {{ $t('insights.alerts.empty') }}
                        </p>
                        <ul v-else class="divide-y divide-border">
                            <li v-for="alert in filteredAlerts" :key="`${alert.video_id}-${alert.alert_type}-${alert.sent_at}`" class="flex items-center justify-between gap-3 py-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <Badge variant="outline">{{ alertTypeLabel(alert.alert_type) }}</Badge>
                                    <span class="truncate text-sm font-medium text-foreground">{{ alert.title || alert.video_id }}</span>
                                </div>
                                <span class="shrink-0 text-xs font-medium text-foreground/60">{{ relativeTime(alert.sent_at) }}</span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </template>
        </div>
    </AppLayout>
</template>
