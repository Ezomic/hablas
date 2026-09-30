<script setup lang="ts">
import { computed, ref } from 'vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { pluralize } from '@/lib/pluralize';
import type { ReviewForecast } from '@/types/forecast';

const props = defineProps<{
    forecast: ReviewForecast;
    weakSpots: number;
}>();

// The server buckets by UTC day, so each date is labelled in UTC as well;
// in the viewer's own zone, anyone west of UTC would see every label a day early.
const dayName = new Intl.DateTimeFormat('en-GB', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    timeZone: 'UTC',
});
const weekdayInitial = new Intl.DateTimeFormat('en-GB', {
    weekday: 'narrow',
    timeZone: 'UTC',
});

const total = computed(() =>
    props.forecast.days.reduce((sum, day) => sum + day.cards, 0),
);

const busiest = computed(() =>
    Math.max(0, ...props.forecast.days.map((day) => day.cards)),
);

const peakIndex = computed(() =>
    props.forecast.days.findIndex((day) => day.cards === busiest.value),
);

const days = computed(() =>
    props.forecast.days.map((day, index) => {
        const date = new Date(`${day.date}T00:00:00Z`);
        const name = index === 0 ? 'Today' : dayName.format(date);

        return {
            ...day,
            initial: weekdayInitial.format(date),
            label: `${name}: ${day.cards} ${pluralize('card', day.cards)}`,
            height: busiest.value === 0 ? 0 : (day.cards / busiest.value) * 100,
        };
    }),
);

const title = computed(() => {
    const span = `in the next ${props.forecast.days.length} days`;

    return total.value === 0
        ? `No cards due ${span}`
        : `${total.value} ${pluralize('card', total.value)} due ${span}`;
});

const unscheduled = computed(() => {
    const parts: string[] = [];

    if (props.weakSpots > 0) {
        parts.push(
            `${props.weakSpots} ${pluralize('weak spot', props.weakSpots)} to clear`,
        );
    }

    if (props.forecast.newWaiting > 0) {
        parts.push(
            `${props.forecast.newWaiting} new ${pluralize('card', props.forecast.newWaiting)} waiting`,
        );
    }

    if (parts.length === 0) {
        return null;
    }

    const list = parts.join(' and ');

    return total.value > 0 ? `Plus ${list}.` : `${list}.`;
});

const visible = computed(() => total.value > 0 || unscheduled.value !== null);

const active = ref<number | null>(null);

const readout = computed(() => days.value[active.value ?? 0]?.label ?? '');

function stepThroughDays(event: KeyboardEvent) {
    const last = days.value.length - 1;
    const current = active.value ?? 0;
    const targets: Record<string, number> = {
        ArrowLeft: current - 1,
        ArrowRight: current + 1,
        Home: 0,
        End: last,
    };

    if (!(event.key in targets)) {
        return;
    }

    event.preventDefault();
    active.value = Math.min(Math.max(targets[event.key], 0), last);
}

// A finger lifting off the screen also fires pointerleave, which would clear
// a day the moment it was tapped.
function leaveChart(event: PointerEvent) {
    if (event.pointerType !== 'touch') {
        active.value = null;
    }
}
</script>

<template>
    <Card v-if="visible">
        <CardHeader>
            <CardDescription>Coming up</CardDescription>
            <CardTitle class="text-2xl">{{ title }}</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-3">
            <div v-if="total > 0" class="flex flex-col gap-2">
                <ol
                    aria-label="Cards due each day"
                    tabindex="0"
                    class="flex h-28 items-end gap-0.5 rounded-sm border-b pt-5 outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50"
                    @keydown="stepThroughDays"
                    @pointerleave="leaveChart"
                    @blur="active = null"
                >
                    <li
                        v-for="(day, index) in days"
                        :key="day.date"
                        class="flex h-full flex-1 items-end justify-center"
                        @pointerenter="active = index"
                    >
                        <span class="sr-only">{{ day.label }}</span>
                        <div
                            data-test="forecast-bar"
                            aria-hidden="true"
                            class="relative w-full max-w-6 rounded-t-sm bg-primary transition-opacity"
                            :class="{
                                'min-h-0.5': day.cards > 0,
                                'opacity-60': active === index,
                            }"
                            :style="{ height: `${day.height}%` }"
                        >
                            <span
                                v-if="index === peakIndex && day.cards > 0"
                                data-test="forecast-peak"
                                class="absolute bottom-full left-1/2 mb-1 -translate-x-1/2 text-xs font-medium tabular-nums"
                                >{{ day.cards }}</span
                            >
                        </div>
                    </li>
                </ol>
                <div
                    aria-hidden="true"
                    class="flex gap-0.5 text-xs text-muted-foreground"
                >
                    <span
                        v-for="(day, index) in days"
                        :key="day.date"
                        class="flex-1 text-center"
                        :class="{
                            'font-semibold text-foreground': index === 0,
                        }"
                        >{{ day.initial }}</span
                    >
                </div>
                <p
                    data-test="forecast-readout"
                    aria-hidden="true"
                    class="text-sm font-medium"
                >
                    {{ readout }}
                </p>
            </div>
            <p v-if="unscheduled" class="text-sm text-muted-foreground">
                {{ unscheduled }}
            </p>
        </CardContent>
    </Card>
</template>
