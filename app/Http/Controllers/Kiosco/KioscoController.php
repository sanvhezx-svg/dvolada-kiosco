<?php

namespace App\Http\Controllers\Kiosco;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ClienteVip;
use App\Models\Orden;
use App\Models\OrdenProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'id'     => $cliente->id,
            'nombre' => $cliente->nombre,
            'apodo'  => $cliente->apodo,
            'puntos' => $cliente->puntos,
            'nivel'  => $cliente->nivel,
            'cumple' => $esCumple,
        ]);
    }

    public function crearOrden(Request $request)
    {
        $request->validate([
            'destino'   => 'required|in:salon,llevar',
            'productos' => 'required|array|min:1',
            'total'     => 'required|numeric|min:0',
        ]);

        // Crear la orden
        $orden = Orden::create([
            'numero_orden'    => 'ORD-' . strtoupper(Str::random(6)),
            'cliente_vip_id'  => $request->cliente_vip_id,
            'destino'         => $request->destino,
            'estado'          => 'recibido',
            'metodo_pago'     => $request->metodo_pago ?? 'terminal',
            'total'           => $request->total,
            'puntos_ganados'  => intval($request->total / 10),
            'pagado_at'       => now(),
        ]);

        // Agregar productos
        foreach ($request->productos as $item) {
            OrdenProducto::create([
                'orden_id'       => $orden->id,
                'producto_id'    => $item['id'],
                'cantidad'       => $item['cantidad'],
                'precio_unitario'=> $item['precio'],
                'adicionales'    => null,
            ]);
        }

        // Actualizar puntos VIP
        if ($request->cliente_vip_id) {
            $cliente = ClienteVip::find($request->cliente_vip_id);
            if ($cliente) {
                $nuevoPuntos = $cliente->puntos + intval($request->total / 10);
                $nivel = $nuevoPuntos >= 500 ? 'Platinum' : ($nuevoPuntos >= 200 ? 'Gold' : 'Silver');
                $cliente->update(['puntos' => $nuevoPuntos, 'nivel' => $nivel]);
            }
        }

        return response()->json([
            'ok'           => true,
            'numero_orden' => $orden->numero_orden,
            'orden_id'     => $orden->id,
        ]);
    }
}