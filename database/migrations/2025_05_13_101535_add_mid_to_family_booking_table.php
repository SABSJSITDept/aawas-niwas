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
    // mid column already exists in family_booking table creation
}

public function down()
{
    Schema::table('family_bookings', function (Blueprint $table) {
        $table->dropColumn('mid');
    });
}

};
