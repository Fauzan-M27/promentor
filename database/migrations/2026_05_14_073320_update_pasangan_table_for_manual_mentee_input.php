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
        Schema::table('pasangan', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom mentee_id
            $table->dropForeign(['mentee_id']);
            $table->dropColumn('mentee_id');
            
            // Tambah kolom untuk input manual mentee
            $table->string('mentee_nama')->after('mentor_id');
            $table->string('mentee_nim')->after('mentee_nama');
            $table->string('mentee_no_telp')->nullable()->after('mentee_nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasangan', function (Blueprint $table) {
            // Kembalikan struktur lama
            $table->dropColumn(['mentee_nama', 'mentee_nim', 'mentee_no_telp']);
            $table->foreignId('mentee_id')->after('mentor_id')->constrained('users')->onDelete('cascade');
        });
    }
};
