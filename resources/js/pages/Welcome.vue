<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import moment from 'moment';
import { useMintyTestStore } from '../stores/minty-test';
import { onMounted } from 'vue';
import BookingCard from '../components/BookingCard.vue';


const { t } = useI18n();
const store = useMintyTestStore();

onMounted(() => {
    store.getBookings();
});
</script>

<template>
    <Head title="Minty Test 2026" />

    <div class="flex flex-col items-center justify-center min-h-screen p-6 bg-gray-100">
        <h1 class="text-4xl font-bold mb-6">Gestion de reservas</h1>
    
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
        </div>
    </div>
</template>