<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('created_at', 'desc')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create() { return redirect()->route('admin.usuarios.index'); }
    public function show(Usuario $usuario) { return redirect()->route('admin.usuarios.index'); }
    public function edit(Usuario $usuario) { return redirect()->route('admin.usuarios.index'); }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'usuario'  => 'required|string|unique:usuarios',
            'password' => 'required|string|min:6',
            'rol'      => 'required|in:administrador,cajero,cocina',
        ]);

        Usuario::create([
            'nombre'   => $request->nombre,
            'usuario'  => $request->usuario,
            'password' => Hash::make($request->password),
            'rol'      => $request->rol,
            'activo'   => true,
        ]);

        return back()->with('success', 'Usuario creado correctamente');
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'rol'    => 'required|in:administrador,cajero,cocina',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'rol'    => $request->rol,
            'activo' => $request->has('activo'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);
        return back()->with('success', 'Usuario actualizado');
    }

    public function destroy(Usuario $usuario)
    {
        if ($usuario->id === session('usuario_id')) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propio usuario']);
        }
        $usuario->delete();
        return back()->with('success', 'Usuario eliminado');
    }
}