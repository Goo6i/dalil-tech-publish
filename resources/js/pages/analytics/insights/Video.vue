<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { IconArrowLeft, IconInfoCircle } from '@tabler/icons-vue';
import { CurveType } from '@unovis/ts';
import { VisArea, VisAxis, VisCrosshair, VisLine, VisScatter, VisTooltip, VisXYContainer } from '@unovis/vue';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

import InsightsTabs from '@/components/analytics/InsightsTabs.vue';
import { Badge, type BadgeVariants } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { getPlatformLabel, getPlatformLogo } from '@/composables/usePlatformLogo';
import dayjs from '@/dayjs';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatNumber } from '@/lib/utils';
import { insights } from '@/routes/app';

type Num = number | string | null;

interface VideoRow {
    platform: string;
    username: string;
    video_id: string;
    title: string | null;
    posted_at: string;
    like_rate: Num;
    comment_rate: Num;
    share_rate: Num;
    class: string | null;
    peak_share_f: Num;
    trajectory: string | null;
    followers_earned: Num;
    followers_per_1k_views: Num;
    attribution_confidence: Num;
}

interface TrajectoryPoint {
    captured_at: string;
    age_h: Num;
    d_views: Num;
    views_per_hour: Num;
}

interface AtAgeRow {
    anchor_h: Num;
    views: Num;
    likes: Num;
    comments: Num;
    shares: Num;
    age_at_measure_h: Num;
}

interface Expectation {
    views_24h: Num;
    expected_7d_low: Num;
    expected_7d_high: Num;
    actual_7d: Num;
    training_n: Num;
    trust: string;
}

const props = defineProps<{
    video: VideoRow;
    trajectory: TrajectoryPoint[];
    atAge: AtAgeRow[];
    expectation: Expectation | null;
}>();

// Postgres numeric/bigint columns can arrive as strings over the wire, so
// every arithmetic/display touchpoint goes through this coercion rather than
// trusting the prop type.
const toNum = (value: Num | undefined): number => {
    if (value === null || value === undefined) return 0;
    const n = typeof value === 'number' ? value : parseFloat(value);
    return Number.isFinite(n) ? n : 0;
};

const isPresent = (value: Num | undefined): boolean => value !== null && value !== undefined;

const toPercent = (value: Num): string => `${Math.round(toNum(value) * 100)}%`;

const pageTitle = computed(() => props.video.title || props.video.video_id);

const postedRelative = computed(() => dayjs.utc(props.video.posted_at).local().fromNow());

const platformLabel = computed(() => getPlatformLabel(props.video.platform));

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

// Trajectory chart: views_per_hour vs age_h. The first snapshot of a video
// carries NULL deltas (no prior snapshot to diff against), so those rows are
// dropped rather than coerced into a false zero.
const trajectoryPoints = computed(() =>
    props.trajectory
        .filter((d) => isPresent(d.views_per_hour) && isPresent(d.age_h))
        .map((d) => ({ age_h: toNum(d.age_h), views_per_hour: toNum(d.views_per_hour) }))
        .sort((a, b) => a.age_h - b.age_h),
);

const peakIndex = computed(() => {
    const points = trajectoryPoints.value;
    if (points.length === 0) return -1;
    let idx = 0;
    for (let i = 1; i < points.length; i++) {
        if (points[i].views_per_hour > points[idx].views_per_hour) idx = i;
    }
    return idx;
});

const peakPoint = computed(() => (peakIndex.value >= 0 ? trajectoryPoints.value[peakIndex.value] : null));

const trajectoryX = (d: { age_h: number }) => d.age_h;
const trajectoryY = (d: { views_per_hour: number }) => d.views_per_hour;

const trajectoryTickFormat = (value: number): string => `${Math.round(value)}h`;

const trajectoryTooltip = (d: { age_h: number; views_per_hour: number }): string =>
    `<div style="font-size:12px;line-height:1.5">
        <div style="font-weight:600;margin-bottom:2px">${trajectoryTickFormat(d.age_h)}</div>
        <div style="color:#7c3aed">&#9679; ${trans('insights.video.trajectory.views_per_hour')}: ${formatNumber(d.views_per_hour)}</div>
    </div>`;

const peakScatterSize = (_d: unknown, i: number) => (i === peakIndex.value ? 7 : 0);
const peakScatterColor = () => '#f59e0b';
const peakScatterLabel = (_d: unknown, i: number) => (i === peakIndex.value ? trans('insights.video.trajectory.peak_marker') : '');

// Milestone chips (video_at_age): one per anchor with an observed snapshot.
// age_at_measure_h is the jitter around the anchor (nearest snapshot within
// +-1.5h), surfaced in a tooltip so it never masquerades as an exact reading.
const anchorLabel = (anchorH: Num): string => `${Math.round(toNum(anchorH))}h`;

const measuredAtLabel = (ageAtMeasureH: Num): string =>
    `${trans('insights.video.milestones.measured_at_label')} ${toNum(ageAtMeasureH).toFixed(1)}h`;

// Expectation band (video_expectation): NULL until training_n >= 3.
const hasExpectationBand = computed(
    () => props.expectation !== null && toNum(props.expectation.training_n) >= 3 && isPresent(props.expectation.expected_7d_low),
);

const hasActual7d = computed(() => props.expectation !== null && isPresent(props.expectation.actual_7d));

// Class card (video_class): peak_share_f/class are NULL when the video's
// early life predates collection, so the series is honestly incomplete.
const hasClass = computed(() => props.video.class !== null && props.video.class !== undefined);
</script>

<template>
    <Head :title="pageTitle" />

    <AppLayout>
        <div class="mx-auto flex h-full w-full max-w-6xl flex-col gap-6 px-6 py-8">
            <InsightsTabs />

            <div class="flex flex-col gap-3">
                <Link
                    :href="insights.url()"
                    class="inline-flex w-fit items-center gap-1 text-sm font-medium text-foreground/60 hover:text-foreground hover:underline"
                >
                    <IconArrowLeft class="size-4" />
                    {{ $t('insights.title') }}
                </Link>

                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="space-y-1">
                        <h1 class="text-2xl font-semibold leading-tight text-foreground sm:text-4xl" style="font-family: var(--font-display)">
                            {{ pageTitle }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-foreground/70">
                            <span>{{ $t('insights.video.posted_label') }} {{ postedRelative }}</span>
                            <span class="inline-flex items-center gap-1.5">
                                <img :src="getPlatformLogo(video.platform)" :alt="platformLabel" class="size-4 rounded-sm" />
                                {{ platformLabel }} &middot; {{ video.username }}
                            </span>
                        </div>
                    </div>
                    <Badge v-if="video.trajectory" :variant="trajectoryVariant(video.trajectory)">{{ trajectoryLabel(video.trajectory) }}</Badge>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>{{ $t('insights.video.trajectory.title') }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="trajectoryPoints.length === 0" class="flex h-48 items-center justify-center text-sm font-medium text-foreground/60">
                        {{ $t('insights.video.trajectory.no_data') }}
                    </div>
                    <template v-else>
                        <VisXYContainer :data="trajectoryPoints" :height="240" :margin="{ top: 16, right: 8, bottom: 4, left: 8 }">
                            <VisArea :x="trajectoryX" :y="trajectoryY" color="#7c3aed" :opacity="0.08" :curve-type="CurveType.MonotoneX" />
                            <VisLine :x="trajectoryX" :y="trajectoryY" color="#7c3aed" :line-width="2.5" :curve-type="CurveType.MonotoneX" />
                            <VisScatter
                                :x="trajectoryX"
                                :y="trajectoryY"
                                :size="peakScatterSize"
                                :color="peakScatterColor"
                                :label="peakScatterLabel"
                                label-position="top"
                            />
                            <VisAxis
                                type="x"
                                :tick-format="trajectoryTickFormat"
                                :num-ticks="6"
                                :grid-line="false"
                                :domain-line="false"
                                :tick-line="false"
                                color="var(--color-foreground)"
                            />
                            <VisAxis type="y" :num-ticks="3" :grid-line="false" :domain-line="false" :tick-line="false" color="var(--color-foreground)" />
                            <VisCrosshair :template="trajectoryTooltip" color="#7c3aed" />
                            <VisTooltip />
                        </VisXYContainer>
                        <p v-if="peakPoint" class="mt-1 text-center text-[11px] font-medium text-foreground/50">
                            {{ $t('insights.video.trajectory.peak_marker') }}: {{ formatNumber(peakPoint.views_per_hour) }}
                            {{ $t('insights.video.trajectory.per_hour') }} @ {{ trajectoryTickFormat(peakPoint.age_h) }}
                        </p>
                    </template>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>{{ $t('insights.video.milestones.title') }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <p v-if="atAge.length === 0" class="py-4 text-center text-sm font-medium text-foreground/60">
                        {{ $t('insights.video.milestones.no_data') }}
                    </p>
                    <TooltipProvider v-else :delay-duration="200">
                        <div class="flex flex-wrap gap-2">
                            <Tooltip v-for="row in atAge" :key="String(row.anchor_h)">
                                <TooltipTrigger as-child>
                                    <Badge variant="outline" class="cursor-help">
                                        {{ anchorLabel(row.anchor_h) }}: {{ formatNumber(toNum(row.views)) }}
                                    </Badge>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p class="text-xs">{{ measuredAtLabel(row.age_at_measure_h) }}</p>
                                </TooltipContent>
                            </Tooltip>
                        </div>
                    </TooltipProvider>
                </CardContent>
            </Card>

            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            {{ $t('insights.video.expectation.title') }}
                            <Badge variant="warning">{{ $t('insights.video.experimental_badge') }}</Badge>
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <template v-if="hasExpectationBand && expectation">
                            <div class="text-lg font-bold text-foreground">
                                {{ formatNumber(toNum(expectation.expected_7d_low)) }} &ndash; {{ formatNumber(toNum(expectation.expected_7d_high)) }}
                            </div>
                            <p class="text-xs font-medium text-foreground/60">{{ $t('insights.video.expectation.band_label') }}</p>
                            <p v-if="hasActual7d" class="text-sm font-medium text-foreground">
                                {{ $t('insights.video.expectation.actual_label') }}: {{ formatNumber(toNum(expectation.actual_7d)) }}
                            </p>
                            <p class="text-xs font-medium text-foreground/60">
                                {{ $t('insights.video.expectation.training_n_label') }}: {{ toNum(expectation.training_n) }}
                            </p>
                            <p class="text-xs text-foreground/60">{{ $t('insights.video.expectation.explanation') }}</p>
                        </template>
                        <p v-else class="text-sm font-medium text-foreground/60">
                            {{ $t('insights.video.expectation.insufficient_data') }}
                            <template v-if="expectation">({{ toNum(expectation.training_n) }})</template>
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            {{ $t('insights.video.class.title') }}
                            <Badge variant="warning">{{ $t('insights.video.experimental_badge') }}</Badge>
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <template v-if="hasClass">
                            <Badge :variant="classVariant(video.class)">{{ classLabel(video.class) }}</Badge>
                            <p class="text-sm font-medium text-foreground">
                                {{ $t('insights.video.class.peak_share_label') }}: {{ toPercent(video.peak_share_f) }}
                            </p>
                            <p class="text-xs text-foreground/60">{{ $t('insights.video.class.explanation') }}</p>
                        </template>
                        <p v-else class="text-sm font-medium text-foreground/60">{{ $t('insights.video.class.insufficient_data') }}</p>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>{{ $t('insights.video.mix.title') }}</CardTitle>
                </CardHeader>
                <CardContent class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-foreground/60">{{ $t('insights.video.mix.like_rate') }}</p>
                        <p class="text-xl font-bold text-foreground">{{ toNum(video.like_rate) }}%</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-foreground/60">{{ $t('insights.video.mix.comment_rate') }}</p>
                        <p class="text-xl font-bold text-foreground">{{ toNum(video.comment_rate) }}%</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-foreground/60">{{ $t('insights.video.mix.share_rate') }}</p>
                        <p class="text-xl font-bold text-foreground">{{ toNum(video.share_rate) }}%</p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        {{ $t('insights.video.attribution.title') }}
                        <TooltipProvider :delay-duration="200">
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <IconInfoCircle class="size-3.5 shrink-0 cursor-help text-foreground/50" />
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p class="max-w-64 text-xs">{{ $t('insights.video.attribution.disclaimer') }}</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-foreground/60">
                                {{ $t('insights.video.attribution.followers_earned') }}
                            </p>
                            <p class="text-xl font-bold text-foreground">
                                {{ isPresent(video.followers_earned) ? formatNumber(toNum(video.followers_earned)) : $t('insights.chips.unknown') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-foreground/60">
                                {{ $t('insights.video.attribution.followers_per_1k_views') }}
                            </p>
                            <p class="text-xl font-bold text-foreground">
                                {{
                                    isPresent(video.followers_per_1k_views)
                                        ? formatNumber(toNum(video.followers_per_1k_views))
                                        : $t('insights.chips.unknown')
                                }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-foreground/60">
                                {{ $t('insights.video.attribution.avg_confidence') }}
                            </p>
                            <p class="text-xl font-bold text-foreground">
                                {{ isPresent(video.attribution_confidence) ? toPercent(video.attribution_confidence) : $t('insights.chips.unknown') }}
                            </p>
                        </div>
                    </div>
                    <p class="text-xs text-foreground/50">{{ $t('insights.video.attribution.disclaimer') }}</p>
                </CardContent>
            </Card>

            <a
                href="https://www.tiktok.com/tiktokstudio/analytics"
                target="_blank"
                rel="noopener noreferrer"
                class="text-sm font-medium text-foreground/70 underline hover:text-foreground"
            >
                {{ $t('insights.video.tiktok_studio_link') }}
            </a>
        </div>
    </AppLayout>
</template>

<style scoped>
:deep(.unovis-xy-container) {
    --vis-axis-tick-label-color: color-mix(in oklab, var(--color-foreground) 45%, transparent);
    --vis-axis-tick-label-font-size: 11px;
    --vis-crosshair-line-stroke-color: color-mix(in oklab, var(--color-foreground) 20%, transparent);
}
</style>
