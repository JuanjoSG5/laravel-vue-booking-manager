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

const errors = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: ''
})

const resetFormState = () => {
    newGuest.first_name = '';
    newGuest.last_name = '';
    newGuest.email = '';
    newGuest.phone_number = '';

    errors.first_name = '';
    errors.last_name = '';
    errors.email = '';
    errors.phone_number = '';
};

const editGuest = (guest) => {
    resetFormState();
    editingGuestId.value = guest.id; 
    newGuest.first_name = guest.first_name;
    newGuest.last_name = guest.last_name;
    newGuest.email = guest.email;
    newGuest.phone_number = guest.phone_number;

    // To show the form
    isAddingGuest.value = true;      
};

const validateForm = () => {
    let valid = true;

    if (!newGuest.first_name) {
        errors.first_name = 'El nombre es obligatorio.';
        valid = false;
    } else {
        errors.first_name = '';
    }

    if (!newGuest.last_name) {
        errors.last_name = 'Los apellidos son obligatorios.';
        valid = false;
    } else {
        errors.last_name = '';
    }


    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!newGuest.email) {
        errors.email = 'El email es obligatorio.';
        valid = false;
    } else if (!emailRegex.test(newGuest.email)) {
        errors.email = 'El email no es válido.';
        valid = false;
    } 

    const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s./0-9]*$/;
    if (newGuest.phone_number && !phoneRegex.test(newGuest.phone_number)) {
        errors.phone_number = 'El teléfono contiene caracteres inválidos.';
        valid = false;
    } else if (newGuest.phone_number && newGuest.phone_number.length < 6) {
        errors.phone_number = 'El número es demasiado corto.';
        valid = false;
    }

    return valid;
}

const saveGuest = async () => {

    if (!validateForm()) return;

    isSubmitting.value = true;
    try{
        let success = false;
        const payload = {
            booking_id: props.booking.id, 
            ...newGuest
        };

        if (editingGuestId.value){        
            success = await store.updateGuest({ id: editingGuestId.value, ...payload });
        }else {
            success = await store.addGuest(payload);
        }


        if (success) {
            resetFormState();
            isAddingGuest.value = false;
            editingGuestId.value = null;
        } 
    } catch (error) {
        console.error("Error en la operación:", error);
        alert('Hubo un error de comunicación con el servidor.');
    } finally {
        isSubmitting.value = false;
    }
};

const cancelForm = () => {
    isAddingGuest.value = false;
    editingGuestId.value = null;
    newGuest.first_name = '';
    newGuest.last_name = '';
    newGuest.email = '';
    newGuest.phone_number = '';
}

const openAddForm = () => {
    resetFormState();
    isAddingGuest.value = true;
    editingGuestId.value = null;
};

const removeGuest = async (guestId) => {
    if (confirm('¿Borrar huésped?')) {
        await store.deleteGuest(guestId, props.booking.id);
    }
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
};
</script>

<template>
    <div class="border border-gray-400 p-4 rounded mb-4 bg-white">
        
         <div class="mb-6 pb-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-xl text-gray-800">Reserva #{{ booking.id }}</h2>
                    <!-- Badge de Status -->
                    <span :class="{
                        'bg-green-100 text-green-700': booking.status === 'confirmed',
                        'bg-red-100 text-red-700': booking.status === 'cancelled',
                        'bg-yellow-100 text-yellow-700': booking.status === 'pending'
                    }" class="text-xs px-2 py-0.5 rounded-full font-semibold uppercase">
                        {{ booking.status }}
                    </span>
                </div>
                <div class="text-sm text-gray-500 mt-2 flex gap-3">
                    <span>{{ formatDate(booking.checkin_at) }}</span>
                    <span>➞</span>
                    <span>{{ formatDate(booking.checkout_at) }}</span>
                </div>
            </div>
        </div>

        <!-- Lista de Huéspedes -->
         <div class="mb-6">
            <h3 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                Huéspedes 
                <span v-if="booking.guests" class="text-xs bg-gray-200 text-gray-600 px-2 rounded-full">{{ booking.guests.length }}</span>
            </h3>
            
            <ul v-if="booking.guests && booking.guests.length > 0" class="space-y-2">
                <li v-for="guest in booking.guests" :key="guest.id" 
                    class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-transparent hover:border-gray-200 hover:bg-white hover:shadow-sm transition-all duration-200">
                    
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-800">{{ guest.first_name }} {{ guest.last_name }}</span>
                        <span class="text-sm text-gray-500">{{ guest.email }}</span>
                    </div>
                
                    <div class="flex gap-2">
                        <button 
                            @click="editGuest(guest)" 
                            class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1.5 rounded transition-colors">
                            Editar
                        </button>

                        <button 
                            @click="removeGuest(guest.id)" 
                            class="text-xs font-semibold text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1.5 rounded transition-colors">
                            Eliminar
                        </button>
                    </div>
                </li>
            </ul>
            <div v-else class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">No hay huéspedes registrados.</p>
            </div>
        </div>
        
        <div v-if="booking.status !== 'cancelled'">
            <button 
                v-if="!isAddingGuest" 
                @click="openAddForm"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
                + Añadir Huésped
            </button>

            <form v-else @submit.prevent="saveGuest" class="bg-gray-50 p-4 mt-4 rounded-xl border border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                    <div class="min-h-[75px]"> 
                        <label class="text-xs font-bold text-gray-500 ml-1">Nombre</label>
                        <input v-model="newGuest.first_name" placeholder="Ej: Juan" 
                            class="border p-2 w-full rounded-md mt-1 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                            :class="errors.first_name ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                        <p class="text-red-500 text-[11px] mt-0.5 ml-1 h-3">{{ errors.first_name }}</p>
                    </div>

                    <div class="min-h-[75px]">
                        <label class="text-xs font-bold text-gray-500 ml-1">Apellidos</label>
                        <input v-model="newGuest.last_name" placeholder="Ej: Sánchez" 
                            class="border p-2 w-full rounded-md mt-1 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                            :class="errors.last_name ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                        <p class="text-red-500 text-[11px] mt-0.5 ml-1 h-3">{{ errors.last_name }}</p>
                    </div>
                    <div class="min-h-[75px]">
                        <label class="text-xs font-bold text-gray-500 ml-1">Email</label>
                        <input v-model="newGuest.email" placeholder="Ej: juan@example.com" 
                            class="border p-2 w-full rounded-md mt-1 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                            :class="errors.email ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                        <p class="text-red-500 text-[11px] mt-0.5 ml-1 h-3">{{ errors.email }}</p>
                    </div>
                    <div class="min-h-[75px]">
                        <label class="text-xs font-bold text-gray-500 ml-1">Telefono</label>
                        <input v-model="newGuest.phone_number" placeholder="Ej: +34 600 123 456" 
                            class="border p-2 w-full rounded-md mt-1 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                            :class="errors.phone_number ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                        <p class="text-red-500 text-[11px] mt-0.5 ml-1 h-3">{{ errors.phone_number }}</p>
                    </div>
                </div>

                <div class="mt-2 flex gap-3">
                    <button type="submit" :disabled="isSubmitting" 
                        class="flex-1 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold py-2 rounded-lg transition shadow-sm">
                        {{ isSubmitting ? 'Procesando...' : (editingGuestId ? 'Actualizar' : 'Guardar') }}
                    </button>
                    <button type="button" @click="cancelForm" 
                        class="flex-1 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
        <div v-else class="text-center p-3 bg-red-50 rounded-lg border border-red-100">
            <p class="text-red-600 text-sm font-medium">Reserva cancelada: No se pueden gestionar huéspedes.</p>
        </div>

    </div>
</template>