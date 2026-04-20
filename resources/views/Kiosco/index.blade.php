<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'Volada Kiosco</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0a0a0a; color: #fff; height: 100vh; overflow: hidden; }

        /* PANTALLA INICIO */
        #pantallaInicio {
            height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            background: radial-gradient(ellipse at center, #1a0a00 0%, #0a0a0a 70%);
        }
        .logo-kiosco { text-align: center; margin-bottom: 60px; }
        .logo-kiosco h1 { font-size: 5rem; font-weight: 900; color: #FFB400; letter-spacing: -3px; }
        .logo-kiosco p { color: #555; font-size: 1rem; letter-spacing: 4px; text-transform: uppercase; margin-top: 8px; }

        .destino-btns { display: flex; gap: 24px; margin-bottom: 48px; }
        .destino-btn {
            width: 200px; height: 200px; border-radius: 24px;
            border: 2px solid #222; background: #111;
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; cursor: pointer; transition: all 0.3s;
            font-size: 1rem; color: #888; gap: 16px;
        }
        .destino-btn .icon { font-size: 3.5rem; }
        .destino-btn:hover { border-color: #FFB400; color: #fff; transform: translateY(-4px); }
        .destino-btn.selected { border-color: #FFB400; background: #1a1000; color: #FFB400; }

        .vip-section { text-align: center; }
        .vip-section p { color: #555; margin-bottom: 16px; font-size: 0.9rem; }
        .vip-input-row { display: flex; gap: 12px; justify-content: center; }
        .vip-input {
            background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 12px;
            padding: 14px 20px; color: #fff; font-size: 1rem; outline: none; width: 240px;
        }
        .vip-input:focus { border-color: #FFB400; }
        .btn-vip {
            background: #FFB400; color: #000; border: none; border-radius: 12px;
            padding: 14px 24px; font-size: 1rem; font-weight: 700; cursor: pointer;
        }
        .btn-skip { background: none; border: 1px solid #222; color: #555; border-radius: 12px; padding: 14px 24px; font-size: 0.9rem; cursor: pointer; }
        .btn-skip:hover { color: #fff; border-color: #444; }

        .vip-welcome {
            background: #1a1000; border: 1px solid #554000;
            border-radius: 16px; padding: 20px 32px; text-align: center;
            display: none;
        }
        .vip-welcome.show { display: block; }
        .vip-welcome h3 { color: #FFB400; font-size: 1.3rem; }
        .vip-welcome p { color: #888; font-size: 0.9rem; margin-top: 4px; }

        /* PANTALLA MENÚ */
        #pantallaMenu { height: 100vh; display: none; flex-direction: column; }
        #pantallaMenu.show { display: flex; }

        .menu-header {
            background: #111; border-bottom: 1px solid #1a1a1a;
            padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
        }
        .menu-header h2 { color: #FFB400; font-size: 1.3rem; font-weight: 900; }
        .menu-header-right { display: flex; align-items: center; gap: 16px; }
        .vip-chip { background: #1a1000; border: 1px solid #554000; border-radius: 20px; padding: 6px 16px; color: #FFB400; font-size: 0.85rem; }

        .menu-body { display: flex; flex: 1; overflow: hidden; }

        /* CATEGORÍAS */
        .categorias-bar {
            width: 120px; background: #0d0d0d; border-right: 1px solid #1a1a1a;
            overflow-y: auto; padding: 16px 0;
        }
        .cat-btn {
            width: 100%; padding: 16px 8px; border: none; background: none;
            color: #555; cursor: pointer; text-align: center; font-size: 0.75rem;
            transition: all 0.2s; border-left: 3px solid transparent;
        }
        .cat-btn .cat-icon { font-size: 1.8rem; display: block; margin-bottom: 6px; }
        .cat-btn:hover { color: #fff; background: #1a1a1a; }
        .cat-btn.active { color: #FFB400; border-left-color: #FFB400; background: #1a1000; }

        /* PRODUCTOS */
        .productos-area { flex: 1; overflow-y: auto; padding: 20px; }
        .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .prod-card {
            background: #111; border: 1px solid #1a1a1a; border-radius: 16px;
            overflow: hidden; cursor: pointer; transition: all 0.2s;
        }
        .prod-card:hover { border-color: #FFB400; transform: translateY(-2px); }
        .prod-card:active { transform: scale(0.98); }
        .prod-img { width: 100%; height: 140px; object-fit: cover; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #333; }
        .prod-img img { width: 100%; height: 100%; object-fit: cover; }
        .prod-info { padding: 12px; }
        .prod-nombre { font-weight: 600; font-size: 0.9rem; margin-bottom: 4px; }
        .prod-desc { color: #555; font-size: 0.75rem; margin-bottom: 8px; line-height: 1.3; }
        .prod-precio { color: #FFB400; font-weight: 700; font-size: 1rem; }

        /* CARRITO */
        .carrito {
            width: 320px; background: #111; border-left: 1px solid #1a1a1a;
            display: flex; flex-direction: column;
        }
        .carrito-header { padding: 20px; border-bottom: 1px solid #1a1a1a; }
        .carrito-header h3 { font-size: 1rem; }
        .carrito-items { flex: 1; overflow-y: auto; padding: 16px; }
        .carrito-item { display: flex; gap: 12px; align-items: center; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #1a1a1a; }
        .carrito-item-info { flex: 1; }
        .carrito-item-nombre { font-size: 0.85rem; font-weight: 600; margin-bottom: 4px; }
        .carrito-item-precio { color: #FFB400; font-size: 0.85rem; }
        .qty-ctrl { display: flex; align-items: center; gap: 8px; }
        .qty-btn { background: #1a1a1a; border: 1px solid #2a2a2a; color: #fff; width: 28px; height: 28px; border-radius: 8px; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; }
        .qty-btn:hover { border-color: #FFB400; }
        .qty-num { font-size: 0.9rem; min-width: 20px; text-align: center; }

        .carrito-footer { padding: 20px; border-top: 1px solid #1a1a1a; }
        .carrito-total { display: flex; justify-content: space-between; margin-bottom: 16px; }
        .carrito-total span { color: #888; }
        .carrito-total strong { color: #FFB400; font-size: 1.2rem; }
        .btn-pagar { width: 100%; background: #FFB400; color: #000; border: none; border-radius: 12px; padding: 16px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        .btn-pagar:hover { background: #ffc933; }
        .btn-pagar:disabled { background: #333; color: #666; cursor: not-allowed; }
        .btn-cancelar { width: 100%; background: none; border: 1px solid #222; color: #666; border-radius: 12px; padding: 12px; font-size: 0.85rem; cursor: pointer; margin-top: 8px; }
        .btn-cancelar:hover { color: #fff; border-color: #444; }

        .empty-cart { text-align: center; color: #333; padding: 40px 20px; font-size: 0.9rem; }
        .empty-cart .icon { font-size: 3rem; display: block; margin-bottom: 12px; }

        .btn-continuar {
            background: #FFB400; color: #000; border: none; border-radius: 12px;
            padding: 16px 40px; font-size: 1.1rem; font-weight: 700; cursor: pointer;
            margin-top: 32px; transition: all 0.2s;
        }
        .btn-continuar:hover { background: #ffc933; transform: translateY(-2px); }
        .btn-continuar:disabled { background: #333; color: #666; cursor: not-allowed; transform: none; }
    </style>
</head>
<body>

{{-- PANTALLA INICIO --}}
<div id="pantallaInicio">
    <div class="logo-kiosco">
        <h1>D'Volada</h1>
        <p>Bienvenido — Haz tu pedido</p>
    </div>

    <div class="destino-btns">
        <div class="destino-btn" id="btnSalon" onclick="seleccionarDestino('salon')">
            <span class="icon">🪑</span>
            Para el salón
        </div>
        <div class="destino-btn" id="btnLlevar" onclick="seleccionarDestino('llevar')">
            <span class="icon">🛍️</span>
            Para llevar
        </div>
    </div>

    <div class="vip-section">
        <p>¿Tienes tarjeta VIP D'Volada?</p>
        <div class="vip-input-row" id="vipInputRow">
            <input type="text" class="vip-input" id="inputTarjeta" placeholder="Número de tarjeta VIP">
            <button class="btn-vip" onclick="buscarVip()">Buscar</button>
            <button class="btn-skip" onclick="continuarSinVip()">Continuar sin tarjeta</button>
        </div>
        <div class="vip-welcome" id="vipWelcome">
            <h3>¡Bienvenido, <span id="vipNombre"></span>! 👋</h3>
            <p>Tienes <strong id="vipPuntos"></strong> puntos acumulados · Nivel <span id="vipNivel"></span></p>
            <button class="btn-continuar" onclick="irAlMenu()">Ver menú →</button>
        </div>
    </div>

    <button class="btn-continuar" id="btnContinuar" disabled onclick="irAlMenu()" style="margin-top:32px">
        Ver menú →
    </button>
</div>

{{-- PANTALLA MENÚ --}}
<div id="pantallaMenu">
    <div class="menu-header">
        <h2>D'Volada</h2>
        <div class="menu-header-right">
            <span id="destinoChip" style="color:#555;font-size:0.85rem"></span>
            <div class="vip-chip" id="vipChip" style="display:none">⭐ <span id="vipChipNombre"></span></div>
            <button onclick="volverInicio()" style="background:none;border:1px solid #222;color:#555;padding:8px 16px;border-radius:8px;cursor:pointer;font-size:0.8rem">← Inicio</button>
        </div>
    </div>

    <div class="menu-body">
        {{-- CATEGORÍAS --}}
        <div class="categorias-bar">
            <button class="cat-btn active" onclick="filtrarCategoria(0, this)">
                <span class="cat-icon">🍽️</span>
                Todo
            </button>
            @foreach($categorias as $cat)
            <button class="cat-btn" onclick="filtrarCategoria({{ $cat->id }}, this)">
                <span class="cat-icon">{{ $cat->icono ?? '📁' }}</span>
                {{ $cat->nombre }}
            </button>
            @endforeach
        </div>

        {{-- PRODUCTOS --}}
        <div class="productos-area">
            <div class="productos-grid" id="productosGrid">
                @foreach($productos as $prod)
                <div class="prod-card" data-categoria="{{ $prod->categoria_id }}"
                    onclick="agregarAlCarrito({{ $prod->id }}, '{{ addslashes($prod->nombre) }}', {{ $prod->precio }})">
                    <div class="prod-img">
                        @if($prod->foto)
                            <img src="{{ asset('storage/'.$prod->foto) }}" alt="{{ $prod->nombre }}">
                        @else
                            🍽️
                        @endif
                    </div>
                    <div class="prod-info">
                        <div class="prod-nombre">{{ $prod->nombre }}</div>
                        <div class="prod-desc">{{ Str::limit($prod->descripcion, 60) }}</div>
                        <div class="prod-precio">${{ number_format($prod->precio, 2) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CARRITO --}}
        <div class="carrito">
            <div class="carrito-header">
                <h3>🛒 Tu orden</h3>
            </div>
            <div class="carrito-items" id="carritoItems">
                <div class="empty-cart">
                    <span class="icon">🛒</span>
                    Agrega productos para comenzar
                </div>
            </div>
            <div class="carrito-footer">
                <div class="carrito-total">
                    <span>Total</span>
                    <strong id="totalCarrito">$0.00</strong>
                </div>
                <button class="btn-pagar" id="btnPagar" disabled onclick="irAPagar()">
                    Pagar →
                </button>
                <button class="btn-cancelar" onclick="volverInicio()">Cancelar pedido</button>
            </div>
        </div>
    </div>
</div>

<script>
    let destino = null;
    let clienteVip = null;
    let carrito = [];

    function seleccionarDestino(tipo) {
        destino = tipo;
        document.getElementById('btnSalon').classList.toggle('selected', tipo === 'salon');
        document.getElementById('btnLlevar').classList.toggle('selected', tipo === 'llevar');
        document.getElementById('btnContinuar').disabled = false;
    }

    async function buscarVip() {
        const tarjeta = document.getElementById('inputTarjeta').value.trim();
        if (!tarjeta) return;

        try {
            const res = await fetch('{{ route("kiosco.kiosco.buscar-vip") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ tarjeta })
            });

            if (!res.ok) {
                alert('Tarjeta no encontrada');
                return;
            }

            const data = await res.json();
            clienteVip = data;

            document.getElementById('vipInputRow').style.display = 'none';
            document.getElementById('vipWelcome').classList.add('show');
            document.getElementById('vipNombre').textContent = data.apodo || data.nombre;
            document.getElementById('vipPuntos').textContent = data.puntos;
            document.getElementById('vipNivel').textContent = data.nivel;
            document.getElementById('btnContinuar').style.display = 'none';

            if (data.cumple) {
                alert('🎂 ¡Feliz cumpleaños! Tienes un descuento especial hoy.');
            }
        } catch(e) {
            alert('Error al buscar la tarjeta');
        }
    }

    function continuarSinVip() {
        clienteVip = null;
        if (destino) irAlMenu();
        else alert('Selecciona primero si es para el salón o para llevar');
    }

    function irAlMenu() {
        if (!destino) {
            alert('Selecciona primero si es para el salón o para llevar');
            return;
        }
        document.getElementById('pantallaInicio').style.display = 'none';
        document.getElementById('pantallaMenu').classList.add('show');
        document.getElementById('destinoChip').textContent = destino === 'salon' ? '🪑 Salón' : '🛍️ Para llevar';

        if (clienteVip) {
            document.getElementById('vipChip').style.display = 'block';
            document.getElementById('vipChipNombre').textContent = clienteVip.apodo || clienteVip.nombre;
        }
    }

    function volverInicio() {
        destino = null;
        clienteVip = null;
        carrito = [];
        document.getElementById('pantallaInicio').style.display = 'flex';
        document.getElementById('pantallaMenu').classList.remove('show');
        document.getElementById('btnSalon').classList.remove('selected');
        document.getElementById('btnLlevar').classList.remove('selected');
        document.getElementById('btnContinuar').disabled = true;
        document.getElementById('vipInputRow').style.display = 'flex';
        document.getElementById('vipWelcome').classList.remove('show');
        document.getElementById('inputTarjeta').value = '';
        document.getElementById('btnContinuar').style.display = 'block';
        actualizarCarrito();
    }

    function filtrarCategoria(catId, btn) {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.prod-card').forEach(card => {
            if (catId === 0 || card.dataset.categoria == catId) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function agregarAlCarrito(id, nombre, precio) {
        const existing = carrito.find(i => i.id === id);
        if (existing) {
            existing.cantidad++;
        } else {
            carrito.push({ id, nombre, precio, cantidad: 1 });
        }
        actualizarCarrito();
    }

    function cambiarCantidad(id, delta) {
        const item = carrito.find(i => i.id === id);
        if (!item) return;
        item.cantidad += delta;
        if (item.cantidad <= 0) carrito = carrito.filter(i => i.id !== id);
        actualizarCarrito();
    }

    function actualizarCarrito() {
        const container = document.getElementById('carritoItems');
        const total = carrito.reduce((sum, i) => sum + i.precio * i.cantidad, 0);

        if (carrito.length === 0) {
            container.innerHTML = '<div class="empty-cart"><span class="icon">🛒</span>Agrega productos para comenzar</div>';
            document.getElementById('btnPagar').disabled = true;
            document.getElementById('totalCarrito').textContent = '$0.00';
            return;
        }

        container.innerHTML = carrito.map(item => `
            <div class="carrito-item">
                <div class="carrito-item-info">
                    <div class="carrito-item-nombre">${item.nombre}</div>
                    <div class="carrito-item-precio">$${(item.precio * item.cantidad).toFixed(2)}</div>
                </div>
                <div class="qty-ctrl">
                    <button class="qty-btn" onclick="cambiarCantidad(${item.id}, -1)">−</button>
                    <span class="qty-num">${item.cantidad}</span>
                    <button class="qty-btn" onclick="cambiarCantidad(${item.id}, 1)">+</button>
                </div>
            </div>
        `).join('');

        document.getElementById('totalCarrito').textContent = '$' + total.toFixed(2);
        document.getElementById('btnPagar').disabled = false;
    }

    async function irAPagar() {
    if (carrito.length === 0) return;

    const btnPagar = document.getElementById('btnPagar');
    btnPagar.disabled = true;
    btnPagar.textContent = 'Procesando...';

    try {
        const res = await fetch('{{ route("kiosco.kiosco.crear-orden") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                destino: destino,
                productos: carrito,
                total: carrito.reduce((sum, i) => sum + i.precio * i.cantidad, 0),
                cliente_vip_id: clienteVip ? clienteVip.id : null,
                metodo_pago: 'terminal'
            })
        });

        const data = await res.json();

        if (data.ok) {
            alert('✅ Orden #' + data.numero_orden + ' enviada a cocina!');
            volverInicio();
        } else {
            alert('Error al crear la orden');
            btnPagar.disabled = false;
            btnPagar.textContent = 'Pagar →';
        }
    } catch(e) {
        alert('Error de conexión');
        btnPagar.disabled = false;
        btnPagar.textContent = 'Pagar →';
    }

    }
</script>

</body>
</html>