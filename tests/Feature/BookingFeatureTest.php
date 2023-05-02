<?php

namespace Tests\Feature;

use App\Models\ScheduledOff;
use App\Models\Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class BookingFeatureTest extends TestCase
{
    use DatabaseTransactions;

    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh --seed');
    }

    /**
     * Test the getSlots method.
     *
     * @return void
     */
    public function testGetSlots()
    {
       // Artisan::call('migrate:fresh --seed');
        // Create a service and configuration
        $service = Service::first();
       // $service = Service::factory()->create();

        // Create a scheduled off for the service
//        $scheduledOff = ScheduledOff::factory()->create([
//            'service_id' => $service->id,
//        ]);

        // Call the API endpoint
        $response = $this->getJson('/api/slots');

        // Check that the response is successful
        $response->assertStatus(Response::HTTP_OK);

        // Check that the service is in the response
        $response->assertJsonFragment([
            'id' => $service->id,
            'service' => $service->name,
        ]);

        // Check that the slot is not in the response
        $response->assertJsonMissing([
            'date' => $scheduledOff->start_time->format('Y-m-d'),
        ]);
    }

}
