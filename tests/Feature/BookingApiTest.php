<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Booking;
use App\Models\Guest;

class BookingApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_fetch_bookings_with_guests()
    {
        $booking = Booking::factory()->create();
        Guest::factory()->count(2)->create(['booking_id' => $booking->id]);

        $response = $this->getJson('/api/bookings');

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $booking->id
        ]);

        $this->assertArrayHasKey('guests', $response->json()[0]);
        $this->assertCount(2, $response->json()[0]['guests']);
    }
}