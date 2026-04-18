<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Log extends Model {
    protected $fillable = ['usuario_id', 'accion', 'detalle', 'dispositivo', 'ip'];
    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }
}