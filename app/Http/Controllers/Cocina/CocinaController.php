<?php

namespace App\Http\Controllers\Cocina;

use App\Http\Controllers\Controller;
use App\Models\Orden;

class CocinaController extends Controller
{
    public function index()
    {
        $ordenes = Orden::with('productos')
            ->whereIn('estado', ['recibido', 'preparando'])
            ->orderBy('created_at', 'asc')
            ->get();
        return view('cocina.index', compact('ordenes'));
    }

    public function actualizar(Orden $orden, $estado)
    {
        $orden->update(['estado' => $estado]);
        return response()->json(['ok' => true]);
    }
}