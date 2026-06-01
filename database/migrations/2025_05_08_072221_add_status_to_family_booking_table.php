<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFamilyBookingTable extends Migration
{
    public function up()
    {
        // status column already exists in family_booking table creation
    }

    public function down()
    {
        Schema::table('family_booking', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

