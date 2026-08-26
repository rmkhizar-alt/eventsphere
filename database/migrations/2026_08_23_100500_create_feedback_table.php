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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating');
            $table->tinyInteger('venue_rating')->nullable();
            $table->tinyInteger('coordination_rating')->nullable();
            $table->tinyInteger('technical_rating')->nullable();
            $table->tinyInteger('hospitality_rating')->nullable();
            $table->text('comments')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};