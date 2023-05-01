<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AvailableSlot;
use App\Models\Configuration;
use App\Models\ScheduledOff;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Create Men's Haircut service
        $mensHaircutService = Service::create([
            'name' => 'Men Haircut',
            'duration' => 10,
            'cleaning_time' => 5,
            'max_clients_per_slot' => 3,
            'available_days_slots' => 7
        ]);

        // Create Women's Haircut service
        $womensHaircutService = Service::create([
            'name' => 'Women Haircut',
            'duration' => 60,
            'cleaning_time' => 10,
            'max_clients_per_slot' => 3,
            'available_days_slots' => 7,
        ]);

        $days = [
            ['day' => 'mon', 'start_time' => '08:00', 'end_time' => '20:00'],
            ['day' => 'tue', 'start_time' => '08:00', 'end_time' => '20:00'],
            ['day' => 'wed', 'start_time' => '08:00', 'end_time' => '20:00'],
            ['day' => 'thu', 'start_time' => '08:00', 'end_time' => '20:00'],
            ['day' => 'fri', 'start_time' => '08:00', 'end_time' => '20:00'],
            ['day' => 'sat', 'start_time' => '10:00', 'end_time' => '22:00'],
            // Sunday is off, no need to insert a configuration
        ];

        // Create configurations for Men's Haircut service
        foreach($days as $day){
            Configuration::create([
                'service_id' => $mensHaircutService->id,
                'day' => $day['day'],
                'start_time' => $day['start_time'],
                'end_time' => $day['end_time'],
                'lunch_start_time' => '12:00',
                'lunch_end_time' => '13:00',
                'cleaning_start_time' => '15:00',
                'cleaning_end_time' => '16:00',
            ]);
        }

        // Create configurations for Women's Haircut service
        foreach($days as $day){
            Configuration::create([
                'service_id' => $womensHaircutService->id,
                'day' => $day['day'],
                'start_time' => $day['start_time'],
                'end_time' => $day['end_time'],
                'lunch_start_time' => '12:00',
                'lunch_end_time' => '13:00',
                'cleaning_start_time' => '15:00',
                'cleaning_end_time' => '16:00',
            ]);
        }

        // Insert the public holiday into the Scheduled_off table
        $publicHolidayDate = Carbon::now()->addDays(3)->format('Y-m-d');
        ScheduledOff::query()->create([
            'service_id' => $mensHaircutService->id,
            'start_time' => "{$publicHolidayDate} 00:00:00",
            'end_time' => "{$publicHolidayDate} 23:59:59",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        ScheduledOff::query()->create([
            'service_id' => $womensHaircutService->id,
            'start_time' => "{$publicHolidayDate} 00:00:00",
            'end_time' => "{$publicHolidayDate} 23:59:59",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }

}
