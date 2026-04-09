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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            
            // Informasi pendaftar (opsional, jika ingin menyimpan data saat pendaftaran)
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('faculty')->nullable();
            
            // Status kehadiran
            $table->boolean('attended')->default(false);
            $table->timestamp('attended_at')->nullable();
            
            // Status pendaftaran
            $table->enum('status', ['registered', 'cancelled', 'waiting'])->default('registered');
            
            // Waktu pendaftaran
            $table->timestamp('registration_date')->useCurrent();
            
            $table->timestamps();

            // Unique constraint untuk mencegah double registration
            $table->unique(['user_id', 'event_id']);
            
            // Index untuk query lebih cepat
            $table->index(['event_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};