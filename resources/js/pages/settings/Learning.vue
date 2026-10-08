<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
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
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { useWebPush } from '@/composables/useWebPush';
import { edit, update } from '@/routes/learning';
import { update as updateInterests } from '@/routes/learning/interests';

interface Settings {
    notificationFrequency: 'daily' | 'weekly' | 'never';
    newItemCapOverride: number | null;
    contextEmphasis: 'travel' | 'everyday_social' | 'professional' | null;
    reviewMode: 'recognition' | 'production' | 'mix';
    lessonName: string | null;
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

const { t } = useI18n();

useBreadcrumbs(() => [
    { title: t('settings.learning.headTitle'), href: edit() },
]);

const notificationFrequencies: Settings['notificationFrequency'][] = [
    'daily',
    'weekly',
    'never',
];

const contextEmphases: ('none' | NonNullable<Settings['contextEmphasis']>)[] = [
    'none',
    'travel',
    'everyday_social',
    'professional',
];

const reviewModes: Settings['reviewMode'][] = [
    'recognition',
    'production',
    'mix',
];

const form = useForm({
    notification_frequency: props.settings.notificationFrequency,
    new_item_cap_override:
        props.settings.newItemCapOverride === null
            ? ''
            : String(props.settings.newItemCapOverride),
    context_emphasis: props.settings.contextEmphasis ?? 'none',
    review_mode: props.settings.reviewMode,
    lesson_name: props.settings.lessonName ?? '',
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
            review_mode: data.review_mode,
            lesson_name:
                data.lesson_name.trim() === '' ? null : data.lesson_name.trim(),
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
    <Head :title="t('settings.learning.headTitle')" />

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            :title="t('settings.nav.learning')"
            :description="t('settings.learning.description')"
        />

        <form class="flex flex-col gap-6" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label for="notification_frequency">{{
                    t('settings.learning.reminderFrequency')
                }}</Label>
                <Select v-model="form.notification_frequency">
                    <SelectTrigger
                        id="notification_frequency"
                        class="w-full max-w-xs"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="value in notificationFrequencies"
                            :key="value"
                            :value="value"
                        >
                            {{ t(`settings.learning.frequency.${value}`) }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.notification_frequency" />
            </div>

            <div class="grid gap-2">
                <Label for="new_item_cap_override">{{
                    t('settings.learning.capLabel')
                }}</Label>
                <Input
                    id="new_item_cap_override"
                    v-model="form.new_item_cap_override"
                    type="number"
                    min="0"
                    max="100"
                    class="max-w-xs"
                    :placeholder="t('settings.learning.capPlaceholder')"
                />
                <p class="text-sm text-muted-foreground">
                    {{ t('settings.learning.capNote') }}
                </p>
                <InputError :message="form.errors.new_item_cap_override" />
            </div>

            <div class="grid gap-2">
                <Label for="context_emphasis">{{
                    t('settings.learning.contentFocus')
                }}</Label>
                <Select v-model="form.context_emphasis">
                    <SelectTrigger
                        id="context_emphasis"
                        class="w-full max-w-xs"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="value in contextEmphases"
                            :key="value"
                            :value="value"
                        >
                            {{ t(`settings.learning.focus.${value}`) }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.context_emphasis" />
            </div>

            <div class="grid gap-2">
                <Label for="review_mode">{{
                    t('settings.learning.reviewStyle')
                }}</Label>
                <Select v-model="form.review_mode">
                    <SelectTrigger id="review_mode" class="w-full max-w-xs">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="value in reviewModes"
                            :key="value"
                            :value="value"
                        >
                            {{ t(`settings.learning.mode.${value}`) }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p class="text-sm text-muted-foreground">
                    {{ t('settings.learning.modeNote') }}
                </p>
                <InputError :message="form.errors.review_mode" />
            </div>

            <div class="grid gap-2">
                <Label for="lesson_name">{{
                    t('settings.learning.lessonName')
                }}</Label>
                <Input
                    id="lesson_name"
                    v-model="form.lesson_name"
                    class="max-w-xs"
                    maxlength="40"
                    autocomplete="off"
                />
                <p class="text-sm text-muted-foreground">
                    {{ t('settings.learning.lessonNameNote') }}
                </p>
                <InputError :message="form.errors.lesson_name" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing" type="submit">{{
                    t('common.save')
                }}</Button>
            </div>
        </form>

        <div v-if="webPush" class="flex flex-col gap-2">
            <Label>{{ t('settings.learning.push.title') }}</Label>
            <p class="text-sm text-muted-foreground">
                {{ t('settings.learning.push.digest') }}
            </p>
            <p class="text-sm text-muted-foreground">
                {{ t('settings.learning.push.daily') }}
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
                <Label for="push-enabled">{{
                    t('settings.learning.push.enable')
                }}</Label>
            </div>
            <InputError :message="webPush.error.value ?? undefined" />
        </div>

        <form class="flex flex-col gap-6" @submit.prevent="submitInterests">
            <div class="grid gap-2">
                <Label>{{ t('settings.learning.interests.title') }}</Label>
                <p class="text-sm text-muted-foreground">
                    {{ t('settings.learning.interests.note') }}
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
                            t(`settings.learning.interests.${tag}`)
                        }}</Label>
                    </div>
                </div>
                <InputError :message="interestTagsError" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="interestsForm.processing" type="submit">{{
                    t('settings.learning.interests.save')
                }}</Button>
            </div>
        </form>
    </div>
</template>
