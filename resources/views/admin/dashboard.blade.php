<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — D'Volada</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0a0a0a; color: #fff; }
        .header {
            background: #111;
            border-bottom: 1px solid #222;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { color: #FFB400; font-size: 1.5rem; }
        .header span { color: #555; font-size: 0.9rem; }
        .logout {
            background: #1a1a1a;
            border: 1px solid #333;
            color: #fff;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
        }
        .content { padding: 40px; }
        .welcome {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .welcome span { color: #FFB400; }
        .subtitle { color: #555; margin-bottom: 40px; }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .card {
            background: #111;
            border: 1px solid #222;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
        }
        .card .icon { font-size: 2rem; margin-bottom: 12px; }
        .card h3 { color: #FFB400; font-size: 1.8rem; margin-bottom: 4px; }
        .card p { color: #555; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="header">
        <h1>D'Volada Kiosco</h1>
        <div style="display:flex;align-items:center;gap:20px">
            <span>👤 {{ session('usuario_nombre') }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">Cerrar sesión</button>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="welcome">Bienvenido, <span>{{ session('usuario_nombre') }}</span></div>
        <div class="subtitle">Panel de Administración — D'Volada Kiosco System</div>

        <div class="cards">
            <div class="card">
                <div class="icon">🍽️</div>
                <h3>0</h3>
                <p>Productos</p>
            </div>
            <div class="card">
                <div class="icon">📋</div>
                <h3>0</h3>
                <p>Órdenes hoy</p>
            </div>
            <div class="card">
                <div class="icon">⭐</div>
                <h3>0</h3>
                <p>Clientes VIP</p>
            </div>
            <div class="card">
                <div class="icon">💰</div>
                <h3>$0</h3>
                <p>Ventas hoy</p>
            </div>
        </div>
    </div>
</body>
</html>