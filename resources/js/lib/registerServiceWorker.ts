import { useRegisterSW } from 'virtual:pwa-register/vue';

export function initializeServiceWorker(): void {
    useRegisterSW({ immediate: true });
}
