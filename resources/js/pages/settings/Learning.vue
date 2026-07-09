<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useWebPush } from '@/composables/useWebPush';
import { edit, update } from '@/routes/learning';
import { update as updateInterests } from '@/routes/learning/interests';

interface Settings {
    notificationFrequency: 'daily' | 'weekly' | 'never';
    newItemCapOverride: number | null;
    contextEmphasis: 'travel' | 'everyday_social' | 'professional' | null;
}

type InterestTag =
    'football' | 'cooking' | 'tech' | 'music' | 'travel' | 'food';

const props = defineProps<{
    settings: Settings;
    interestTags: InterestTag[];
    availableInterestTags: InterestTag[];
    pushEnabled: boolean;
    vapidPublicKey: string | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Learning settings', href: edit() }],
    },
});

const notificationFrequencyLabels: Record<
    Settings['notificationFrequency'],
    string
> = {
    daily: 'Daily',
    weekly: 'Weekly',
    never: 'Never',
};

const contextEmphasisLabels: Record<
    'none' | NonNullable<Settings['contextEmphasis']>,
    string
> = {
    none: 'No preference',
    travel: 'Travel',
    everyday_social: 'Everyday & social',
    professional: 'Professional',
};

const interestTagLabels: Record<InterestTag, string> = {
    football: 'Football',
    cooking: 'Cooking',
    tech: 'Tech',
    music: 'Music',
    travel: 'Travel',
    food: 'Food',
};

const form = useForm({
    notification_frequency: props.settings.notificationFrequency,
    new_item_cap_override:
        props.settings.newItemCapOverride === null
            ? ''
            : String(props.settings.newItemCapOverride),
    context_emphasis: props.settings.contextEmphasis ?? 'none',
});

function submit() {
    form.transform((data) => {
        const parsedCapOverride = Number(data.new_item_cap_override);

        return {
            notification_frequency: data.notification_frequency,
            new_item_cap_override:
                data.new_item_cap_override === '' ||
                Number.isNaN(parsedCapOverride)
                    ? null
                    : parsedCapOverride,
            context_emphasis:
                data.context_emphasis === 'none' ? null : data.context_emphasis,
        };
    }).patch(update().url, { preserveScroll: true });
}

const interestsForm = useForm({
    interest_tags: [...props.interestTags],
});

const interestTagsError = computed(() => {
    const key = Object.keys(interestsForm.errors).find((k) =>
        k.startsWith('interest_tags'),
    );

    return key
        ? interestsForm.errors[key as keyof typeof interestsForm.errors]
        : undefined;
});

function toggleInterest(tag: InterestTag, checked: boolean) {
    if (checked) {
        interestsForm.interest_tags.push(tag);
    } else {
        interestsForm.interest_tags = interestsForm.interest_tags.filter(
            (t) => t !== tag,
        );
    }
}

function submitInterests() {
    interestsForm.patch(updateInterests().url, { preserveScroll: true });
}

const pushEnabled = ref(props.pushEnabled);
const webPush = props.vapidPublicKey ? useWebPush(props.vapidPublicKey) : null;

async function togglePush(checked: boolean) {
    if (!webPush) {
        return;
    }

    const succeeded = checked
        ? await webPush.subscribe()
        : await webPush.unsubscribe();

    if (succeeded) {
        pushEnabled.value = checked;
    }
}
</script>

<template>
    <Head title="Learning settings" />

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Learning"
            description="Control reminders, new-item pacing, and content focus"
        />

        <form class="flex flex-col gap-6" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label for="notification_frequency">Reminder frequency</Label>
                <Select v-model="form.notification_frequency">
                    <SelectTrigger
                        id="notification_frequency"
                        class="w-full max-w-xs"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="(
                                label, value
                            ) in notificationFrequencyLabels"
                            :key="value"
                            :value="value"
                        >
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.notification_frequency" />
            </div>

            <div class="grid gap-2">
                <Label for="new_item_cap_override"
                    >Daily new-item cap override</Label
                >
                <Input
                    id="new_item_cap_override"
                    v-model="form.new_item_cap_override"
                    type="number"
                    min="0"
                    max="100"
                    class="max-w-xs"
                    placeholder="Adaptive (default)"
                />
                <p class="text-sm text-muted-foreground">
                    Leave blank to let the app adjust your daily new-item limit
                    automatically based on your review backlog.
                </p>
                <InputError :message="form.errors.new_item_cap_override" />
            </div>

            <div class="grid gap-2">
                <Label for="context_emphasis">Content focus</Label>
                <Select v-model="form.context_emphasis">
                    <SelectTrigger
                        id="context_emphasis"
                        class="w-full max-w-xs"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="(label, value) in contextEmphasisLabels"
                            :key="value"
                            :value="value"
                        >
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.context_emphasis" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing" type="submit">Save</Button>
            </div>
        </form>

        <div v-if="webPush" class="flex flex-col gap-2">
            <Label>Push notifications</Label>
            <p class="text-sm text-muted-foreground">
                Get a browser notification for your daily digest instead of (or
                alongside) email.
            </p>
            <div class="flex items-center gap-3 pt-2">
                <Checkbox
                    id="push-enabled"
                    :model-value="pushEnabled"
                    :disabled="webPush.isSubscribing.value"
                    @update:model-value="
                        (checked: boolean | 'indeterminate') =>
                            togglePush(checked === true)
                    "
                />
                <Label for="push-enabled">Enable push notifications</Label>
            </div>
            <InputError :message="webPush.error.value ?? undefined" />
        </div>

        <form class="flex flex-col gap-6" @submit.prevent="submitInterests">
            <div class="grid gap-2">
                <Label>Interests</Label>
                <p class="text-sm text-muted-foreground">
                    Units that match your interests are prioritized when picking
                    what to learn next.
                </p>
                <div class="flex flex-col gap-3 pt-2">
                    <div
                        v-for="tag in props.availableInterestTags"
                        :key="tag"
                        class="flex items-center gap-3"
                    >
                        <Checkbox
                            :id="`interest-${tag}`"
                            :model-value="
                                interestsForm.interest_tags.includes(tag)
                            "
                            @update:model-value="
                                (checked: boolean | 'indeterminate') =>
                                    toggleInterest(tag, checked === true)
                            "
                        />
                        <Label :for="`interest-${tag}`">{{
                            interestTagLabels[tag]
                        }}</Label>
                    </div>
                </div>
                <InputError :message="interestTagsError" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="interestsForm.processing" type="submit"
                    >Save interests</Button
                >
            </div>
        </form>
    </div>
</template>
