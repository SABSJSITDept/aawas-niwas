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
        Schema::table('hotel_details', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('google_maps_link');
        });
    }

    public function down()
    {
        Schema::table('hotel_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
