<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('group_bookings', function (Blueprint $table) {
            $table->string('booking_type')->default('group');
        });
    }

    public function down(): void
    {
        Schema::table('group_bookings', function (Blueprint $table) {
            $table->dropColumn('booking_type');
        });
    }
};
