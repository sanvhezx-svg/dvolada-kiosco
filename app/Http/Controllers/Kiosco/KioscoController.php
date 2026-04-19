<?php

namespace App\Http\Controllers\Kiosco;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ClienteVip;
use App\Models\Orden;
use Illuminate\Http\Request;

class KioscoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('activo', true)->orderBy('orden')->get();
        $productos = Producto::where('activo', true)
            ->where('disponible', true)
            ->with('categoria')
            ->get();
        return view('kiosco.index', compact('categorias', 'productos'));
    }

    public function buscarVip(Request $request)
    {
        $cliente = ClienteVip::where('numero_tarjeta', $request->tarjeta)
            ->where('activo', true)
            ->first();

        if (!$cliente) {
            return response()->json(['error' => 'Tarjeta no encontrada'], 404);
        }

        $esCumple = $cliente->fecha_nacimiento &&
            now()->format('m-d') === \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('m-d');

        return response()->json([
            'id'       => $cliente->id,
            'nombre'   => $cliente->nombre,
            'apodo'    => $cliente->apodo,
            'puntos'   => $cliente->puntos,
            'nivel'    => $cliente->nivel,
            'cumple'   => $esCumple,
        ]);
    }
}