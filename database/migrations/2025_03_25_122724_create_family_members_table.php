<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {if (!Schema::hasTable('family_members')) {
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('family_id'); // Foreign key reference to family_booking
            $table->string('name');
            $table->string('mobile', 10);
            $table->string('aadhar_number', 12)->unique();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('family_id')->references('id')->on('family_booking')->onDelete('cascade');
        });
    }
}
    public function down()
    {
        Schema::dropIfExists('family_members');
    }

};
