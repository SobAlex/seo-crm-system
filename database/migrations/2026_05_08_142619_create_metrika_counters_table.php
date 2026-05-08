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
        Schema::create('metrika_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->string('counter_id');
            $table->text('token')->nullable(); // encrypted on model
            $table->timestamp('token_expires_at')->nullable();
            $table->json('goals')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->string('sync_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrika_counters');
    }
};
