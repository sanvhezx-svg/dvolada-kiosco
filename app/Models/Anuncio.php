<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model {
    protected $fillable = ['titulo', 'texto', 'imagen', 'duracion', 'fecha_inicio', 'fecha_fin', 'activo'];
}