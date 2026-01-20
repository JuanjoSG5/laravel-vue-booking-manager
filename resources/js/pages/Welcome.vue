<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import moment from 'moment';

import { useMintyTestStore } from '../stores/minty-test';

import { onMounted } from 'vue';

const { t } = useI18n();
const store = useMintyTestStore();

onMounted(() => {
    store.getBookings();
});
</script>

<template>
    <Head title="Minty Test 2026" />

    <div class="flex flex-col items-center justify-center gap-6 py-48 px-6 ">
        <div class="flex flex-col items-center gap-3 text-center max-w-2xl">
            <img
                src="/images/minty-logo.png"
                alt="minty"
                class="h-48 w-auto"
            />
            <h1 class="text-3xl font-bold" v-html="t('welcome.headerTitle')">
            </h1>
            <p class="text-gray-600" v-html="t('welcome.headerDescription')">
            </p>
        </div>
    </div>
</template>