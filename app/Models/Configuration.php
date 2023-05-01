<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;
    protected $fillable = ['service_id','day','start_time','end_time','lunch_start_time','lunch_end_time','lunch_end_time','cleaning_end_time'];
}
