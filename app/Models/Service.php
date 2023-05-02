<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name','duration','cleaning_time' , 'max_clients_per_slot', 'available_days_slots'];

    public function generateSlots($date)
    {
        // Get the configuration for the given day
        $dayOfWeek = Carbon::parse($date)->format('D');
        $config = $this->configurations()
            ->where('day', strtolower($dayOfWeek))
            ->first();

        // Return an empty array if no configuration is found for the day
        if (!$config) {
            return [];
        }

        $slots = [];
        $startTime = Carbon::parse("{$date} {$config->start_time}");
        $endTime = Carbon::parse("{$date} {$config->end_time}");

        $lunchStartTime = $config->lunch_start_time ? Carbon::parse("{$date} {$config->lunch_start_time}") : null;
        $lunchEndTime = $config->lunch_end_time ? Carbon::parse("{$date} {$config->lunch_end_time}") : null;

        $cleaningStartTime = $config->cleaning_start_time ? Carbon::parse("{$date} {$config->cleaning_start_time}") : null;
        $cleaningEndTime = $config->cleaning_end_time ? Carbon::parse("{$date} {$config->cleaning_end_time}") : null;

        while ($startTime->lessThan($endTime)) {
            if (($lunchStartTime && $startTime->greaterThanOrEqualTo($lunchStartTime) && $startTime->lessThan($lunchEndTime)) ||
                ($cleaningStartTime && $startTime->greaterThanOrEqualTo($cleaningStartTime) && $startTime->lessThan($cleaningEndTime))) {
                // Skip the current slot if it is within a break
                $startTime->addMinutes($this->duration + $this->cleaning_time);
                continue;
            }

            $slotEnd = $startTime->copy()->addMinutes($this->duration);
            $slots[] = [
                'start_time' => $startTime->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
            ];

            // Move to the next slot, considering the cleaning_time
            $startTime = $slotEnd->addMinutes($this->cleaning_time);
        }

        return $slots;
    }

    public function configurations(){
        return $this->hasMany(Configuration::class, 'service_id','id');
    }
}
