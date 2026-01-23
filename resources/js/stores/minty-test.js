import { defineStore } from 'pinia';
import axios from 'axios';

export const useMintyTestStore = defineStore('minty-test', {
    state: () => ({
        testUser: 'Candidato/a',
        bookings: [],
        loading: false,
    }),

    actions: {
        async getBookings() {
            this.loading = true; 
            try {
                const response = await fetch('/api/bookings');

                if (!response.ok) {
                    throw new Error('Failed to fetch bookings');
                }

                const data = await response.json();
                this.bookings = data;
            } catch (err) {
                console.error('Error fetching bookings:', err);
            } finally{
                this.loading = false;
            }
        },
        async addGuest(guestData) {
            try {
                const response = await axios.post('/api/guests', guestData);
                const newGuest = response.data;

                // We find the booking that we are adding the guest to
                const booking = this.bookings.find(searchingBooking => searchingBooking.id === newGuest.booking_id);
                if (booking) {
                    booking.guests.push(newGuest);
                }
                return true; 
            } catch (error) {
                console.error('Error adding guest:', error);
                return false; 
            }
        }
    },
});
