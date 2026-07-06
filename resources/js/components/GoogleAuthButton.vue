<script setup lang="ts">
import { redirect } from '@/routes/auth/google';
</script>

<template>
    <a
        :href="redirect().url"
        class="mb-6 flex w-full items-center justify-center gap-2 rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm hover:bg-accent"
    >
        Continue with Google
    </a>

    <div class="relative mb-6 text-center text-sm text-muted-foreground">
        <span class="bg-background px-2">or continue with email</span>
    </div>
</template>
