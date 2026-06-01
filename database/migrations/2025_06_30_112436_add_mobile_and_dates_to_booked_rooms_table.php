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
    Schema::table('booked_rooms', function (Blueprint $table) {
        $table->string('mobile_number')->nullable();
        $table->date('check_in_date')->nullable();
        $table->date('check_out_date')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked_rooms', function (Blueprint $table) {
            //
        });
    }
};
