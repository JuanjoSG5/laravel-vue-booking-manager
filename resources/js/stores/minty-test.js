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
            this.loading = true
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
        },
        async updateGuest(guestData, bookingId) {
            try {
                const response = await axios.put(`/api/guests/${guestData.id}`, guestData);
                
                const booking = this.bookings.find(b => b.id === bookingId);
                if (booking) {
                    const index = booking.guests.findIndex(g => g.id === guestData.id);
                    if (index !== -1) {
                        booking.guests[index] = response.data;
                    }
                }
                return true;
            } catch (error) {
                console.error(error);
                return false;
            }
        },
        async deleteGuest(guestId, bookingId) {
            try {
                await axios.delete(`/api/guests/${guestId}`);
                
                const booking = this.bookings.find(b => b.id === bookingId);
                if (booking) {
                    booking.guests = booking.guests.filter(g => g.id !== guestId);
                }
                return true;
            } catch (error) {
                console.error('Error borrando huésped:', error);
                return false;
            }
        }
    },
});
