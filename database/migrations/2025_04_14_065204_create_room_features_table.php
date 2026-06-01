<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomFeaturesTable extends Migration
{
    public function up()
    {
        Schema::create('room_details', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('hotel_id'); 
            $table->string('room_number', 50); 
            $table->unsignedBigInteger('category_id'); 
            $table->enum('ac', ['AC', 'Non-AC'])->default('AC'); 
            $table->enum('attach_bath', ['Yes', 'No'])->default('Yes'); 
            $table->enum('toilet_type', ['Western', 'Indian'])->default('Western'); 
            $table->timestamps(); 
            
            $table->foreign('hotel_id')->references('id')->on('hotel_details')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');

            
            $table->index('hotel_id');
            $table->index('room_number');
            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_details');
    }
}
