<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import moment from 'moment';
import { useMintyTestStore } from '../stores/minty-test';
import { ref, watch, onMounted } from 'vue';
import BookingCard from '../components/BookingCard.vue';
const store = useMintyTestStore();
const searchQuery = ref('');

onMounted(() => {
    store.getBookings();
});

watch(searchQuery, (newQuery) => {
    store.getBookings(1, newQuery);
});

const changePage = (newPage) => {
    if (!url) return;
    const urlParams = new URLSearchParams(url.split('?')[1]);
    const search = urlParams.get('search') || '';
    store.getBookings(newPage, search);
}
</script>

<template>
    <Head title="Minty Test 2026" />

    <div class="flex flex-col items-center justify-center min-h-screen p-6 bg-gray-100">
        <h1 class="text-4xl font-bold mb-6">Gestion de reservas</h1>
        <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Buscar por nombre de huésped..." 
            class="p-2 border border-gray-300 rounded shadow-sm w-64 focus:ring-2 focus:ring-blue-200 outline-none"
        >
    
        <div v-if="store.loading" class="text-lg">Cargando reservas...</div>

        <div v-else>
            <span v-if="store.bookings.length === 0" class="text-lg">No hay reservas disponibles.</span>
            <section v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
               
                <BookingCard 
                    v-for="booking in store.bookings" 
                    :key="booking.id" 
                    :booking="booking" 
                />
            </section>

            <div v-if="store.bookings.length === 0" class="text-center mt-10 text-gray-500">
                No se encontraron reservas con el nombre de ese huésped.
            </div>

            <div v-if="store.pagination && store.pagination.last_page > 1" class="mt-8 flex justify-center gap-2">
                <button 
                    v-for="(link, index) in store.pagination.links" 
                    :key="index"
                    @click="changePage(link.url)"
                    :disabled="!link.url || link.active"
                    class="px-3 py-1 rounded border"
                    :class="{
                        'bg-blue-600 text-white border-blue-600': link.active,
                        'bg-white text-gray-600 border-gray-300 hover:bg-gray-50': !link.active,
                        'opacity-50 cursor-not-allowed': !link.url
                    }"
                    v-html="link.label" 
                ></button>
            </div>
        </div>
    </div>
</template>