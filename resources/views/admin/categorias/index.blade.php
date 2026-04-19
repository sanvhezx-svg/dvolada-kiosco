@extends('admin.layout')

@section('title', 'Categorías')
@section('page_title', 'Categorías')
@section('breadcrumb', 'Inicio / Categorías')

@section('content')
<style>
    .btn {
        padding: 10px 20px; border-radius: 10px; border: none;
        cursor: pointer; font-size: 0.85rem; font-weight: 600;
        transition: all 0.2s;
    }
    .btn-primary { background: #FFB400; color: #000; }
    .btn-primary:hover { background: #ffc933; }
    .btn-danger { background: #1a0000; color: #ff6b6b; border: 1px solid #500; }
    .btn-sm { padding: 6px 14px; font-size: 0.8rem; }

    .alert-success {
        background: #001a00; border: 1px solid #050;
        color: #4caf50; padding: 12px 16px;
        border-radius: 10px; margin-bottom: 20px;
    }

    .card {
        background: #111; border: 1px solid #1a1a1a;
        border-radius: 16px; padding: 24px; margin-bottom: 24px;
    }
    .card h3 { margin-bottom: 20px; font-size: 1rem; color: #FFB400; }

    .form-row { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
    .form-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 150px; }
    label { color: #666; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
    input, select {
        background: #1a1a1a; border: 1px solid #2a2a2a;
        border-radius: 10px; padding: 10px 14px;
        color: #fff; font-size: 0.9rem; outline: none;
    }
    input:focus { border-color: #FFB400; }

    table { width: 100%; border-collapse: collapse; }
    th { color: #444; font-size: 0.75rem; text-transform: uppercase;
         letter-spacing: 1px; padding: 12px 16px; text-align: left;
         border-bottom: 1px solid #1a1a1a; }
    td { padding: 14px 16px; border-bottom: 1px solid #111;
         font-size: 0.9rem; vertical-align: middle; }
    tr:hover td { background: #111; }

    .badge {
        padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
    }
    .badge-active { background: #001a00; color: #4caf50; }
    .badge-inactive { background: #1a1a1a; color: #555; }

    .icon-preview { font-size: 1.5rem; }
</style>

@if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
@endif

{{-- FORMULARIO NUEVA CATEGORÍA --}}
<div class="card">
    <h3>➕ Nueva Categoría</h3>
    <form method="POST" action="{{ route('admin.categorias.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Icono (emoji)</label>
                <input type="text" name="icono" placeholder="🍕" maxlength="5">
            </div>
            <div class="form-group" style="flex:3">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Ej: Bebidas frías" required>
            </div>
            <div class="form-group">
                <label>Orden</label>
                <input type="number" name="orden" placeholder="0" min="0">
            </div>
            <div class="form-group" style="flex:0">
                <label>&nbsp;</label>
                <button class="btn btn-primary" type="submit">Guardar</button>
            </div>
        </div>
    </form>
</div>

{{-- LISTA DE CATEGORÍAS --}}
<div class="card">
    <h3>📁 Categorías ({{ $categorias->count() }})</h3>
    @if($categorias->isEmpty())
        <p style="color:#444;text-align:center;padding:40px">No hay categorías aún</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Icono</th>
                <th>Nombre</th>
                <th>Orden</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categorias as $cat)
            <tr>
                <td><span class="icon-preview">{{ $cat->icono ?? '📁' }}</span></td>
                <td>{{ $cat->nombre }}</td>
                <td>{{ $cat->orden }}</td>
                <td>
                    <span class="badge {{ $cat->activo ? 'badge-active' : 'badge-inactive' }}">
                        {{ $cat->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td style="display:flex;gap:8px">
                    {{-- Editar --}}
                    <form method="POST" action="{{ route('admin.categorias.update', $cat) }}" style="display:flex;gap:8px">
                        @csrf @method('PUT')
                        <input type="text" name="icono" value="{{ $cat->icono }}" style="width:60px">
                        <input type="text" name="nombre" value="{{ $cat->nombre }}" style="width:160px">
                        <input type="number" name="orden" value="{{ $cat->orden }}" style="width:60px">
                        <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
                    </form>
                    {{-- Eliminar --}}
                    <form method="POST" action="{{ route('admin.categorias.destroy', $cat) }}"
                          onsubmit="return confirm('¿Eliminar esta categoría?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection