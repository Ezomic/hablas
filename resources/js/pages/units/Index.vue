<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Lock } from '@lucide/vue';
import { computed, ref } from 'vue';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { skillLabels } from '@/lib/skillLabels';
import type { CompletionFilter } from '@/lib/unitLibrary';
import { filterUnits, groupByLevel } from '@/lib/unitLibrary';
import { index as reviewIndex } from '@/routes/review';
import { index, show } from '@/routes/units';
import type { LibraryUnit, UnitAvailability } from '@/types/unit';

const props = defineProps<{
    language: { name: string } | null;
    units: LibraryUnit[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Units', href: index() }],
    },
});

const skillOptions: Record<string, string> = {
    all: 'All skills',
    ...skillLabels,
};

const completionOptions: Record<CompletionFilter, string> = {
    all: 'All units',
    completed: 'Completed',
    not_completed: 'Not completed',
};

const availabilityLabels: Record<UnitAvailability, string> = {
    completed: 'Completed',
    available: 'Available',
    held_back: 'After review',
    locked: 'Locked',
};

const availabilityVariants: Record<UnitAvailability, BadgeVariants['variant']> =
    {
        completed: 'secondary',
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
    return (
        unit.availability === 'completed' || unit.availability === 'available'
    );
}

function note(unit: LibraryUnit): string | null {
    if (unit.availability === 'locked') {
        return `Unlocks when your overall level reaches ${unit.cefrLevel}.`;
    }

    if (unit.availability === 'held_back') {
        return 'Opens once your recent reviews are back on track.';
    }

    return null;
}
</script>

<template>
    <Head title="Units" />

    <div class="mx-auto flex w-full max-w-3xl flex-col gap-6 p-4">
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-semibold">Units</h1>
            <p class="text-muted-foreground">
                <template v-if="props.language">
                    Every {{ props.language.name }} unit by level. Completed
                    units stay open as a reference.
                </template>
                <template v-else>No active language yet.</template>
            </p>
        </div>

        <Card v-if="hasHeldBackUnits">
            <CardHeader>
                <CardTitle>Reinforce what's tricky first</CardTitle>
                <CardDescription>
                    Your recent reviews have had a lot of misses, so new units
                    wait until you've revisited them.
                </CardDescription>
                <Button as-child class="mt-2 w-fit">
                    <Link :href="reviewIndex()">Review now</Link>
                </Button>
            </CardHeader>
        </Card>

        <div
            v-if="props.units.length"
            class="grid gap-4 sm:grid-cols-2 sm:gap-6"
        >
            <div class="grid gap-2">
                <Label for="unit-skill-filter">Skill</Label>
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
                <Label for="unit-completion-filter">Progress</Label>
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
            No {{ props.language.name }} units yet.
        </p>
        <p
            v-else-if="props.units.length && !groups.length"
            class="text-muted-foreground"
        >
            No units match these filters.
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
                                        skillLabels[unit.primarySkill] ??
                                        unit.primarySkill
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
                                            availabilityLabels[
                                                unit.availability
                                            ]
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
