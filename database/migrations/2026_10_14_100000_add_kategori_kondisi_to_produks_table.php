<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('produks', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('judul');
            $table->string('kondisi')->default('bekas')->after('kategori');
        });
    }

    public function down(): void {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'kondisi']);
        });
    }
};