<script setup>
import { ref, reactive } from 'vue';
// Asegúrate de que la ruta al store sea la correcta
import { useMintyTestStore } from '@/stores/minty-test'; 

// IMPORTANTE: Asignamos defineProps a una variable 'props'
// para poder usarla dentro de las funciones (saveGuest/removeGuest)
const props = defineProps({
    booking: Object
});

const store = useMintyTestStore();

const isAddingGuest = ref(false);
const isSubmitting = ref(false);

const newGuest = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone: ''
});

const saveGuest = async () => {
    isSubmitting.value = true;

    const payload = {
        booking_id: props.booking.id, // Ahora sí funciona 'props'
        ...newGuest
    };

    const success = await store.addGuest(payload);

    if (success) {
        // Limpiar formulario
        newGuest.first_name = '';
        newGuest.last_name = '';
        newGuest.email = '';
        newGuest.phone = '';
        isAddingGuest.value = false;
    } else {
        alert('Error al agregar el huésped.');
    } 
    isSubmitting.value = false;
};

const removeGuest = async (guestId) => {
    if (confirm('¿Borrar huésped?')) {
        await store.deleteGuest(guestId, props.booking.id);
    }
};
</script>

<template>
    <!-- Tarjeta básica con borde gris -->
    <div class="border border-gray-400 p-4 rounded mb-4 bg-white">
        
        <!-- Info Reserva -->
        <div class="mb-4 border-b pb-2">
            <h2 class="font-bold text-lg">Reserva #{{ booking.id }}</h2>
            <!-- Asegúrate de que los nombres de campos coinciden con tu BBDD -->
            <p>In: {{ booking.checkin_at }} | Out: {{ booking.checkout_at }}</p>
        </div>

        <!-- Lista de Huéspedes -->
        <div class="mb-4">
            <h3 class="font-bold">Huéspedes:</h3>
            
            <ul v-if="booking.guests && booking.guests.length > 0">
                <li v-for="guest in booking.guests" :key="guest.id" class="flex justify-between items-center mb-1">
                    <!-- Datos del huésped -->
                    <span>- {{ guest.first_name }} {{ guest.last_name }} ({{ guest.email }})</span>
                    
                    <!-- Botón Borrar (Rojo básico) -->
                    <button 
                        @click="removeGuest(guest.id)" 
                        class="text-red-600 font-bold ml-2 border px-2 text-xs">
                        X
                    </button>
                </li>
            </ul>
            <p v-else class="text-gray-500 italic">No hay huéspedes.</p>
        </div>

        <div>
            <button 
                v-if="!isAddingGuest" 
                @click="isAddingGuest = true"
                class="bg-blue-500 text-white px-3 py-1 rounded">
                + Añadir Huésped
            </button>

            <!-- Opción B: El Formulario (visible solo si isAddingGuest es true) -->
            <form v-else @submit.prevent="saveGuest" class="bg-gray-100 p-2 mt-2">
                <div class="flex flex-col gap-2">
                    <input v-model="newGuest.first_name" placeholder="Nombre" required class="border p-1">
                    <input v-model="newGuest.last_name" placeholder="Apellidos" required class="border p-1">
                    <input v-model="newGuest.email" type="email" placeholder="Email" required class="border p-1">
                    <input v-model="newGuest.phone" placeholder="Teléfono" class="border p-1">
                </div>

                <div class="mt-2 flex gap-2">
                    <button type="submit" :disabled="isSubmitting" class="bg-green-500 text-white px-3 py-1 rounded">
                        Guardar
                    </button>
                    <button type="button" @click="isAddingGuest = false" class="bg-gray-500 text-white px-3 py-1 rounded">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

    </div>
</template>