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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->integer('size');
            $table->string('mime_type');
            $table->string('entity_type'); // project, website, task, task_template, comment
            $table->unsignedBigInteger('entity_id');
            $table->boolean('is_template')->default(false);
            $table->foreignId('uploaded_by_id')->constrained('users');
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
