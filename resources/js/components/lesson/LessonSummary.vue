<script setup lang="ts">
import { Check, X } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { NextLesson, RunSummary } from '@/types/lesson';

const props = defineProps<{
    summary: RunSummary;
    isCheck: boolean;
    next: NextLesson | null;
    starting?: boolean;
}>();

const emit = defineEmits<{ next: []; unit: [] }>();

const families: Record<string, string> = {
    choice: 'Multiple choice',
    writing: 'Writing',
};

const missing = computed(() =>
    props.summary.items.filter((item) => !item.mastered),
);

const mastered = computed(() =>
    props.summary.items.filter((item) => item.mastered),
);

const nextIsOpen = computed(
    () =>
        props.next !== null &&
        ['available', 'in_progress'].includes(props.next.state),
);
</script>

<template>
    <section class="flex flex-col gap-4" data-testid="summary">
        <h2 class="text-2xl font-semibold">
            {{
                props.summary.unitCompleted
                    ? 'Unit complete'
                    : props.isCheck
                      ? 'Check finished'
                      : 'Lesson complete'
            }}
        </h2>

        <p
            v-if="props.summary.unitCompleted"
            class="text-sm text-muted-foreground"
        >
            Every word and the grammar point are proven.
        </p>

        <Card v-if="Object.keys(props.summary.accuracy).length">
            <CardHeader>
                <CardTitle class="text-base">Right the first time</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-1 text-sm">
                <p
                    v-for="(value, family) in props.summary.accuracy"
                    :key="family"
                    class="flex justify-between"
                >
                    <span>{{ families[family] ?? family }}</span>
                    <span class="font-medium"
                        >{{ Math.round(value * 100) }}%</span
                    >
                </p>
            </CardContent>
        </Card>

        <Card v-if="props.summary.retried.length">
            <CardHeader>
                <CardTitle class="text-base">Needed a second go</CardTitle>
            </CardHeader>
            <CardContent class="text-sm">
                {{ props.summary.retried.join(', ') }}
            </CardContent>
        </Card>

        <template v-if="props.isCheck">
            <Card v-if="mastered.length">
                <CardHeader>
                    <CardTitle class="text-base"
                        >Proven ({{ mastered.length }})</CardTitle
                    >
                </CardHeader>
                <CardContent class="flex flex-col gap-1 text-sm">
                    <p
                        v-for="item in mastered"
                        :key="item.term"
                        class="flex items-center gap-2"
                    >
                        <Check class="size-4 text-green-600" />
                        {{ item.term }}
                    </p>
                </CardContent>
            </Card>

            <Card v-if="missing.length">
                <CardHeader>
                    <CardTitle class="text-base"
                        >Not proven yet ({{ missing.length }})</CardTitle
                    >
                </CardHeader>
                <CardContent class="flex flex-col gap-1 text-sm">
                    <p
                        v-for="item in missing"
                        :key="item.term"
                        class="flex items-center gap-2"
                    >
                        <X class="size-4 text-red-600" />
                        {{ item.term }}
                        <span
                            v-if="item.translation"
                            class="text-muted-foreground"
                            >({{ item.translation }})</span
                        >
                    </p>
                </CardContent>
            </Card>

            <Card v-if="props.summary.answers.length">
                <CardHeader>
                    <CardTitle class="text-base">Every answer</CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col gap-2 text-sm">
                    <p
                        v-for="(answer, index) in props.summary.answers"
                        :key="index"
                        class="flex flex-col"
                    >
                        <span class="text-muted-foreground">{{
                            answer.prompt
                        }}</span>
                        <span
                            :class="
                                answer.correct
                                    ? 'text-green-700 dark:text-green-300'
                                    : 'text-red-700 dark:text-red-300'
                            "
                            >{{ answer.given || 'No answer' }}</span
                        >
                        <span v-if="!answer.correct" class="font-medium"
                            >Correct: {{ answer.expected }}</span
                        >
                    </p>
                </CardContent>
            </Card>

            <p
                v-if="props.summary.cardsEnrolled"
                class="text-sm text-muted-foreground"
            >
                {{ props.summary.cardsEnrolled }}
                {{ props.summary.cardsEnrolled === 1 ? 'card' : 'cards' }}
                joined your review deck.
            </p>
        </template>

        <div class="flex flex-col gap-2">
            <Button
                v-if="nextIsOpen && props.next"
                :disabled="props.starting"
                @click="emit('next')"
            >
                {{
                    props.next.stage === 'check'
                        ? 'Take the unit check'
                        : `Next lesson: ${props.next.title}`
                }}
            </Button>
            <p
                v-else-if="props.next?.state === 'opens_tomorrow'"
                class="text-sm text-muted-foreground"
            >
                The unit check opens tomorrow, so what you learned has time to
                settle.
            </p>
            <Button variant="outline" @click="emit('unit')"
                >Back to the unit</Button
            >
        </div>
    </section>
</template>
