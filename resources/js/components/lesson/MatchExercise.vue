<script setup lang="ts">
import { computed, ref } from 'vue';
import { seededShuffle } from '@/lib/lessonQueue';
import { cn } from '@/lib/utils';

interface Pair {
    target: string;
    left: string;
    right: string;
}

const props = defineProps<{
    pairs: Pair[];
    instruction: string;
    seed: number;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    change: [state: { complete: boolean; wrong: string[] }];
}>();

const left = ref<string | null>(null);
const right = ref<string | null>(null);
const matched = ref<string[]>([]);
const wrong = ref<string[]>([]);
const shaking = ref<string[]>([]);

const lefts = computed(() => props.pairs);
const rights = computed(() => seededShuffle(props.pairs, props.seed));

function report() {
    emit('change', {
        complete: matched.value.length === props.pairs.length,
        wrong: wrong.value,
    });
}

function choose(side: 'left' | 'right', target: string) {
    if (props.disabled || matched.value.includes(target)) {
        return;
    }

    if (side === 'left') {
        left.value = target;
    } else {
        right.value = target;
    }

    if (left.value === null || right.value === null) {
        return;
    }

    if (left.value === right.value) {
        matched.value = [...matched.value, left.value];
    } else {
        wrong.value = [...new Set([...wrong.value, left.value, right.value])];
        shaking.value = [left.value, right.value];
        setTimeout(() => (shaking.value = []), 400);
    }

    left.value = null;
    right.value = null;
    report();
}

function selected(side: 'left' | 'right', target: string): boolean {
    return (side === 'left' ? left.value : right.value) === target;
}

function tile(side: 'left' | 'right', target: string): string {
    return cn(
        'min-h-12 w-full rounded-lg border bg-background px-3 py-2 text-sm transition-all outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50',
        selected(side, target) && 'border-primary bg-primary/10',
        matched.value.includes(target) && 'opacity-0',
        shaking.value.includes(target) &&
            !matched.value.includes(target) &&
            'border-red-600 motion-safe:animate-pulse',
    );
}
</script>

<template>
    <section class="flex flex-col gap-4">
        <p class="text-sm text-muted-foreground">{{ props.instruction }}</p>
        <div class="grid grid-cols-2 gap-3" data-testid="match">
            <div class="flex flex-col gap-2">
                <button
                    v-for="pair in lefts"
                    :key="`left-${pair.target}`"
                    type="button"
                    :class="tile('left', pair.target)"
                    :disabled="matched.includes(pair.target)"
                    :aria-hidden="
                        matched.includes(pair.target) ? 'true' : undefined
                    "
                    :tabindex="matched.includes(pair.target) ? -1 : undefined"
                    :aria-pressed="selected('left', pair.target)"
                    :data-testid="`left-${pair.target}`"
                    @click="choose('left', pair.target)"
                >
                    {{ pair.left }}
                </button>
            </div>
            <div class="flex flex-col gap-2">
                <button
                    v-for="pair in rights"
                    :key="`right-${pair.target}`"
                    type="button"
                    :class="tile('right', pair.target)"
                    :disabled="matched.includes(pair.target)"
                    :aria-hidden="
                        matched.includes(pair.target) ? 'true' : undefined
                    "
                    :tabindex="matched.includes(pair.target) ? -1 : undefined"
                    :aria-pressed="selected('right', pair.target)"
                    :data-testid="`right-${pair.target}`"
                    @click="choose('right', pair.target)"
                >
                    {{ pair.right }}
                </button>
            </div>
        </div>
    </section>
</template>
