<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Adicional extends Model {
    protected $fillable = ['producto_id', 'nombre', 'etapa', 'precio', 'activo'];
    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}