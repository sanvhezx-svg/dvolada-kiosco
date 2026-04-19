<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create() { return redirect()->route('admin.categorias.index'); }
    public function show(Categoria $categoria) { return redirect()->route('admin.categorias.index'); }
    public function edit(Categoria $categoria) { return redirect()->route('admin.categorias.index'); }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100']);
        Categoria::create([
            'nombre' => $request->nombre,
            'icono'  => $request->icono,
            'orden'  => $request->orden ?? 0,
            'activo' => true,
        ]);
        return back()->with('success', 'Categoría creada correctamente');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate(['nombre' => 'required|string|max:100']);
        $categoria->update([
            'nombre' => $request->nombre,
            'icono'  => $request->icono,
            'orden'  => $request->orden ?? 0,
            'activo' => $request->has('activo'),
        ]);
        return back()->with('success', 'Categoría actualizada');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return back()->with('success', 'Categoría eliminada');
    }
}