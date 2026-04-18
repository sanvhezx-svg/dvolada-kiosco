<?php
namespace app\models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable {
    protected $table = 'usuarios';
    protected $fillable = [
        'nombre', 'foto', 'rol', 'usuario', 'password', 'activo'
    ];
    protected $hidden = ['password'];

    public function logs() {
        return $this->hasMany(Log::class);
    }
}