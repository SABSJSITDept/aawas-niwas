<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('family_booking', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('age');
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('age');
        });
    }

    public function down(): void
    {
        Schema::table('family_booking', function (Blueprint $table) {
            $table->dropColumn('gender');
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
