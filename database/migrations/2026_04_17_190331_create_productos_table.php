<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('foto')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('puntos_vip')->default(0);
            $table->boolean('disponible')->default(true);
            $table->boolean('activo')->default(true);
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('productos');
    }
};