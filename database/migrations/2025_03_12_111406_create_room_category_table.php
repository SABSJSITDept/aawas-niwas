<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('room_category', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('hotel_id'); 
            $table->string('category_name'); 
            $table->integer('category_id')->nullable(); 
            $table->string('floor', 50)->nullable(); 
            $table->integer('beds')->default(1); 
            $table->integer('extra_capacity')->default(0); 
            $table->integer('total_capacity')->virtualAs('beds + extra_capacity'); 
            $table->text('room_number'); 
            $table->timestamps(); 

           
            $table->foreign('hotel_id')->references('id')->on('hotel_details')->onDelete('cascade');

            
            $table->index('hotel_id');
            $table->index('category_id');
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('room_category');
    }
};
