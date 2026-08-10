import { onMounted, ref } from 'vue';

export type Appearance = 'light' | 'dark' | 'system';

const LIGHT_BG = '#FBFAF7';
const DARK_BG = '#0E1116';

function systemPrefersDark(): boolean {
    return typeof window !== 'undefined' && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

function resolveDark(value: Appearance): boolean {
    return value === 'dark' || (value === 'system' && systemPrefersDark());
}

export function applyAppearance(value: Appearance): void {
    if (typeof document === 'undefined') return;
    const dark = resolveDark(value);
    const el = document.documentElement;
    el.classList.toggle('dark', dark);
    el.style.backgroundColor = dark ? DARK_BG : LIGHT_BG;
    const meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.setAttribute('content', dark ? DARK_BG : LIGHT_BG);
}

const appearance = ref<Appearance>('system');

function stored(): Appearance {
    if (typeof localStorage === 'undefined') return 'system';
    return (localStorage.getItem('appearance') as Appearance) || 'system';
}

// Called once at app boot (app.ts) so the toggle and system changes stay live.
export function initAppearance(): void {
    appearance.value = stored();
    applyAppearance(appearance.value);
    if (typeof window !== 'undefined') {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (appearance.value === 'system') applyAppearance('system');
        });
    }
}

export function useAppearance() {
    onMounted(() => {
        appearance.value = stored();
    });

    function setAppearance(value: Appearance): void {
        appearance.value = value;
        if (typeof localStorage !== 'undefined') localStorage.setItem('appearance', value);
        applyAppearance(value);
    }

    return { appearance, setAppearance };
}
