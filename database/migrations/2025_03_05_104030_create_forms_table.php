<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('phone', 10)->unique();
            $table->string('mid')->nullable();;
            $table->string('aadhar_number', 12)->unique();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('aanchal')->nullable();
            $table->string('department');
            $table->string('post')->nullable();
            $table->boolean('is_coming')->default(false);
            $table->string('travel_type')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forms');
    }
};


