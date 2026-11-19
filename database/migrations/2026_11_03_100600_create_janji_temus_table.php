<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('janji_temus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percakapan_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('lokasi_temu');
            $table->dateTime('waktu_temu');
            $table->unsignedBigInteger('harga_disepakati');
            $table->enum('status', ['menunggu_konfirmasi', 'disepakati', 'selesai', 'batal'])->default('menunggu_konfirmasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('janji_temus');
    }
};