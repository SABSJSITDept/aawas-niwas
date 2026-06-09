<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('travel_forms', function (Blueprint $table) {
            $table->id();
            $table->string('travel_type');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->time('check_in_time');
            $table->time('check_out_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_forms');
    }
};
