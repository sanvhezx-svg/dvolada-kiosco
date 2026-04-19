<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->orderBy('created_at', 'desc')->get();
        $categorias = Categoria::where('activo', true)->orderBy('orden')->get();
        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function create() { return redirect()->route('admin.productos.index'); }
    public function show(Producto $producto) { return redirect()->route('admin.productos.index'); }
    public function edit(Producto $producto) { return redirect()->route('admin.productos.index'); }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'precio'       => 'required|numeric|min:0',
            'foto'         => 'nullable|image|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('productos', 'public');
        }

        Producto::create([
            'nombre'       => $request->nombre,
            'descripcion'  => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'precio'       => $request->precio,
            'foto'         => $foto,
            'puntos_vip'   => $request->puntos_vip ?? 0,
            'disponible'   => true,
            'activo'       => true,
        ]);

        return back()->with('success', 'Producto creado correctamente');
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
        ]);

        $foto = $producto->foto;
        if ($request->hasFile('foto')) {
            if ($foto) Storage::disk('public')->delete($foto);
            $foto = $request->file('foto')->store('productos', 'public');
        }

        $producto->update([
            'nombre'       => $request->nombre,
            'descripcion'  => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'precio'       => $request->precio,
            'foto'         => $foto,
            'puntos_vip'   => $request->puntos_vip ?? 0,
            'activo'       => $request->has('activo'),
        ]);

        return back()->with('success', 'Producto actualizado');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->foto) Storage::disk('public')->delete($producto->foto);
        $producto->delete();
        return back()->with('success', 'Producto eliminado');
    }
}