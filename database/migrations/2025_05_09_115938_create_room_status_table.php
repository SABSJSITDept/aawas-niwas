<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomStatusTable extends Migration
{
    public function up()
    {
        Schema::create('room_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('room_number');
            $table->integer('available_capacity');
            $table->string('status');
            $table->timestamps();

            // Add any other necessary constraints
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_status');
    }
}
