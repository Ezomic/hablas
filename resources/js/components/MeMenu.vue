<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { Library, LogOut, Settings } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import InstallAppButton from '@/components/InstallAppButton.vue';
import InterfaceLocaleSwitcher from '@/components/InterfaceLocaleSwitcher.vue';
import PortalSwitcher from '@/components/PortalSwitcher.vue';
import { Card, CardContent } from '@/components/ui/card';
import UserInfo from '@/components/UserInfo.vue';
import { clearOfflineData } from '@/composables/useOfflineSync';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import { index as unitsIndex } from '@/routes/units';

const { t } = useI18n();
const page = usePage();
const user = computed(() => page.props.auth.user);

function handleLogout() {
    router.flushAll();
}
</script>

<template>
    <Card data-testid="me-menu">
        <CardContent class="flex flex-col gap-4">
            <UserInfo v-if="user" :user="user" :show-email="true" />

            <ul class="flex flex-col divide-y rounded-lg border">
                <li>
                    <Link
                        :href="unitsIndex()"
                        class="flex items-center gap-3 px-4 py-3 text-sm active:bg-accent"
                    >
                        <Library class="size-5 text-muted-foreground" />
                        {{ t('nav.units') }}
                    </Link>
                </li>
                <li>
                    <Link
                        :href="edit()"
                        class="flex items-center gap-3 px-4 py-3 text-sm active:bg-accent"
                    >
                        <Settings class="size-5 text-muted-foreground" />
                        {{ t('nav.settings') }}
                    </Link>
                </li>
            </ul>

            <div class="flex flex-wrap items-center gap-3">
                <InterfaceLocaleSwitcher />
                <PortalSwitcher />
                <InstallAppButton />
            </div>

            <Link
                :href="logout()"
                as="button"
                class="flex w-fit items-center gap-2 text-sm text-muted-foreground"
                data-test="logout-button"
                @click="handleLogout"
                @success="clearOfflineData"
            >
                <LogOut class="size-4" />
                {{ t('nav.logOut') }}
            </Link>
        </CardContent>
    </Card>
</template>
