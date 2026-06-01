<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('thana_sant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thana_id');
            $table->string('sant_name');
            $table->timestamps();

            $table->foreign('thana_id')->references('id')->on('sadhu_sadvis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thana_sant');
    }
};
