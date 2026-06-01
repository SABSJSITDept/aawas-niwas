<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMembersTable extends Migration
{
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_booking_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('mobile_number');
            $table->string('aadhar_number');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('group_booking_id')->references('id')->on('group_bookings')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_members');
    }
}
