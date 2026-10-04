<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

const { t } = useI18n();
</script>

<template>
    <div class="space-y-6">
        <Heading
            variant="small"
            :title="t('settings.delete.title')"
            :description="t('settings.delete.description')"
        />
        <div
            class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10"
        >
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">{{ t('settings.delete.warning') }}</p>
                <p class="text-sm">
                    {{ t('settings.delete.caution') }}
                </p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button
                        variant="destructive"
                        data-test="delete-user-button"
                        >{{ t('settings.delete.title') }}</Button
                    >
                </DialogTrigger>
                <DialogContent>
                    <!--
                        No credential field: the destroy route sits behind the
                        password.confirm middleware, which redirects to the
                        confirm-identity page and re-authenticates with an
                        emailed code (or a passkey) before this runs.
                    -->
                    <Form
                        v-bind="ProfileController.destroy.form()"
                        :options="{
                            preserveScroll: true,
                        }"
                        class="space-y-6"
                        v-slot="{ processing }"
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle>{{
                                t('settings.delete.confirmTitle')
                            }}</DialogTitle>
                            <DialogDescription>
                                {{ t('settings.delete.confirmBody') }}
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary">
                                    {{ t('common.cancel') }}
                                </Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                            >
                                {{ t('settings.delete.title') }}
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
