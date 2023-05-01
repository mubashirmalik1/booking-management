<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // service name
            $table->integer('duration'); // duration is in minutes and its duration time for each slot
            $table->integer('cleaning_time'); // break time between each slot
            $table->integer('max_clients_per_slot'); // max customer per slot
            $table->integer('available_days_slots'); // slots should be available for how many days e.g like user can see and book for next 7 days
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
