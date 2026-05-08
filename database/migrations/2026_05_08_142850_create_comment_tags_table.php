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
        Schema::create('comment_tags', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('color')->default('#cccccc');
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // предзаполнение системных меток
        DB::table('comment_tags')->insert([
            ['title' => 'Для отчета', 'color' => '#27ae60', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Важно', 'color' => '#e74c3c', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Согласовано', 'color' => '#2980b9', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Требуется ответ', 'color' => '#f39c12', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_tags');
    }
};
