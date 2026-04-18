<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('usuario', $request->usuario)
            ->where('activo', true)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['usuario' => 'Credenciales incorrectas']);
        }

        Session::put('usuario_id', $usuario->id);
        Session::put('usuario_nombre', $usuario->nombre);
        Session::put('usuario_rol', $usuario->rol);

        // Redirige según rol
        return match($usuario->rol) {
            'administrador' => redirect()->route('admin.dashboard'),
            'cajero'        => redirect()->route('kiosco.index'),
            'cocina'        => redirect()->route('cocina.index'),
            default         => back()->withErrors(['usuario' => 'Rol no válido'])
        };
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}