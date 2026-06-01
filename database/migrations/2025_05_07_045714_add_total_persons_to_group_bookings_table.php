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
        Schema::table('group_bookings', function (Blueprint $table) {
            $table->integer('total_persons')->nullable()->after('total_members');
        });
    }
    
    public function down()
    {
        Schema::table('group_bookings', function (Blueprint $table) {
            $table->dropColumn('total_persons');
        });
    }
    
};
