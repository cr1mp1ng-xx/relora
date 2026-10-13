<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void{
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->unsignedBigInteger('harga');
            $table->string('area');
            $table->decimal('jarak', 4, 1);
            $table->text('deskripsi')->nullable();
            $table->string('nego')->default('nego');
            $table->json('foto')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('produks');
    }
};