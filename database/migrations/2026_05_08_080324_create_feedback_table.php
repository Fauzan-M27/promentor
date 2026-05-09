<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pasangan_id')->constrained('pasangan')->onDelete('cascade');
            // Penilaian bintang per aspek (1-5)
            $table->integer('bintang_ketersediaan')->default(5);
            $table->integer('bintang_penjelasan')->default(5);
            $table->integer('bintang_empati')->default(5);
            $table->integer('bintang_komitmen')->default(5);
            // Rata-rata otomatis
            $table->decimal('rata_rata', 3, 2)->nullable();
            // Komentar
            $table->text('hal_positif')->nullable();
            $table->text('saran')->nullable();
            $table->enum('rekomendasi', ['ya', 'mungkin', 'tidak'])->default('ya');
            $table->string('tahun_ajaran')->default('2026');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
