<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('family_booking', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('phone', 10);
            $table->string('aadhar_number', 12)->unique();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('aanchal')->nullable();
            $table->string('department');
            $table->string('post')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('booking_type')->default('family');
            $table->string('status')->default('pending');
            $table->integer('total_persons')->nullable();
            $table->string('mid', 12)->nullable();
            $table->boolean('is_veer_parivar')->default(false);
            $table->string('veer_relation')->nullable();
            $table->string('ms_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('family_booking');
    }
};
 