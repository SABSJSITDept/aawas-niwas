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
    Schema::table('group_members', function (Blueprint $table) {
        $table->string('status')->default('pending')->after('aadhar_number'); // ya jis column ke baad chahiye uske according
    });
}

public function down()
{
    Schema::table('group_members', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
