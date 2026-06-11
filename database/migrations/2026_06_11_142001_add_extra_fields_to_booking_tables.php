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
        // For forms table (VIP/Other booking)
        if (Schema::hasTable('forms') && !Schema::hasColumn('forms', 'extra_fields')) {
            Schema::table('forms', function (Blueprint $table) {
                $table->json('extra_fields')->nullable()->after('status');
            });
        }

        // For family_booking table
        if (Schema::hasTable('family_booking') && !Schema::hasColumn('family_booking', 'extra_fields')) {
            Schema::table('family_booking', function (Blueprint $table) {
                $table->json('extra_fields')->nullable()->after('status');
            });
        }

        // For group_bookings table
        if (Schema::hasTable('group_bookings') && !Schema::hasColumn('group_bookings', 'extra_fields')) {
            Schema::table('group_bookings', function (Blueprint $table) {
                $table->json('extra_fields')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('forms') && Schema::hasColumn('forms', 'extra_fields')) {
            Schema::table('forms', function (Blueprint $table) {
                $table->dropColumn('extra_fields');
            });
        }

        if (Schema::hasTable('family_booking') && Schema::hasColumn('family_booking', 'extra_fields')) {
            Schema::table('family_booking', function (Blueprint $table) {
                $table->dropColumn('extra_fields');
            });
        }

        if (Schema::hasTable('group_bookings') && Schema::hasColumn('group_bookings', 'extra_fields')) {
            Schema::table('group_bookings', function (Blueprint $table) {
                $table->dropColumn('extra_fields');
            });
        }
    }
};
