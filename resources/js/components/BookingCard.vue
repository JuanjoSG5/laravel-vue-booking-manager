<script setup>
import { ref, reactive } from 'vue';
import { useMintyTestStore } from '@/stores/minty-test'; 

const props = defineProps({
    booking: Object
});

const store = useMintyTestStore();

const isAddingGuest = ref(false);
const isSubmitting = ref(false);
const editingGuestId = ref(null);

const newGuest = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: ''
});

const editGuest = (guest) => {
    editingGuestId.value = guest.id; 
    newGuest.first_name = guest.first_name;
    newGuest.last_name = guest.last_name;
    newGuest.email = guest.email;
    newGuest.phone_number = guest.phone_number;

    // To show the form
    isAddingGuest.value = true;      
};

const saveGuest = async () => {
    isSubmitting.value = true;
    let success = false;

    if (editingGuestId.value){
        const payload = {
            id: editingGuestId.value,
            booking_id: props.booking.id, 
            ...newGuest
        };
    
        success = await store.updateGuest(payload);
    }else {
        const payload = {
            booking_id: props.booking.id, 
            ...newGuest
        };
    
        success = await store.addGuest(payload);
    }


    if (success) {
        newGuest.first_name = '';
        newGuest.last_name = '';
        newGuest.email = '';
        newGuest.phone_number = '';
        isAddingGuest.value = false;
        editingGuestId.value = null;
    } else {
        alert('Error al agregar el huésped.');
    } 
    isSubmitting.value = false;
};

const cancelForm = () => {
    isAddingGuest.value = false;
    editingGuestId.value = null;
    newGuest.first_name = '';
    newGuest.last_name = '';
    newGuest.email = '';
    newGuest.phone_number = '';
}

const removeGuest = async (guestId) => {
    if (confirm('¿Borrar huésped?')) {
        await store.deleteGuest(guestId, props.booking.id);
    }
};
</script>

<template>
    <div class="border border-gray-400 p-4 rounded mb-4 bg-white">
        
        <div class="mb-4 border-b pb-2">
            <h2 class="font-bold text-lg">Reserva #{{ booking.id }}</h2>
            <p>In: {{ booking.checkin_at }} | Out: {{ booking.checkout_at }}</p>
        </div>

        <!-- Lista de Huéspedes -->
        <div class="mb-4">
            <h3 class="font-bold">Huéspedes:</h3>
            
            <ul v-if="booking.guests && booking.guests.length > 0">
                <li v-for="guest in booking.guests" :key="guest.id" class="flex justify-between items-center mb-1">
                    <span>- {{ guest.first_name }} {{ guest.last_name }} ({{ guest.email }})</span>
                
                    <button 
                        @click="editGuest(guest)" 
                        class="text-blue-600 font-bold border px-2 text-xs">
                        Edit
                    </button>

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

            <form v-else @submit.prevent="saveGuest" class="bg-gray-100 p-2 mt-2">
                <div class="flex flex-col gap-2">
                    <input v-model="newGuest.first_name" placeholder="Nombre" required class="border p-1">
                    <input v-model="newGuest.last_name" placeholder="Apellidos" required class="border p-1">
                    <input v-model="newGuest.email" type="email" placeholder="Email" required class="border p-1">
                    <input v-model="newGuest.phone_number" placeholder="Teléfono" class="border p-1">
                </div>

                <div class="mt-2 flex gap-2">
                    <button type="submit" :disabled="isSubmitting" class="bg-green-500 text-white px-3 py-1 rounded">
                        Guardar
                    </button>
                    <button type="button" @click="cancelForm" class="bg-gray-500 text-white px-3 py-1 rounded">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

    </div>
</template>