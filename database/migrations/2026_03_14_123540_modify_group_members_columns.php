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
        Schema::table('group_members', function (Blueprint $table) {
            // Make columns nullable
            $table->string('father_name')->nullable()->change();
            $table->string('aadhar_number')->nullable()->change();
            $table->string('mid')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->string('father_name')->nullable(false)->change();
            $table->string('aadhar_number')->nullable(false)->change();
            $table->string('mid')->nullable(false)->change();
        });
    }
};
