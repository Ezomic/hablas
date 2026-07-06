import { router } from '@inertiajs/vue3';
import { PartyPopper } from '@lucide/vue';
import { toast } from 'vue-sonner';
import type { FlashToast } from '@/types/ui';

export function initializeFlashToast(): void {
    router.on('flash', (event) => {
        const flash = (event as CustomEvent).detail?.flash;
        const data = flash?.toast as FlashToast | undefined;

        if (!data) {
            return;
        }

        if (data.type === 'milestone') {
            toast.success(data.message, {
                icon: PartyPopper,
                duration: 6000,
                class: 'milestone-toast',
            });

            return;
        }

        toast[data.type](data.message);
    });
}
