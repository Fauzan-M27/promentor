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
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('motivasi');
            $table->string('motivation_letter')->after('pengalaman_organisasi');
            $table->string('khs')->after('motivation_letter');
            $table->string('sertifikat_organisasi')->nullable()->after('khs');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->text('motivasi')->after('pengalaman_organisasi');
            $table->dropColumn(['motivation_letter', 'khs', 'sertifikat_organisasi']);
        });
    }
};
