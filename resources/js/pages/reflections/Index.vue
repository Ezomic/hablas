<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/reflections';

interface Statement {
    id: number;
    skill: string;
    statement_text: string;
}

const props = defineProps<{
    statements: Statement[];
    submittedThisWeek: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Weekly reflection', href: '/reflections' }],
    },
});

const skillLabels: Record<string, string> = {
    reading: 'Reading',
    listening: 'Listening',
    speaking: 'Speaking',
    writing: 'Writing',
};

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
    <Head title="Weekly reflection" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div>
            <h1 class="text-2xl font-semibold">Weekly reflection</h1>
            <p class="mt-1 text-muted-foreground">
                Check off what you feel confident doing this week.
            </p>
        </div>

        <p v-if="props.submittedThisWeek" class="text-muted-foreground">
            You've already completed this week's reflection. Come back next week
            for a new one.
        </p>

        <form v-else class="flex flex-col gap-8" @submit.prevent="submit">
            <div
                v-for="skill in Object.keys(skillLabels)"
                :key="skill"
                class="flex flex-col gap-3"
            >
                <h2 class="text-lg font-medium">{{ skillLabels[skill] }}</h2>

                <Card
                    v-for="statement in props.statements.filter(
                        (s) => s.skill === skill,
                    )"
                    :key="statement.id"
                >
                    <CardContent class="flex items-center gap-3 py-4">
                        <Checkbox
                            :id="`statement-${statement.id}`"
                            :checked="form.can_do_ids.includes(statement.id)"
                            @update:checked="
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
                <Spinner v-if="form.processing" />
                Submit reflection
            </Button>
        </form>
    </div>
</template>
