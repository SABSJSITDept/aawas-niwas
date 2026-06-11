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
        Schema::create('dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->string('form_type'); // 'vip', 'family', 'group'
            $table->string('label');
            $table->string('name'); // slug for the field name
            $table->string('type'); // 'text', 'select', 'checkbox', 'radio'
            $table->json('options')->nullable(); // For select/radio options
            $table->boolean('is_required')->default(false);
            $table->boolean('status')->default(true); // Active or inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_fields');
    }
};
