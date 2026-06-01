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
    // total_persons column already exists in family_booking table creation
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_booking', function (Blueprint $table) {
            //
        });
    }
};
