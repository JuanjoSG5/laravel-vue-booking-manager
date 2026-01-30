<script setup>
import { Head } from '@inertiajs/vue3';
import { useMintyTestStore } from '../stores/minty-test';
import { ref, watch, onMounted, onUnmounted } from 'vue';
import BookingCard from '../components/BookingCard.vue';
const store = useMintyTestStore();
let debounceTimeout = null;
const searchQuery = ref('');


watch(searchQuery, (newQuery) => {
    clearTimeout(debounceTimeout);

    debounceTimeout = setTimeout(() => {
        store.getBookings(1, newQuery);
    }, 300);
});

onUnmounted(() => {
    clearTimeout(debounceTimeout);
});

onMounted(() => {
    store.getBookings();
});


const changePage = (linkUrl) => {
    if (!linkUrl || store.loading ) return;

    try {
        const url = new URL(linkUrl);
        const page = url.searchParams.get('page') || 1;
        store.getBookings(page, searchQuery.value);
    } catch {
        console.error('URL inválida:', linkUrl);
    }
}
</script>

<template>
    <Head title="Minty Test 2026" />

    <div class="flex flex-col items-center justify-center min-h-screen p-6 bg-gray-100">
        <h1 class="text-4xl font-bold mb-6">Gestion de reservas</h1>
        <div class="relative w-full sm:w-80 mb-8">
            <input 
                v-model="searchQuery" 
                type="text" 
                placeholder="Buscar por huésped..." 
                class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all duration-200"
            >

            <div v-if="store.loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                 <div class="animate-spin h-4 w-4 border-2 border-blue-500 border-t-transparent rounded-full"></div>
            </div>
        </div>
    
        <div v-if="store.loading" class="text-lg">Cargando reservas...</div>

        <div v-else class="w-full max-w-7xl">
            <section v-if="store.bookings.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
               
                <BookingCard 
                    v-for="booking in store.bookings" 
                    :key="booking.id" 
                    :booking="booking" 
                />
            </section>

            <div v-else class="text-center mt-20 p-10 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <p class="text-gray-500 text-lg">No se han encontrado reservas que coincidan con tu búsqueda.</p>
                <button @click="searchQuery = ''" class="mt-4 text-blue-600 font-semibold hover:underline">Limpiar búsqueda</button>
            </div>

            <div v-if="store.pagination && store.pagination.last_page > 1" class="flex justify-center mt-12 gap-2">
                <button 
                    v-for="(link, index) in store.pagination.links" 
                    :key="index"
                    @click="changePage(link.url)"
                    :disabled="!link.url || link.active"
                    class="px-4 py-2 border rounded-lg transition-all duration-200"
                    :class="{
                        'bg-blue-600 text-white border-blue-600 shadow-md': link.active,
                        'bg-white text-gray-600 border-gray-300 hover:bg-gray-50': !link.active,
                        'opacity-50 cursor-not-allowed bg-gray-100': !link.url
                    }"
                    v-html="link.label" 
                ></button>
            </div>
        </div>
    </div>
</template>