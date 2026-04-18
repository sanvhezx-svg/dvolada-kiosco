<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteVip extends Model {
    protected $table = 'clientes_vip';
    protected $fillable = [
        'nombre', 'apodo', 'foto', 'telefono', 'correo',
        'numero_tarjeta', 'nip', 'puntos', 'nivel',
        'fecha_nacimiento', 'activo'
    ];
    protected $hidden = ['nip'];

    public function ordenes() {
        return $this->hasMany(Orden::class);
    }

    public function esCumpleanios() {
        return $this->fecha_nacimiento &&
            now()->format('m-d') === date('m-d', strtotime($this->fecha_nacimiento));
    }
}