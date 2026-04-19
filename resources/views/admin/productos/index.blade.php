@extends('admin.layout')

@section('title', 'Productos')
@section('page_title', 'Productos')
@section('breadcrumb', 'Inicio / Productos')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; }
    .btn-primary { background: #FFB400; color: #000; }
    .btn-primary:hover { background: #ffc933; }
    .btn-danger { background: #1a0000; color: #ff6b6b; border: 1px solid #500; }
    .btn-sm { padding: 6px 14px; font-size: 0.8rem; }
    .alert-success { background: #001a00; border: 1px solid #050; color: #4caf50; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; }
    .card { background: #111; border: 1px solid #1a1a1a; border-radius: 16px; padding: 24px; margin-bottom: 24px; }
    .card h3 { margin-bottom: 20px; font-size: 1rem; color: #FFB400; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }
    label { color: #666; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
    input, select, textarea { background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 10px; padding: 10px 14px; color: #fff; font-size: 0.9rem; outline: none; width: 100%; }
    input:focus, select:focus, textarea:focus { border-color: #FFB400; }
    textarea { resize: vertical; min-height: 80px; }

    .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .producto-card { background: #111; border: 1px solid #1a1a1a; border-radius: 16px; overflow: hidden; transition: border-color 0.2s; }
    .producto-card:hover { border-color: #333; }
    .producto-img { width: 100%; height: 180px; object-fit: cover; background: #1a1a1a; display: flex; align-items: center; justify-content: center; color: #333; font-size: 3rem; }
    .producto-img img { width: 100%; height: 100%; object-fit: cover; }
    .producto-info { padding: 16px; }
    .producto-nombre { font-weight: 700; font-size: 1rem; margin-bottom: 4px; }
    .producto-cat { color: #555; font-size: 0.8rem; margin-bottom: 8px; }
    .producto-precio { color: #FFB400; font-size: 1.2rem; font-weight: 700; margin-bottom: 12px; }
    .producto-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .badge-active { background: #001a00; color: #4caf50; }
    .badge-inactive { background: #1a1a1a; color: #555; }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.show { display: flex; }
    .modal { background: #111; border: 1px solid #222; border-radius: 20px; padding: 32px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
    .modal h3 { color: #FFB400; margin-bottom: 24px; }
    .modal-close { float: right; background: none; border: none; color: #666; font-size: 1.5rem; cursor: pointer; }
</style>

@if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
@endif

{{-- BOTÓN NUEVO PRODUCTO --}}
<div style="margin-bottom:24px">
    <button class="btn btn-primary" onclick="document.getElementById('modalNuevo').classList.add('show')">
        ➕ Nuevo Producto
    </button>
</div>

{{-- GRID DE PRODUCTOS --}}
<div class="productos-grid">
    @forelse($productos as $producto)
    <div class="producto-card">
        <div class="producto-img">
            @if($producto->foto)
                <img src="{{ asset('storage/'.$producto->foto) }}" alt="{{ $producto->nombre }}">
            @else
                🍽️
            @endif
        </div>
        <div class="producto-info">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px">
                <div class="producto-nombre">{{ $producto->nombre }}</div>
                <span class="badge {{ $producto->activo ? 'badge-active' : 'badge-inactive' }}">
                    {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <div class="producto-cat">{{ $producto->categoria->nombre ?? '—' }}</div>
            <div class="producto-precio">${{ number_format($producto->precio, 2) }}</div>
            <div class="producto-actions">
                <button class="btn btn-primary btn-sm"
                    onclick="editarProducto({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', '{{ addslashes($producto->descripcion) }}', {{ $producto->categoria_id }}, {{ $producto->precio }}, {{ $producto->puntos_vip }}, {{ $producto->activo ? 'true' : 'false' }})">
                    ✏️ Editar
                </button>
                <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}"
                      onsubmit="return confirm('¿Eliminar este producto?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit">🗑️</button>
                </form>
            </div>
        </div>
    </div>
    @empty
        <div style="color:#444;padding:40px;text-align:center;grid-column:1/-1">
            No hay productos aún. ¡Crea el primero!
        </div>
    @endforelse
</div>

{{-- MODAL NUEVO PRODUCTO --}}
<div class="modal-overlay" id="modalNuevo">
    <div class="modal">
        <button class="modal-close" onclick="document.getElementById('modalNuevo').classList.remove('show')">×</button>
        <h3>➕ Nuevo Producto</h3>
        <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Foto del producto</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
                <div class="form-group full">
                    <label>Nombre</label>
                    <input type="text" name="nombre" placeholder="Ej: Café Americano" required>
                </div>
                <div class="form-group full">
                    <label>Descripción</label>
                    <textarea name="descripcion" placeholder="Descripción del producto..."></textarea>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" required>
                        <option value="">Selecciona...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icono }} {{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Precio ($)</label>
                    <input type="number" name="precio" step="0.01" min="0" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label>Puntos VIP</label>
                    <input type="number" name="puntos_vip" min="0" placeholder="0">
                </div>
            </div>
            <div style="margin-top:24px;display:flex;gap:12px;justify-content:flex-end">
                <button type="button" class="btn" style="background:#1a1a1a;color:#fff"
                    onclick="document.getElementById('modalNuevo').classList.remove('show')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR PRODUCTO --}}
<div class="modal-overlay" id="modalEditar">
    <div class="modal">
        <button class="modal-close" onclick="document.getElementById('modalEditar').classList.remove('show')">×</button>
        <h3>✏️ Editar Producto</h3>
        <form method="POST" id="formEditar" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group full">
                    <label>Nueva foto (opcional)</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
                <div class="form-group full">
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="editNombre" required>
                </div>
                <div class="form-group full">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="editDescripcion"></textarea>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" id="editCategoria" required>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icono }} {{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Precio ($)</label>
                    <input type="number" name="precio" id="editPrecio" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Puntos VIP</label>
                    <input type="number" name="puntos_vip" id="editPuntos" min="0">
                </div>
                <div class="form-group">
                    <label style="flex-direction:row;gap:8px;align-items:center">
                        <input type="checkbox" name="activo" id="editActivo" style="width:auto">
                        Activo
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
function editarProducto(id, nombre, descripcion, categoriaId, precio, puntos, activo) {
    document.getElementById('formEditar').action = '/admin/productos/' + id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editDescripcion').value = descripcion;
    document.getElementById('editCategoria').value = categoriaId;
    document.getElementById('editPrecio').value = precio;
    document.getElementById('editPuntos').value = puntos;
    document.getElementById('editActivo').checked = activo;
    document.getElementById('modalEditar').classList.add('show');
}
</script>
@endsection