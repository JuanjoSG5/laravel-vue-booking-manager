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
        
        $responseData = $response->json()['data']; 

        $this->assertNotEmpty($responseData);
        $this->assertEquals($booking->id, $responseData[0]['id']);

        $this->assertArrayHasKey('guests', $responseData[0]);
        $this->assertCount(2, $responseData[0]['guests']);
    }
}