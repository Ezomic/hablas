<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Lock } from '@lucide/vue';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import StarRow from '@/components/StarRow.vue';
import type { BadgeVariants } from '@/components/ui/badge';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Progress } from '@/components/ui/progress';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { skillKeys, skillLabel } from '@/lib/skillLabels';
import type { CompletionFilter } from '@/lib/unitLibrary';
import { filterUnits, groupByLevel, paceToDeadline } from '@/lib/unitLibrary';
import { index as reviewIndex } from '@/routes/review';
import { index, show } from '@/routes/units';
import type { LibraryUnit, UnitAvailability } from '@/types/unit';

const props = defineProps<{
    language: { name: string } | null;
    units: LibraryUnit[];
    a1Deadline: string | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.units'), href: index() }]);

const pace = computed(() =>
    props.a1Deadline === null || props.units.every((u) => u.cefrLevel !== 'A1')
        ? null
        : paceToDeadline(props.units, 'A1', props.a1Deadline, new Date()),
);

const skillOptions = computed<Record<string, string>>(() => ({
    all: t('units.filters.allSkills'),
    ...Object.fromEntries(skillKeys.map((key) => [key, skillLabel(key)])),
}));

const completionOptions = computed<Record<CompletionFilter, string>>(() => ({
    all: t('units.filters.allUnits'),
    completed: t('units.availability.completed'),
    not_completed: t('units.filters.notCompleted'),
}));

const availabilityVariants: Record<UnitAvailability, BadgeVariants['variant']> =
    {
        completed: 'secondary',
        in_progress: 'default',
        available: 'default',
        held_back: 'outline',
        locked: 'outline',
    };

const skill = ref('all');
const completion = ref<CompletionFilter>('all');

const groups = computed(() =>
    groupByLevel(
        filterUnits(props.units, {
            skill: skill.value,
            completion: completion.value,
        }),
    ),
);

const hasHeldBackUnits = computed(() =>
    props.units.some((unit) => unit.availability === 'held_back'),
);

function opens(unit: LibraryUnit): boolean {
    return ['completed', 'in_progress', 'available'].includes(
        unit.availability,
    );
}

function note(unit: LibraryUnit): string | null {
    if (unit.availability === 'in_progress' && unit.lessonCount > 0) {
        return t('units.notes.lesson', {
            current: Math.min(unit.lessonsCompleted + 1, unit.lessonCount),
            count: unit.lessonCount,
        });
    }

    if (unit.availability === 'locked') {
        return t('units.notes.locked', { level: unit.cefrLevel });
    }

    if (unit.availability === 'held_back') {
        return t('units.notes.heldBack');
    }

    return null;
}
</script>

<template>
    <Head :title="t('nav.units')" />

    <div class="mx-auto flex w-full max-w-3xl flex-col gap-6 p-4">
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-semibold">{{ t('nav.units') }}</h1>
            <p class="text-muted-foreground">
                <template v-if="props.language">
                    {{ t('units.intro', { language: props.language.name }) }}
                </template>
                <template v-else>{{ t('common.noActiveLanguage') }}</template>
            </p>
        </div>

        <Card v-if="pace" data-testid="pace">
            <CardHeader>
                <CardTitle>{{ t('units.pace.title') }}</CardTitle>
                <CardDescription>
                    <template v-if="pace.status === 'done'">
                        {{ t('units.pace.done') }}
                    </template>
                    <template v-else-if="pace.status === 'late'">
                        {{ t('units.pace.late', { left: pace.left }) }}
                    </template>
                    <template v-else>
                        {{
                            t('units.pace.summary', {
                                left: pace.left,
                                days: pace.days,
                                perDay: pace.perDay,
                            })
                        }}
                        {{ t(`units.pace.${pace.status}`) }}
                    </template>
                </CardDescription>
            </CardHeader>
        </Card>

        <Card v-if="hasHeldBackUnits">
            <CardHeader>
                <CardTitle>{{ t('units.reinforce.title') }}</CardTitle>
                <CardDescription>
                    {{ t('units.reinforce.body') }}
                </CardDescription>
                <Button as-child class="mt-2 w-fit">
                    <Link :href="reviewIndex()">{{
                        t('common.reviewNow')
                    }}</Link>
                </Button>
            </CardHeader>
        </Card>

        <div
            v-if="props.units.length"
            class="grid gap-4 sm:grid-cols-2 sm:gap-6"
        >
            <div class="grid gap-2">
                <Label for="unit-skill-filter">{{
                    t('units.filters.skill')
                }}</Label>
                <Select v-model="skill">
                    <SelectTrigger id="unit-skill-filter" class="w-full">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="(label, value) in skillOptions"
                            :key="value"
                            :value="value"
                        >
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div class="grid gap-2">
                <Label for="unit-completion-filter">{{
                    t('units.filters.progress')
                }}</Label>
                <Select v-model="completion">
                    <SelectTrigger id="unit-completion-filter" class="w-full">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="(label, value) in completionOptions"
                            :key="value"
                            :value="value"
                        >
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
        </div>

        <p
            v-if="props.language && !props.units.length"
            class="text-muted-foreground"
        >
            {{ t('units.none', { language: props.language.name }) }}
        </p>
        <p
            v-else-if="props.units.length && !groups.length"
            class="text-muted-foreground"
        >
            {{ t('units.noMatch') }}
        </p>

        <section
            v-for="group in groups"
            :key="group.level"
            class="flex flex-col gap-3"
            :aria-labelledby="`units-level-${group.level}`"
        >
            <h2 :id="`units-level-${group.level}`" class="text-lg font-medium">
                {{ group.level }}
            </h2>

            <ul class="flex flex-col gap-3">
                <li v-for="unit in group.units" :key="unit.id">
                    <component
                        :is="opens(unit) ? Link : 'div'"
                        v-bind="opens(unit) ? { href: show(unit.id) } : {}"
                        class="group block rounded-xl outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50"
                    >
                        <Card
                            class="gap-3 py-4 transition-colors"
                            :class="
                                opens(unit)
                                    ? 'group-hover:bg-accent/50'
                                    : 'border-dashed bg-muted/40 shadow-none'
                            "
                        >
                            <CardHeader class="gap-2 px-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <Badge variant="outline">{{
                                        skillLabel(unit.primarySkill)
                                    }}</Badge>
                                    <Badge
                                        :variant="
                                            availabilityVariants[
                                                unit.availability
                                            ]
                                        "
                                    >
                                        <Lock
                                            v-if="
                                                unit.availability === 'locked'
                                            "
                                            aria-hidden="true"
                                        />
                                        {{
                                            t(
                                                `units.availability.${unit.availability}`,
                                            )
                                        }}
                                    </Badge>
                                    <Badge
                                        v-if="unit.struggles > 0"
                                        variant="outline"
                                        class="border-amber-500 text-amber-700 dark:text-amber-300"
                                        data-testid="struggles-badge"
                                    >
                                        {{
                                            t(
                                                'units.struggles.badge',
                                                { count: unit.struggles },
                                                unit.struggles,
                                            )
                                        }}
                                    </Badge>
                                </div>
                                <CardTitle
                                    class="text-base"
                                    :class="{
                                        'text-muted-foreground': !opens(unit),
                                    }"
                                    >{{ unit.title }}</CardTitle
                                >
                                <CardDescription>{{
                                    unit.taskDescription
                                }}</CardDescription>
                                <div
                                    v-if="opens(unit) && unit.percent > 0"
                                    class="flex items-center gap-2"
                                    data-testid="unit-progress"
                                >
                                    <Progress
                                        :model-value="unit.percent"
                                        class="h-2 flex-1"
                                        :aria-label="
                                            t('progress.unitPercent', {
                                                percent: unit.percent,
                                            })
                                        "
                                    />
                                    <span class="text-xs text-muted-foreground"
                                        >{{ unit.percent }}%</span
                                    >
                                    <StarRow
                                        :stars="unit.stars"
                                        size="size-4"
                                    />
                                </div>
                                <p
                                    v-if="note(unit)"
                                    class="text-sm text-muted-foreground"
                                >
                                    {{ note(unit) }}
                                </p>
                            </CardHeader>
                        </Card>
                    </component>
                </li>
            </ul>
        </section>
    </div>
</template>
