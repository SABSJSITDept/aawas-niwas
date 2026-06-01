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
        // booked_rooms table already created in 2025_05_03_113217_create_booked_rooms_table.php
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to drop since this migration does not create the table
    }
};
