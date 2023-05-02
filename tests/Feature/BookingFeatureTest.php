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

        // get a scheduled off for the service
        $scheduledOff = ScheduledOff::where('service_id',$service->id)->first();

        $response = $this->getJson('/api/get-slots');

        // Check that the response is successful
        $response->assertStatus(Response::HTTP_OK);

        // Check that the service is in the response
        $response->assertJsonFragment([
            'id' => $service->id,
            'service' => $service->name,
        ]);

        // Check that the slot is not in the response
        $response->assertJsonMissing([
            'date' => date('Y-m-d', strtotime($scheduledOff->start_time)),
        ]);
    }

}
