<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->integer('kompetensi_akademik')->default(0); // 0-25
            $table->integer('kemampuan_komunikasi')->default(0); // 0-25
            $table->integer('kepemimpinan')->default(0);         // 0-25
            $table->integer('integritas_komitmen')->default(0);  // 0-25
            $table->integer('skor_total')->storedAs('kompetensi_akademik + kemampuan_komunikasi + kepemimpinan + integritas_komitmen');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
