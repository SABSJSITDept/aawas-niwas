<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('group_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('city');
            $table->string('state');
            $table->string('aanchal');
            $table->string('travel_type');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->time('check_in_time');
            $table->time('check_out_time');
            $table->integer('total_members');
            $table->integer('total_male');
            $table->integer('total_female');
            $table->integer('sixty_plus_members')->nullable();
            $table->integer('sixty_plus_male')->nullable();
            $table->integer('sixty_plus_female')->nullable();
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('group_bookings');
    }
};