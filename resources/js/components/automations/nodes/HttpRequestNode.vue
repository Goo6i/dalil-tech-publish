<script setup lang="ts">
import { IconWorld } from '@tabler/icons-vue';
import { Handle, Position } from '@vue-flow/core';
import { computed } from 'vue';

import type { HttpMethodValue } from '@/types/automation/http-method';

const props = defineProps<{
    data: {
        url?: string;
        method: HttpMethodValue;
    };
    selected?: boolean;
}>();

const summary = computed(() => {
    const method = props.data.method.toUpperCase();
    const url = props.data.url;
    if (!url) return method;
    let host = url;
    try {
        host = new URL(url).hostname;
    } catch {
        // not a valid URL, fall back to raw string
    }
    return `${method} · ${host}`;
});
</script>

<template>
    <div
        class="automation-node automation-node--accent-slate"
        :class="{ 'is-selected': selected }"
    >
        <div class="automation-node__header">
            <div class="automation-node__icon-tile automation-node__icon-tile--slate">
                <IconWorld :size="16" />
            </div>
            <span class="automation-node__title">{{ $t('automations.nodes.http_request') }}</span>
        </div>
        <div class="automation-node__summary">
            {{ summary }}
        </div>
        <Handle type="target" :position="Position.Left" class="!bg-muted-foreground" />
        <Handle
            id="default"
            type="source"
            :position="Position.Right"
            class="!bg-primary/100"
            :style="{ top: '35%' }"
        />
        <span class="pointer-events-none absolute left-full top-[35%] z-10 ml-3 -translate-y-1/2 whitespace-nowrap rounded bg-background px-1.5 text-[10px] font-bold uppercase tracking-wider text-success">{{ $t('automations.nodes.handles.items') }}</span>
        <Handle
            id="no_items"
            type="source"
            :position="Position.Right"
            class="!bg-destructive/100"
            :style="{ top: '75%' }"
        />
        <span class="pointer-events-none absolute left-full top-[75%] z-10 ml-3 -translate-y-1/2 whitespace-nowrap rounded bg-background px-1.5 text-[10px] font-bold uppercase tracking-wider text-destructive">{{ $t('automations.nodes.handles.no_items') }}</span>
    </div>
</template>
