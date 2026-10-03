<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import type { Remediation } from '@/types/lesson';

const props = defineProps<{ remediation: Remediation; busy?: boolean }>();

const emit = defineEmits<{ practice: []; retake: [] }>();

const { t } = useI18n();
</script>

<template>
    <div class="flex flex-col gap-3" data-testid="remediation">
        <p class="font-medium">
            {{ t('lesson.remediation.missing', props.remediation.missing) }}
        </p>
        <Button :disabled="props.busy" @click="emit('practice')">
            {{ t('lesson.remediation.practise') }}
        </Button>
        <Button
            v-if="props.remediation.retake === 'open'"
            variant="outline"
            :disabled="props.busy"
            @click="emit('retake')"
        >
            {{ t('lesson.remediation.retake') }}
        </Button>
        <p v-else class="text-sm text-muted-foreground">
            {{ t('lesson.remediation.retakeLater') }}
        </p>
    </div>
</template>
