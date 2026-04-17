<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clientes_vip', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apodo')->nullable();
            $table->string('foto')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('numero_tarjeta')->unique();
            $table->string('nip');
            $table->integer('puntos')->default(0);
            $table->enum('nivel', ['Silver', 'Gold', 'Platinum'])->default('Silver');
            $table->date('fecha_nacimiento')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clientes_vip');
    }
};