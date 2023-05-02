<?php

namespace App\Rules;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Contracts\Validation\Rule;

class MaxClientsPerSlot implements Rule
{
    protected $service_id;
    protected $booking_date;
    protected $start_time;
    protected $end_time;

    public function __construct($service_id, $booking_date, $start_time, $end_time)
    {
        $this->service_id = $service_id;
        $this->booking_date = $booking_date;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    public function passes($attribute, $value)
    {
        $service = Service::find($this->service_id);

        if ($service === null) {
            return false;
        }

        $existingBookingsCount = Booking::query()
            ->where('service_id', $this->service_id)
            ->where('booking_date', $this->booking_date)
            ->where('start_time', $this->start_time)
            ->where('end_time', $this->end_time)
            ->count();

        return count($value) + $existingBookingsCount <= $service->max_clients_per_slot;
    }

    public function message()
    {
        return 'The maximum clients per slot for this service has been exceeded.';
    }
}
