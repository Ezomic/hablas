<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Spinner } from '@/components/ui/spinner';
import { skip, store } from '@/routes/placement';

interface PlacementTestItem {
    id: number;
    skill: string;
    prompt: string;
    options: string[];
    sort_order: number;
}

const props = defineProps<{
    items: PlacementTestItem[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Placement test', href: '/placement' }],
    },
});

const skillLabels: Record<string, string> = {
    reading: 'Reading',
    listening: 'Listening',
    speaking: 'Speaking',
    writing: 'Writing',
};

const form = useForm<{ responses: Record<number, string> }>({
    responses: {},
});

function itemsBySkill(skill: string) {
    return props.items.filter((item) => item.skill === skill);
}

function submit() {
    form.post(store().url);
}

function skipTest() {
    form.post(skip().url);
}
</script>

<template>
    <Head title="Spanish placement test" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div>
            <h1 class="text-2xl font-semibold">Spanish placement test</h1>
            <p class="mt-1 text-muted-foreground">
                Answer as many as you can. This sets your starting CEFR level
                for reading, listening, speaking, and writing separately.
            </p>
        </div>

        <form class="flex flex-col gap-10" @submit.prevent="submit">
            <div
                v-for="skill in Object.keys(skillLabels)"
                :key="skill"
                class="flex flex-col gap-6"
            >
                <h2 class="text-lg font-medium">{{ skillLabels[skill] }}</h2>

                <div
                    v-for="item in itemsBySkill(skill)"
                    :key="item.id"
                    class="flex flex-col gap-3"
                >
                    <p class="font-medium">{{ item.prompt }}</p>
                    <RadioGroup v-model="form.responses[item.id]">
                        <div
                            v-for="option in item.options"
                            :key="option"
                            class="flex items-center gap-2"
                        >
                            <RadioGroupItem
                                :id="`item-${item.id}-${option}`"
                                :value="option"
                            />
                            <Label :for="`item-${item.id}-${option}`">{{
                                option
                            }}</Label>
                        </div>
                    </RadioGroup>
                </div>
            </div>

            <InputError :message="form.errors.responses" />

            <Button type="submit" :disabled="form.processing">
                <Spinner v-if="form.processing" />
                See my results
            </Button>
        </form>

        <div class="border-t pt-6 text-center text-sm text-muted-foreground">
            Not ready for a test?
            <button
                type="button"
                class="underline underline-offset-4"
                :disabled="form.processing"
                @click="skipTest"
            >
                Skip and start at A1
            </button>
        </div>
    </div>
</template>
