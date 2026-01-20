<script setup>
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
</script>

<template>
    <div class="flex flex-col items-center justify-center h-screen gap-4">
        <h1 class="text-2xl font-bold">{{ t('welcome.title') }}</h1>
        <p class="text-gray-500">{{ t('welcome.subtitle') }}</p>
    </div>
</template>
