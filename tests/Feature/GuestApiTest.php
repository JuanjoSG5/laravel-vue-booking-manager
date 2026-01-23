<?php
namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_guest_for_booking()
    {
        $booking = Booking::factory()->create();

        $response = $this->postJson('/api/guests', [
            'first_name' => 'Juanjo',
            'last_name' => 'Sanchez',
            'email' => 'juanjo@example.com',
            'phone_number' => '1234567890',
            'booking_id' => $booking->id,
        ]);

        $response = $this->postJson('/api/guests', $payload);

        $response->assertCreated();
    }
}