<script setup lang="ts">
import { CloudOff, TriangleAlert } from '@lucide/vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { pluralize } from '@/lib/pluralize';

const { isOnline, pendingCount, rejectedCount } = useOfflineSync();

function attempts(count: number): string {
    return `${count} ${pluralize('attempt', count)}`;
}
</script>

<template>
    <div
        v-if="pendingCount > 0 || rejectedCount > 0"
        class="flex flex-col gap-2 px-4 pt-4"
    >
        <Alert v-if="pendingCount > 0" role="status">
            <CloudOff />
            <AlertDescription v-if="isOnline">
                {{ attempts(pendingCount) }} saved on this device
                {{ pendingCount === 1 ? "hasn't" : "haven't" }} synced yet and
                will be retried automatically.
            </AlertDescription>
            <AlertDescription v-else>
                You're offline. {{ attempts(pendingCount) }} saved on this
                device will sync once you're back online.
            </AlertDescription>
        </Alert>

        <Alert v-if="rejectedCount > 0" variant="destructive">
            <TriangleAlert />
            <AlertDescription
                class="flex flex-wrap items-center justify-between gap-2"
            >
                {{ attempts(rejectedCount) }} saved offline couldn't be synced
                and {{ rejectedCount === 1 ? 'was' : 'were' }} discarded.
                <Button variant="outline" size="sm" @click="rejectedCount = 0">
                    Dismiss
                </Button>
            </AlertDescription>
        </Alert>
    </div>
</template>
