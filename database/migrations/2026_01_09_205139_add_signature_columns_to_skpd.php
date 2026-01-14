<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Tambah kolom signature di tabel users (untuk Mentor)
        Schema::table('skpd', function (Blueprint $table) {
            $table->string('signature')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('signature');
        });

        Schema::table('skpds', function (Blueprint $table) {
            $table->dropColumn('ttd_kepala');
        });
    }
};