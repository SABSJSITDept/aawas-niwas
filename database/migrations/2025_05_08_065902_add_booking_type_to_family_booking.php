<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // booking_type column already exists in family_booking table creation
    }

    public function down(): void
    {
        Schema::table('family_booking', function (Blueprint $table) {
            $table->dropColumn('booking_type');
        });
    }
};

