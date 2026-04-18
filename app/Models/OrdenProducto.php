<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrdenProducto extends Model {
    protected $fillable = ['orden_id', 'producto_id', 'cantidad', 'precio_unitario', 'adicionales'];
    protected $casts = ['adicionales' => 'array'];
    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}