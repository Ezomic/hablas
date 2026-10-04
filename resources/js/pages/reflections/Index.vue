<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { skillKeys, skillLabel } from '@/lib/skillLabels';
import { index, store } from '@/routes/reflections';

interface Statement {
    id: number;
    skill: string;
    statement_text: string;
}

const props = defineProps<{
    statements: Statement[];
    submittedThisWeek: boolean;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.weeklyReflection'), href: index() }]);

const form = useForm<{ statement_ids: number[]; can_do_ids: number[] }>({
    statement_ids: props.statements.map((statement) => statement.id),
    can_do_ids: [],
});

function toggle(statementId: number, checked: boolean) {
    if (checked) {
        form.can_do_ids.push(statementId);
    } else {
        form.can_do_ids = form.can_do_ids.filter((id) => id !== statementId);
    }
}

function submit() {
    form.post(store().url);
}
</script>

<template>
    <Head :title="t('nav.weeklyReflection')" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div>
            <h1 class="text-2xl font-semibold">
                {{ t('nav.weeklyReflection') }}
            </h1>
            <p class="mt-1 text-muted-foreground">
                {{ t('reflections.intro') }}
            </p>
        </div>

        <p v-if="props.submittedThisWeek" class="text-muted-foreground">
            {{ t('reflections.done') }}
        </p>

        <form v-else class="flex flex-col gap-8" @submit.prevent="submit">
            <div
                v-for="skill in skillKeys"
                :key="skill"
                class="flex flex-col gap-3"
            >
                <h2 class="text-lg font-medium">{{ skillLabel(skill) }}</h2>

                <Card
                    v-for="statement in props.statements.filter(
                        (s) => s.skill === skill,
                    )"
                    :key="statement.id"
                >
                    <CardContent class="flex items-center gap-3 py-4">
                        <Checkbox
                            :id="`statement-${statement.id}`"
                            :model-value="
                                form.can_do_ids.includes(statement.id)
                            "
                            @update:model-value="
                                (checked: boolean | 'indeterminate') =>
                                    toggle(statement.id, checked === true)
                            "
                        />
                        <Label :for="`statement-${statement.id}`">{{
                            statement.statement_text
                        }}</Label>
                    </CardContent>
                </Card>
            </div>

            <Button type="submit" :disabled="form.processing">
                <AppSpinner v-if="form.processing" />
                {{ t('reflections.submit') }}
            </Button>
        </form>
    </div>
</template>
