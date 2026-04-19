@extends('admin.layout')

@section('title', 'Clientes VIP')
@section('page_title', 'Clientes VIP')
@section('breadcrumb', 'Inicio / Clientes VIP')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; }
    .btn-primary { background: #FFB400; color: #000; }
    .btn-primary:hover { background: #ffc933; }
    .btn-danger { background: #1a0000; color: #ff6b6b; border: 1px solid #500; }
    .btn-sm { padding: 6px 14px; font-size: 0.8rem; }
    .alert-success { background: #001a00; border: 1px solid #050; color: #4caf50; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; }

    .vips-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
    .vip-card { background: #111; border: 1px solid #1a1a1a; border-radius: 16px; padding: 24px; transition: border-color 0.2s; }
    .vip-card:hover { border-color: #333; }
    .vip-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .vip-avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; overflow: hidden; flex-shrink: 0; }
    .vip-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .vip-nombre { font-weight: 700; font-size: 1rem; }
    .vip-apodo { color: #555; font-size: 0.85rem; }
    .vip-tarjeta { color: #FFB400; font-size: 0.8rem; font-family: monospace; }

    .nivel-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: 12px; }
    .nivel-Silver { background: #1a1a2e; color: #aaa; border: 1px solid #333; }
    .nivel-Gold { background: #1a1500; color: #FFB400; border: 1px solid #554000; }
    .nivel-Platinum { background: #001a1a; color: #00d4ff; border: 1px solid #005566; }

    .puntos-bar { background: #1a1a1a; border-radius: 20px; height: 6px; margin: 8px 0 4px; overflow: hidden; }
    .puntos-fill { background: #FFB400; height: 100%; border-radius: 20px; transition: width 0.3s; }
    .puntos-text { color: #555; font-size: 0.8rem; display: flex; justify-content: space-between; }

    .vip-info { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin: 12px 0; }
    .vip-info-item { background: #1a1a1a; border-radius: 8px; padding: 8px 12px; }
    .vip-info-label { color: #444; font-size: 0.7rem; text-transform: uppercase; }
    .vip-info-value { color: #fff; font-size: 0.85rem; margin-top: 2px; }

    .vip-actions { display: flex; gap: 8px; margin-top: 16px; }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.show { display: flex; }
    .modal { background: #111; border: 1px solid #222; border-radius: 20px; padding: 32px; width: 90%; max-width: 560px; max-height: 90vh; overflow-y: auto; }
    .modal h3 { color: #FFB400; margin-bottom: 24px; }
    .modal-close { float: right; background: none; border: none; color: #666; font-size: 1.5rem; cursor: pointer; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }
    label { color: #666; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
    input, select { background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 10px; padding: 10px 14px; color: #fff; font-size: 0.9rem; outline: none; width: 100%; }
    input:focus { border-color: #FFB400; }

    .cumple-badge { background: #1a0a00; color: #ff9800; border: 1px solid #663300; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; }
</style>

@if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
@endif

<div style="margin-bottom:24px;display:flex;justify-content:space-between;align-items:center">
    <div style="color:#555">{{ $clientes->count() }} clientes registrados</div>
    <button class="btn btn-primary" onclick="document.getElementById('modalNuevo').classList.add('show')">
        ⭐ Nuevo Cliente VIP
    </button>
</div>

<div class="vips-grid">
    @forelse($clientes as $cliente)
    @php
        $nextNivel = $cliente->nivel === 'Silver' ? 200 : ($cliente->nivel === 'Gold' ? 500 : 500);
        $progress = $cliente->nivel === 'Platinum' ? 100 : min(100, ($cliente->puntos / $nextNivel) * 100);
        $esCumple = $cliente->fecha_nacimiento && now()->format('m-d') === \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('m-d');
    @endphp
    <div class="vip-card">
        <div class="vip-header">
            <div class="vip-avatar">
                @if($cliente->foto)
                    <img src="{{ asset('storage/'.$cliente->foto) }}" alt="">
                @else
                    👤
                @endif
            </div>
            <div>
                <div class="vip-nombre">
                    {{ $cliente->nombre }}
                    @if($esCumple) <span class="cumple-badge">🎂 Cumpleaños</span> @endif
                </div>
                <div class="vip-apodo">{{ $cliente->apodo ? '"'.$cliente->apodo.'"' : '' }}</div>
                <div class="vip-tarjeta">🃏 {{ $cliente->numero_tarjeta }}</div>
            </div>
        </div>

        <span class="nivel-badge nivel-{{ $cliente->nivel }}">
            {{ $cliente->nivel === 'Silver' ? '🥈' : ($cliente->nivel === 'Gold' ? '🥇' : '💎') }}
            {{ $cliente->nivel }}
        </span>

        <div class="puntos-bar">
            <div class="puntos-fill" style="width:{{ $progress }}%"></div>
        </div>
        <div class="puntos-text">
            <span>{{ $cliente->puntos }} puntos</span>
            <span>@if($cliente->nivel !== 'Platinum') {{ $nextNivel - $cliente->puntos }} para {{ $cliente->nivel === 'Silver' ? 'Gold' : 'Platinum' }} @else Nivel máximo @endif</span>
        </div>

        <div class="vip-info">
            <div class="vip-info-item">
                <div class="vip-info-label">Teléfono</div>
                <div class="vip-info-value">{{ $cliente->telefono ?? '—' }}</div>
            </div>
            <div class="vip-info-item">
                <div class="vip-info-label">Estado</div>
                <div class="vip-info-value">{{ $cliente->activo ? '✅ Activo' : '❌ Inactivo' }}</div>
            </div>
        </div>

        <div class="vip-actions">
            <button class="btn btn-primary btn-sm" onclick="editarVip(
                {{ $cliente->id }},
                '{{ addslashes($cliente->nombre) }}',
                '{{ addslashes($cliente->apodo) }}',
                '{{ $cliente->telefono }}',
                '{{ $cliente->correo }}',
                {{ $cliente->puntos }},
                {{ $cliente->activo ? 'true' : 'false' }},
                '{{ $cliente->fecha_nacimiento }}'
            )">✏️ Editar</button>
            <form method="POST" action="{{ route('admin.vips.destroy', $cliente) }}"
                  onsubmit="return confirm('¿Eliminar este cliente?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit">🗑️</button>
            </form>
        </div>
    </div>
    @empty
        <div style="color:#444;padding:40px;text-align:center;grid-column:1/-1">
            No hay clientes VIP aún
        </div>
    @endforelse
</div>

{{-- MODAL NUEVO --}}
<div class="modal-overlay" id="modalNuevo">
    <div class="modal">
        <button class="modal-close" onclick="document.getElementById('modalNuevo').classList.remove('show')">×</button>
        <h3>⭐ Nuevo Cliente VIP</h3>
        <form method="POST" action="{{ route('admin.vips.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Foto</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Apodo</label>
                    <input type="text" name="apodo" placeholder="Opcional">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono">
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="correo">
                </div>
                <div class="form-group">
                    <label>Número de tarjeta VIP</label>
                    <input type="text" name="numero_tarjeta" required placeholder="Ej: VIP-0001">
                </div>
                <div class="form-group">
                    <label>NIP (4 dígitos)</label>
                    <input type="password" name="nip" required maxlength="4">
                </div>
                <div class="form-group full">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento">
                </div>
            </div>
            <div style="margin-top:24px;display:flex;gap:12px;justify-content:flex-end">
                <button type="button" class="btn" style="background:#1a1a1a;color:#fff"
                    onclick="document.getElementById('modalNuevo').classList.remove('show')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Cliente VIP</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal-overlay" id="modalEditar">
    <div class="modal">
        <button class="modal-close" onclick="document.getElementById('modalEditar').classList.remove('show')">×</button>
        <h3>✏️ Editar Cliente VIP</h3>
        <form method="POST" id="formEditar" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group full">
                    <label>Nueva foto (opcional)</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="editNombre" required>
                </div>
                <div class="form-group">
                    <label>Apodo</label>
                    <input type="text" name="apodo" id="editApodo">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" id="editTelefono">
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="correo" id="editCorreo">
                </div>
                <div class="form-group">
                    <label>Puntos</label>
                    <input type="number" name="puntos" id="editPuntos" min="0">
                </div>
                <div class="form-group">
                    <label>Nuevo NIP (opcional)</label>
                    <input type="password" name="nip" maxlength="4">
                </div>
                <div class="form-group full">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="editFecha">
                </div>
                <div class="form-group full">
                    <label style="flex-direction:row;gap:8px;align-items:center">
                        <input type="checkbox" name="activo" id="editActivo" style="width:auto">
                        Cliente activo
                    </label>
                </div>
            </div>
            <div style="margin-top:24px;display:flex;gap:12px;justify-content:flex-end">
                <button type="button" class="btn" style="background:#1a1a1a;color:#fff"
                    onclick="document.getElementById('modalEditar').classList.remove('show')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function editarVip(id, nombre, apodo, telefono, correo, puntos, activo, fecha) {
    document.getElementById('formEditar').action = '/admin/vips/' + id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editApodo').value = apodo;
    document.getElementById('editTelefono').value = telefono;
    document.getElementById('editCorreo').value = correo;
    document.getElementById('editPuntos').value = puntos;
    document.getElementById('editActivo').checked = activo;
    document.getElementById('editFecha').value = fecha;
    document.getElementById('modalEditar').classList.add('show');
}
</script>
@endsection