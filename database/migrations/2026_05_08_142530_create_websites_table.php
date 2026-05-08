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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('website_type_id')->constrained();
            $table->foreignId('previous_website_id')->nullable()->constrained('websites')->nullOnDelete();
            $table->string('cms')->nullable();
            $table->string('region')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
