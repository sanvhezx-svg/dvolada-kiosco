<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocina — D'Volada</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0a0a0a; color: #fff; min-height: 100vh; }

        .header {
            background: #111; border-bottom: 1px solid #1a1a1a;
            padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
        }
        .header h1 { color: #FFB400; font-size: 1.3rem; font-weight: 900; }
        .header-right { display: flex; align-items: center; gap: 16px; }
        .clock { color: #555; font-size: 1rem; font-family: monospace; }
        .btn-refresh { background: #1a1a1a; border: 1px solid #222; color: #fff; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 0.85rem; }

        .content { padding: 24px; }

        .ordenes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }

        .orden-card {
            background: #111; border: 2px solid #1a1a1a;
            border-radius: 16px; overflow: hidden; transition: all 0.3s;
        }
        .orden-card.recibido { border-color: #1a1a2e; }
        .orden-card.preparando { border-color: #554000; }
        .orden-card.urgente { border-color: #500; animation: pulso 1s infinite; }

        @keyframes pulso {
            0%, 100% { border-color: #500; }
            50% { border-color: #f00; }
        }

        .orden-header {
            padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;
        }
        .orden-header.recibido { background: #0d0d1a; }
        .orden-header.preparando { background: #1a1000; }
        .orden-header.urgente { background: #1a0000; }

        .orden-num { font-size: 1.3rem; font-weight: 900; }
        .orden-num.recibido { color: #4466ff; }
        .orden-num.preparando { color: #FFB400; }
        .orden-num.urgente { color: #ff4444; }

        .orden-meta { text-align: right; }
        .orden-destino { font-size: 0.8rem; color: #888; }
        .orden-tiempo { font-size: 0.75rem; color: #555; margin-top: 2px; }
        .orden-tiempo.urgente { color: #ff4444; font-weight: 700; }

        .orden-productos { padding: 16px 20px; border-top: 1px solid #1a1a1a; }
        .prod-item { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px; }
        .prod-qty { background: #FFB400; color: #000; font-weight: 700; font-size: 0.85rem; min-width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .prod-nombre { font-size: 0.9rem; font-weight: 600; }
        .prod-adicionales { color: #555; font-size: 0.78rem; margin-top: 2px; }

        .orden-footer { padding: 16px 20px; border-top: 1px solid #1a1a1a; display: flex; gap: 10px; }
        .btn-estado {
            flex: 1; padding: 12px; border-radius: 10px; border: none;
            font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s;
        }
        .btn-preparando { background: #1a1000; color: #FFB400; border: 1px solid #554000; }
        .btn-preparando:hover { background: #2a1a00; }
        .btn-listo { background: #001a00; color: #4caf50; border: 1px solid #055; }
        .btn-listo:hover { background: #002a00; }

        .empty { text-align: center; padding: 80px; color: #333; }
        .empty .icon { font-size: 4rem; display: block; margin-bottom: 16px; }

        .badge-vip { background: #1a1000; color: #FFB400; border: 1px solid #554000; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; }
        .badge-llevar { background: #001a1a; color: #00aaff; border: 1px solid #005; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; }
        .badge-salon { background: #0a1a0a; color: #4caf50; border: 1px solid #050; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; }
    </style>
</head>
<body>

<div class="header">
    <h1>🍳 Cocina — D'Volada</h1>
    <div class="header-right">
        <span class="clock" id="reloj"></span>
        <button class="btn-refresh" onclick="location.reload()">🔄 Actualizar</button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-refresh" type="submit">Salir</button>
        </form>
    </div>
</div>

<div class="content">
    @if($ordenes->isEmpty())
        <div class="empty">
            <span class="icon">✅</span>
            No hay órdenes pendientes
        </div>
    @else
    <div class="ordenes-grid">
        @foreach($ordenes as $orden)
        @php
            $minutos = $orden->created_at->diffInMinutes(now());
            $urgente = $minutos > 15;
            $claseEstado = $urgente ? 'urgente' : $orden->estado;
        @endphp
        <div class="orden-card {{ $claseEstado }}" id="orden-{{ $orden->id }}">
            <div class="orden-header {{ $claseEstado }}">
                <div>
                    <div class="orden-num {{ $claseEstado }}">#{{ $orden->numero_orden }}</div>
                    <div style="display:flex;gap:6px;margin-top:6px">
                        <span class="badge-{{ $orden->destino }}">
                            {{ $orden->destino === 'salon' ? '🪑 Salón' : '🛍️ Llevar' }}
                        </span>
                        @if($orden->cliente_vip_id)
                            <span class="badge-vip">⭐ VIP</span>
                        @endif
                    </div>
                </div>
                <div class="orden-meta">
                    <div class="orden-destino">
                        {{ $orden->estado === 'recibido' ? '📥 Recibido' : '👨‍🍳 Preparando' }}
                    </div>
                    <div class="orden-tiempo {{ $urgente ? 'urgente' : '' }}">
                        ⏱️ {{ $minutos }} min {{ $urgente ? '— ¡URGENTE!' : '' }}
                    </div>
                </div>
            </div>

            <div class="orden-productos">
                @foreach($orden->productos as $item)
                <div class="prod-item">
                    <div class="prod-qty">{{ $item->cantidad }}</div>
                    <div>
                        <div class="prod-nombre">{{ $item->producto->nombre ?? 'Producto' }}</div>
                        @if($item->adicionales)
                            <div class="prod-adicionales">
                                {{ implode(', ', array_column($item->adicionales, 'nombre')) }}
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="orden-footer">
                @if($orden->estado === 'recibido')
                <button class="btn-estado btn-preparando"
                    onclick="cambiarEstado({{ $orden->id }}, 'preparando')">
                    👨‍🍳 En preparación
                </button>
                @endif
                <button class="btn-estado btn-listo"
                    onclick="cambiarEstado({{ $orden->id }}, 'listo')">
                    ✅ Listo
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script>
    // Reloj
    function actualizarReloj() {
        const now = new Date();
        document.getElementById('reloj').textContent =
            now.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    setInterval(actualizarReloj, 1000);
    actualizarReloj();

    // Auto-refresh cada 30 segundos
    setTimeout(() => location.reload(), 30000);

    async function cambiarEstado(ordenId, estado) {
        try {
            const res = await fetch(`/cocina/orden/${ordenId}/${estado}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            if (res.ok) {
                if (estado === 'listo') {
                    document.getElementById('orden-' + ordenId).remove();
                } else {
                    location.reload();
                }
            }
        } catch(e) {
            alert('Error al actualizar estado');
        }
    }
</script>

</body>
</html>
