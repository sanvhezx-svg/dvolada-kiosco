<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('anuncios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('texto')->nullable();
            $table->string('imagen')->nullable();
            $table->integer('duracion')->default(5);
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('anuncios');
    }
};