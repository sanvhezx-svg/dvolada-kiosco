@extends('admin.layout')

@section('title', 'Usuarios')
@section('page_title', 'Usuarios del Sistema')
@section('breadcrumb', 'Inicio / Usuarios')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; }
    .btn-primary { background: #FFB400; color: #000; }
    .btn-primary:hover { background: #ffc933; }
    .btn-danger { background: #1a0000; color: #ff6b6b; border: 1px solid #500; }
    .btn-sm { padding: 6px 14px; font-size: 0.8rem; }
    .alert-success { background: #001a00; border: 1px solid #050; color: #4caf50; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; }

    .usuarios-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .usuario-card { background: #111; border: 1px solid #1a1a1a; border-radius: 16px; padding: 24px; transition: border-color 0.2s; }
    .usuario-card:hover { border-color: #333; }
    .usuario-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .usuario-avatar { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: 700; flex-shrink: 0; }
    .avatar-admin { background: #1a0a00; color: #FFB400; border: 2px solid #554000; }
    .avatar-cajero { background: #001a1a; color: #00aaff; border: 2px solid #005566; }
    .avatar-cocina { background: #001a00; color: #4caf50; border: 2px solid #055; }
    .usuario-nombre { font-weight: 700; font-size: 1rem; }
    .usuario-user { color: #555; font-size: 0.85rem; font-family: monospace; }

    .rol-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: 16px; }
    .rol-administrador { background: #1a0a00; color: #FFB400; border: 1px solid #554000; }
    .rol-cajero { background: #001a1a; color: #00aaff; border: 1px solid #005566; }
    .rol-cocina { background: #001a00; color: #4caf50; border: 1px solid #055; }

    .usuario-actions { display: flex; gap: 8px; }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.show { display: flex; }
    .modal { background: #111; border: 1px solid #222; border-radius: 20px; padding: 32px; width: 90%; max-width: 480px; }
    .modal h3 { color: #FFB400; margin-bottom: 24px; }
    .modal-close { float: right; background: none; border: none; color: #666; font-size: 1.5rem; cursor: pointer; }
    .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    label { color: #666; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
    input, select { background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 10px; padding: 10px 14px; color: #fff; font-size: 0.9rem; outline: none; width: 100%; }
    input:focus, select:focus { border-color: #FFB400; }
    .badge-active { background: #001a00; color: #4caf50; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; }
    .badge-inactive { background: #1a1a1a; color: #555; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; }
</style>

@if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
@endif

<div style="margin-bottom:24px;display:flex;justify-content:space-between;align-items:center">
    <div style="color:#555">{{ $usuarios->count() }} usuarios registrados</div>
    <button class="btn btn-primary" onclick="document.getElementById('modalNuevo').classList.add('show')">
        👤 Nuevo Usuario
    </button>
</div>

<div class="usuarios-grid">
    @foreach($usuarios as $usuario)
    @php
        $avatarClass = 'avatar-' . $usuario->rol;
        $inicial = strtoupper(substr($usuario->nombre, 0, 1));
    @endphp
    <div class="usuario-card">
        <div class="usuario-header">
            <div class="usuario-avatar {{ $avatarClass }}">{{ $inicial }}</div>
            <div>
                <div class="usuario-user">{{ $usuario->usuario }}</div>
                <div class="usuario-user">{{ $usuario->usuario }}</div>
            </div>
        </div>

        <span class="rol-badge rol-{{ $usuario->rol }}">
            {{ $usuario->rol === 'administrador' ? '👑 Admin' : ($usuario->rol === 'cajero' ? '💳 Cajero' : '🍳 Cocina') }}
        </span>

        <div style="margin-bottom:16px">
            <span class="{{ $usuario->activo ? 'badge-active' : 'badge-inactive' }}">
                {{ $usuario->activo ? '✅ Activo' : '❌ Inactivo' }}
            </span>
        </div>

        <div class="usuario-actions">
            <button class="btn btn-primary btn-sm" onclick="editarUsuario(
                {{ $usuario->id }},
                '{{ addslashes($usuario->nombre) }}',
                '{{ $usuario->rol }}',
                {{ $usuario->activo ? 'true' : 'false' }}
            )">✏️ Editar</button>

            @if($usuario->id !== session('usuario_id'))
            <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}"
                  onsubmit="return confirm('¿Eliminar este usuario?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit">🗑️</button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- MODAL NUEVO --}}
<div class="modal-overlay" id="modalNuevo">
    <div class="modal">
        <button class="modal-close" onclick="document.getElementById('modalNuevo').classList.remove('show')">×</button>
        <h3>👤 Nuevo Usuario</h3>
        <form method="POST" action="{{ route('admin.usuarios.store') }}">
            @csrf
            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="rol" required>
                    <option value="administrador">👑 Administrador</option>
                    <option value="cajero">💳 Cajero</option>
                    <option value="cocina">🍳 Cocina</option>
                </select>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px">
                <button type="button" class="btn" style="background:#1a1a1a;color:#fff"
                    onclick="document.getElementById('modalNuevo').classList.remove('show')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal-overlay" id="modalEditar">
    <div class="modal">
        <button class="modal-close" onclick="document.getElementById('modalEditar').classList.remove('show')">×</button>
        <h3>✏️ Editar Usuario</h3>
        <form method="POST" id="formEditar">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre" id="editNombre" required>
            </div>
            <div class="form-group">
                <label>Nueva contraseña (opcional)</label>
                <input type="password" name="password" minlength="6" placeholder="Dejar vacío para no cambiar">
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="rol" id="editRol" required>
                    <option value="administrador">👑 Administrador</option>
                    <option value="cajero">💳 Cajero</option>
                    <option value="cocina">🍳 Cocina</option>
                </select>
            </div>
            <div class="form-group">
                <label style="flex-direction:row;gap:8px;align-items:center">
                    <input type="checkbox" name="activo" id="editActivo" style="width:auto">
                    Usuario activo
                </label>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px">
                <button type="button" class="btn" style="background:#1a1a1a;color:#fff"
                    onclick="document.getElementById('modalEditar').classList.remove('show')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editarUsuario(id, nombre, rol, activo) {
    document.getElementById('formEditar').action = '/admin/usuarios/' + id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editRol').value = rol;
    document.getElementById('editActivo').checked = activo;
    document.getElementById('modalEditar').classList.add('show');
}
</script>
@endsection