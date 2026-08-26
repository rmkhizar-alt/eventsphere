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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('category', ['Technical', 'Cultural', 'Sports', 'Workshops', 'Annual Day', 'Competitions']);
            $table->string('venue');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('event_date');
            $table->string('banner_path')->nullable();
            $table->string('rulebook_path')->nullable();
            $table->integer('total_seats');
            $table->integer('seats_booked')->default(0);
            $table->boolean('waitlist_enabled')->default(false);
            $table->decimal('certificate_fee', 8, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed']);
            $table->string('cancellation_reason')->nullable();
            $table->datetime('registration_cutoff')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};