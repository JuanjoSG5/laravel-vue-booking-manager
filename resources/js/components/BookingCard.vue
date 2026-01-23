<script setup>
defineProps({
    booking: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Reserva #{{ booking.id }}</h2>
        <p><strong>Check-in:</strong> {{ booking.checkin_at }}</p>
        <p><strong>Check-out:</strong> {{ booking.checkout_at }}</p>
        <p><strong>Estado:</strong> {{ booking.status }}</p>
    </div>
</template>