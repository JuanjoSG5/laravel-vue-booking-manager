<script setup>
import { useI18n } from 'vue-i18n';

import { useMintyTestStore } from '../stores/minty-test';

const { t } = useI18n();
const store = useMintyTestStore();
</script>

<template>
    <div class="flex flex-col items-center justify-center h-screen gap-4">
        <h1 class="text-2xl font-bold">{{ t('welcome.title') }}</h1>
        <p class="text-gray-500">{{ t('welcome.subtitle') }}</p>

        <!-- Pinia Store Example -->
        <div class="mt-8 p-6 bg-gray-100 rounded-lg">
            <h2 class="text-xl font-semibold mb-4">Pinia Store Demo</h2>
            
            <div class="space-y-3">
                <p>{{ store.greeting }}</p>
                <p>Count: {{ store.count }}</p>
                <p>Double Count: {{ store.doubleCount }}</p>
                
                <div class="flex gap-2">
                    <button 
                        @click="store.increment"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
                    >
                        Increment
                    </button>
                    <button 
                        @click="store.decrement"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                    >
                        Decrement
                    </button>
                    <button 
                        @click="store.reset"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
