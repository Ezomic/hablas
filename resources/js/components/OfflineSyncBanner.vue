<script setup lang="ts">
import { CloudOff, TriangleAlert } from '@lucide/vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { useOfflineSync } from '@/composables/useOfflineSync';

const { isOnline, pendingCount, rejectedCount } = useOfflineSync();
</script>

<template>
    <div
        v-if="pendingCount > 0 || rejectedCount > 0"
        class="flex flex-col gap-2 px-4 pt-4"
    >
        <Alert v-if="pendingCount > 0" role="status">
            <CloudOff />
            <AlertDescription v-if="isOnline">
                {{
                    $t(
                        'offline.pendingOnline',
                        { count: pendingCount },
                        pendingCount,
                    )
                }}
            </AlertDescription>
            <AlertDescription v-else>
                {{
                    $t(
                        'offline.pendingOffline',
                        { count: pendingCount },
                        pendingCount,
                    )
                }}
            </AlertDescription>
        </Alert>

        <Alert v-if="rejectedCount > 0" variant="destructive">
            <TriangleAlert />
            <AlertDescription
                class="flex flex-wrap items-center justify-between gap-2"
            >
                {{
                    $t(
                        'offline.rejected',
                        { count: rejectedCount },
                        rejectedCount,
                    )
                }}
                <Button variant="outline" size="sm" @click="rejectedCount = 0">
                    {{ $t('common.dismiss') }}
                </Button>
            </AlertDescription>
        </Alert>
    </div>
</template>
