import { setLayoutProps } from '@inertiajs/vue3';
import { watchEffect } from 'vue';
import type { BreadcrumbItem } from '@/types';

export function useBreadcrumbs(items: () => BreadcrumbItem[]): void {
    watchEffect(() => setLayoutProps({ breadcrumbs: items() }));
}
