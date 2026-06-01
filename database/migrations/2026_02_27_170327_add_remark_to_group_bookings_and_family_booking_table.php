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
        Schema::table('group_bookings', function (Blueprint $table) {
            $table->text('remark')->nullable()->after('booking_id');
        });

        Schema::table('family_booking', function (Blueprint $table) {
            $table->text('remark')->nullable()->after('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_bookings', function (Blueprint $table) {
            $table->dropColumn('remark');
        });

        Schema::table('family_booking', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
};
