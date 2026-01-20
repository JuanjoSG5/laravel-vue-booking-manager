import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useMintyTestStore = defineStore('minty-test', () => {
    // State
    const count = ref(0);
    const name = ref('Minty');

    // Getters
    const doubleCount = computed(() => count.value * 2);
    const greeting = computed(() => `Hello from ${name.value}!`);

    // Actions
    function increment() {
        count.value++;
    }

    function decrement() {
        count.value--;
    }

    function setName(newName) {
        name.value = newName;
    }

    function reset() {
        count.value = 0;
        name.value = 'Minty';
    }

    return {
        // State
        count,
        name,
        // Getters
        doubleCount,
        greeting,
        // Actions
        increment,
        decrement,
        setName,
        reset,
    };
});
