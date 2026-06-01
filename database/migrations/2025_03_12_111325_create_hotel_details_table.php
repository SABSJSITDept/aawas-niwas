<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('hotel_details', function (Blueprint $table) {
        $table->id();
        $table->string('hotel_name');
        $table->string('incharge_name');
        $table->string('contact_number');
        $table->integer('total_rooms');
        $table->enum('common_bath', ['Yes', 'No']);
        $table->enum('lift', ['Yes', 'No']);
        $table->enum('generator', ['Yes', 'No']);
        $table->text('address');
        $table->timestamps();
    });
    
    
}
    public function down(): void
    {
        Schema::dropIfExists('hotel_details');
    }
};
