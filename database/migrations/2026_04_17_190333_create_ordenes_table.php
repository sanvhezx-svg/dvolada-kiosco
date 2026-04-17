<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden')->unique();
            $table->foreignId('cliente_vip_id')->nullable()->constrained('clientes_vip');
            $table->foreignId('mesa_id')->nullable()->constrained('mesas');
            $table->enum('destino', ['salon', 'llevar']);
            $table->enum('estado', ['recibido', 'preparando', 'listo', 'entregado', 'cancelado'])->default('recibido');
            $table->enum('metodo_pago', ['terminal', 'qr', 'nfc'])->nullable();
            $table->decimal('total', 10, 2);
            $table->integer('puntos_ganados')->default(0);
            $table->string('referencia_pago')->nullable();
            $table->timestamp('pagado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ordenes');
    }
};