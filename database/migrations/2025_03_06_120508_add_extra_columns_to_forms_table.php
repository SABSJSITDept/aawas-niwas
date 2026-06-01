<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // These columns already exist in forms table creation migration
        // No need to add them again
    }

    public function down()
    {
        // No columns to drop since none were added
    }
};
