<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['service_id','first_name','last_name', 'email','start_time','end_time','booking_date'];

    protected static function newFactory(): BookingFactory
    {
        return BookingFactory::new();
    }
}
