import { setLayoutProps } from '@inertiajs/vue3';
import { watchEffect } from 'vue';

type LayoutText = {
    title: string;
    description: string;
};

export function useLayoutText(text: () => LayoutText): void {
    watchEffect(() => setLayoutProps(text()));
}
