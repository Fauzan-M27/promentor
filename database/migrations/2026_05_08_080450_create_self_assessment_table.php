<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('self_assessment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            // A. Kompetensi Akademik
            $table->integer('q1_kemampuan_akademik')->nullable();       // 1-4
            $table->integer('q2_kemampuan_menjelaskan')->nullable();     // 1-4
            // B. Kemampuan Interpersonal
            $table->integer('q3_komunikasi_empati')->nullable();         // 1-4
            $table->integer('q4_ketersediaan_waktu')->nullable();        // 1-4
            // C. Kepemimpinan & Motivasi
            $table->integer('q5_pengalaman_kepemimpinan')->nullable();   // 1-4
            $table->integer('q6_motivasi')->nullable();                  // 1-4
            // D. Refleksi Diri
            $table->text('kelebihan')->nullable();
            $table->text('kelemahan')->nullable();
            $table->text('rencana_jika_kesulitan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('self_assessment');
    }
};
