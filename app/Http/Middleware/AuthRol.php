<?php
namespace App\Http\Middleware;
use Closure;

  use Illuminate\Http\requirest;

  class authrol
  {
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!session('usuario_id')) {
            return redirect()->route('login');
        }

        if (!in_array(session('usuario_rol'), $roles)) {
            abort(403, 'No tienes permiso para acceder aquí');
        }

        return $next($request);
    }
  }