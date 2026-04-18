<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model {
    protected $fillable = [
        'numero_orden', 'cliente_vip_id', 'mesa_id',
        'destino', 'estado', 'metodo_pago', 'total',
        'puntos_ganados', 'referencia_pago', 'pagado_at'
    ];

    public function productos() {
        return $this->hasMany(OrdenProducto::class);
    }

    public function clienteVip() {
        return $this->belongsTo(ClienteVip::class);
    }

    public function mesa() {
        return $this->belongsTo(Mesa::class);
    }
}