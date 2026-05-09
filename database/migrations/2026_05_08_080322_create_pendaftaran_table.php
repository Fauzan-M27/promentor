<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pengalaman_organisasi')->nullable();
            $table->text('motivasi');
            $table->enum('ketersediaan_waktu', ['1-2', '2-3', '3-4', '4+'])->default('2-3');
            $table->enum('status', ['pending', 'review', 'diterima', 'ditolak'])->default('pending');
            $table->integer('skor_total')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->string('tahun_ajaran')->default('2026');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
