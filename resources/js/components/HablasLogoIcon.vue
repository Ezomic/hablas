<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<!--
  Replaces the Laravel starter-kit mark, which was Laravel's trademark standing
  in as this app's logo. A speech bubble — "hablas" is "you speak".

  Drawn with currentColor strokes only (no knocked-out fill), so it reads
  correctly on any background: the dark square on the landing page, the
  sidebar-primary square in the app chrome, light mode or dark.
-->
<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.9"
        stroke-linecap="round"
        stroke-linejoin="round"
        :class="className"
        v-bind="$attrs"
    >
        <path
            d="M20 14.5A2.5 2.5 0 0 1 17.5 17H10l-4.6 3.4A.6.6 0 0 1 4.5 20V6.5A2.5 2.5 0 0 1 7 4h10.5A2.5 2.5 0 0 1 20 6.5v8Z"
        />
        <path d="M8.5 8.75h7" />
        <path d="M8.5 12.25h4.5" />
    </svg>
</template>
