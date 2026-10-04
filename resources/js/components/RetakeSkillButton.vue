<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { retakeDate } from '@/lib/retakeDate';
import { skillLabel } from '@/lib/skillLabels';
import { store } from '@/routes/placement/skills';

const props = defineProps<{
    skill: string;
    availableOn: string | null;
}>();

const { t } = useI18n();

// No fields: the skill is in the URL, and a refusal comes back under "skill".
const form = useForm<Record<string, string>>({});

const skillName = computed(() => skillLabel(props.skill).toLowerCase());

function retake() {
    form.post(store(props.skill).url, { preserveScroll: true });
}
</script>

<template>
    <div class="flex flex-col items-start gap-1">
        <Button
            variant="outline"
            size="sm"
            class="h-auto text-left whitespace-normal"
            :disabled="props.availableOn !== null || form.processing"
            @click="retake"
        >
            {{ t('dashboard.retake', { skill: skillName }) }}
        </Button>
        <p v-if="props.availableOn" class="text-xs text-muted-foreground">
            {{
                t('dashboard.retakeAvailable', {
                    date: retakeDate(props.availableOn),
                })
            }}
        </p>
        <InputError :message="form.errors.skill" />
    </div>
</template>
