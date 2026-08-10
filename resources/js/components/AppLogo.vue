<script setup lang="ts">
withDefaults(
    defineProps<{
        class?: string;
        iconOnly?: boolean;
    }>(),
    { iconOnly: false },
);
</script>

<template>
    <span :class="['dt-logo', $props.class]">
        <svg class="dt-logo__mark" viewBox="0 0 64 64" role="img" aria-label="Dalil Tech Publish">
            <path d="M32 5 L39 17 L25 17 Z" fill="#3ECF8E" />
            <rect x="18" y="20" width="28" height="5" rx="2.5" fill="#3ECF8E" />
            <rect x="21" y="28" width="22" height="5" rx="2.5" fill="#3ECF8E" opacity="0.82" />
            <rect x="24" y="36" width="16" height="5" rx="2.5" fill="#3ECF8E" opacity="0.66" />
            <rect x="27" y="44" width="10" height="5" rx="2.5" fill="#3ECF8E" opacity="0.52" />
            <circle cx="32" cy="55" r="2.6" fill="#3ECF8E" />
        </svg>
        <span v-if="!iconOnly" class="dt-logo__word">Dalil Tech <span class="dt-logo__accent">Publish</span></span>
    </span>
</template>

<style scoped>
.dt-logo {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    line-height: 1;
}
.dt-logo__mark {
    height: 1.75rem;
    width: 1.75rem;
    flex-shrink: 0;
}
.dt-logo__word {
    font-family: 'Space Grotesk', var(--font-display, system-ui), sans-serif;
    font-weight: 700;
    font-size: 1.125rem;
    letter-spacing: -0.02em;
    color: var(--foreground, #e6eaf0);
    white-space: nowrap;
}
.dt-logo__accent {
    color: var(--primary, #3ecf8e);
}
</style>
