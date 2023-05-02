<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\ScheduledOff;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getSlots()
    {
        $services = Service::all();
        $date = Carbon::now();
        $slotsByService = [];

        foreach ($services as $service) {
            $serviceSlots = [];
            $availableDays = $service->available_days_slots;

            $holidays = ScheduledOff::where('service_id', $service->id)
                ->whereBetween('start_time', [$date->copy()->startOfDay(), $date->copy()->addDays(7)->endOfDay()])
                ->get()
                ->map(function ($holiday) {
                    return Carbon::parse($holiday->start_time)->format('Y-m-d');
                });

            for ($i = 0; $i < $availableDays; $i++) {
                $currentDate = $date->format('Y-m-d');
                $dayOfWeek = $date->format('D');

                // Check if the current day is Sunday or a holiday
                if ($dayOfWeek != 'Sun' && !$holidays->contains($currentDate)) {
                    $serviceSlots[] = [
                        'date' => $currentDate,
                        'day' => $dayOfWeek,
                        'slots' => $service->generateSlots($currentDate),
                    ];
                }

                $date->addDay(); // Move to the next day
            }

            $date->subDays($availableDays); // Reset the date for the next service

            $slotsByService[] = [
                'id' => $service->id,
                'service' => $service->name,
                'Available_slots' => $serviceSlots,
            ];
        }

        return response()->json($slotsByService);
    }


    public function saveSlot(BookingRequest  $request){

        $validatedData = $request->validated();

        $start_time = Carbon::parse("{$validatedData['booking_date']} {$validatedData['start_time']}");
        $end_time = Carbon::parse("{$validatedData['booking_date']} {$validatedData['end_time']}");

        $numBookings = count($validatedData['first_name']);

        for ($i = 0; $i < $numBookings; $i++) {
            $booking = new Booking();
            $booking->appointment_id = $validatedData['service_id'];
            $booking->start_time = $start_time;
            $booking->end_time = $end_time;
            $booking->first_name = $validatedData['first_name'][$i];
            $booking->last_name = $validatedData['last_name'][$i];
            $booking->email = $validatedData['email'][$i];
            $booking->save();
        }

    }
}
