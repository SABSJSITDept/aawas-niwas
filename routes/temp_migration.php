<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Route::get('/run-migration-temp', function () {
    if (!Schema::hasColumn('hotel_details', 'additional_contacts')) {
        Schema::table('hotel_details', function (Blueprint $table) {
            $table->json('additional_contacts')->nullable()->after('contact_number');
        });
        return 'Migration successful: additional_contacts column added.';
    }
    return 'Column already exists.';
});

