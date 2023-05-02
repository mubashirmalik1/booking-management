<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        $start_time = Carbon::parse($this->faker->dateTimeBetween('-1 week', '+1 week'));
        $end_time = $start_time->copy()->addMinutes($this->faker->numberBetween(15, 240));

        return [
            'service_id' => Service::query()->first()->id,
            'booking_date' => date('Y-m-d',strtotime($start_time)),
            'start_time' =>  date('H:i',strtotime($start_time)),
            'end_time' => date('H:i',strtotime($end_time)),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
        ];
    }
}
