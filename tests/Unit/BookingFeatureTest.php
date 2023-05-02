<?php

namespace Tests\Unit;

use App\Console\Kernel;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

class BookingFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        app()->make(Kernel::class)->bootstrap();
    }


    public function testGetSlots()
    {
        // Create a service and configuration
        // Run the seeder
        Artisan::call('db:seed');
        // ... Create related configuration and ScheduledOff records

        // Call the API
        $response = $this->getJson('/api/get-slots');

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'service',
                    'Available_slots',
                ],
            ]);
    }

    public function testStoreBooking()
    {
        // Create a service and appointment
        // Run the seeder
        Artisan::call('db:seed');
        // ... Create related appointment and configuration records

        // Define booking data
        $bookingData = [
            'service_id' => 1,
            'booking_date' => Carbon::now()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '10:30',
            'first_name' => [$this->faker->firstName()],
            'last_name' => [$this->faker->lastName()],
            'email' => [$this->faker->safeEmail()],
        ];

        // Call the API
        $response = $this->postJson('/api/save-slots', $bookingData);

        // Assert the response status and that the booking was created
        $response->assertStatus(200);

        $this->assertDatabaseHas('bookings', [
            'appointment_id' => $bookingData['service_id'],
            'booking_date' => $bookingData['booking_date'],
            'start_time' => $bookingData['start_time'],
            'end_time' => $bookingData['end_time'],
            'first_name' => $bookingData['first_name'][0],
            'last_name' => $bookingData['last_name'][0],
            'email' => $bookingData['email'][0],
        ]);
    }
}
