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
    // is_veer_parivar and veer_relation columns already exist in family_booking table creation
}

public function down()
{
    Schema::table('family_booking', function (Blueprint $table) {
        $table->dropColumn('is_veer_parivar');
        $table->dropColumn('veer_relation');
    });
}

};
