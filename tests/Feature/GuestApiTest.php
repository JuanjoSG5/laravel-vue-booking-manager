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

        $payload = [
            'first_name' => 'Jhon',
            'last_name' => 'Doe',
            'email' => 'jhon.doe@example.com',
            'phone_number' => '1234567890',
            'booking_id' => $booking->id,
        ];

        $response = $this->postJson('/api/guests', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('guests', ['email' => 'jhon.doe@example.com']);
    }

    public function test_cannot_create_guest_with_invalid_data()
    {
        $response = $this->postJson('/api/guests', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['booking_id', 'first_name', 'email']);
    }

    public function test_can_update_guest_information()
    {
        $guest = Guest::factory()->create();

        $payload = [
            'first_name' => 'Edited Name'
        ];

        $response = $this->putJson("/api/guests/{$guest->id}", $payload);

        $response->assertOk();
        $this->assertDatabaseHas('guests', ['id' => $guest->id, 'first_name' => 'Edited Name']);
    }

    public function test_can_delete_guest()
    {
        $guest = Guest::factory()->create();

        $response = $this->deleteJson("/api/guests/{$guest->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('guests', ['id' => $guest->id]);
    }
}