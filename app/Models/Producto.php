<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'categoria_id', 'nombre', 'descripcion', 'foto',
        'precio', 'puntos_vip', 'disponible', 'activo',
        'hora_inicio', 'hora_fin'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function adicionales()
    {
        return $this->hasMany(Adicional::class);
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }
}