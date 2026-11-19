<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percakapan_id')->constrained()->cascadeOnDelete();
            $table->enum('pengirim', ['pembeli', 'penjual']);          
            $table->enum('tipe', ['teks', 'ringkasan'])->default('teks');
            $table->text('isi')->nullable();                            
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};