<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();                       
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->nullOnDelete();
            $table->string('judul', 100);
            $table->unsignedBigInteger('harga');
            $table->enum('kondisi', ['baru', 'bekas'])->default('bekas');
            $table->text('deskripsi')->nullable();
            $table->string('area', 100);                                                           
            $table->decimal('jarak_km', 4, 1);                                                     
            $table->enum('nego', ['pas', 'nego'])->default('nego');
            $table->enum('status', ['draf', 'tersedia', 'dipesan', 'terjual', 'arsip'])->default('tersedia');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};