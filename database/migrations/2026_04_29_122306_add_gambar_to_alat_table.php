<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        // PASTIKAN ini 'alat', bukan 'alats'
        Schema::table('alat', function (Blueprint $table) {
            $table->string('gambar')->nullable()->after('nama_alat');
        });
    }

    public function down(): void
    {
        // PASTIKAN ini 'alat', bukan 'alats'
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};
