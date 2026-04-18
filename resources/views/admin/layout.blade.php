<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "D'Volada Admin")</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0a0a0a; color: #fff; display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: #111;
            border-right: 1px solid #1a1a1a;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 28px 24px;
            border-bottom: 1px solid #1a1a1a;
        }
        .sidebar-logo h1 { color: #FFB400; font-size: 1.4rem; font-weight: 900; }
        .sidebar-logo p { color: #444; font-size: 0.75rem; margin-top: 2px; }

        .nav { padding: 16px 0; flex: 1; }
        .nav-section {
            padding: 8px 24px 4px;
            color: #333;
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .nav a:hover { color: #fff; background: #1a1a1a; }
        .nav a.active { color: #FFB400; border-left-color: #FFB400; background: #1a1a1a; }
        .nav a .icon { font-size: 1.1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 20px 24px;
            border-top: 1px solid #1a1a1a;
        }
        .user-info { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
        .user-avatar {
            width: 36px; height: 36px;
            background: #FFB400;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #000; font-weight: 700; font-size: 0.9rem;
        }
        .user-name { color: #fff; font-size: 0.85rem; font-weight: 600; }
        .user-rol { color: #444; font-size: 0.75rem; }
        .btn-logout {
            width: 100%;
            background: #1a1a1a;
            border: 1px solid #222;
            color: #666;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-logout:hover { color: #fff; border-color: #444; }

        /* MAIN */
        .main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: #111;
            border-bottom: 1px solid #1a1a1a;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar h2 { font-size: 1.1rem; font-weight: 600; }
        .topbar .breadcrumb { color: #444; font-size: 0.85rem; }

        .content { padding: 32px; flex: 1; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-logo">
        <h1>D'Volada</h1>
        <p>Panel de Administración</p>
    </div>

    <nav class="nav">
        <div class="nav-section">Principal</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a>

        <div class="nav-section">Menú</div>
        <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos*') ? 'active' : '' }}">
            <span class="icon">🍽️</span> Productos
        </a>
        <a href="{{ route('admin.categorias.index') }}" class="{{ request()->routeIs('admin.categorias*') ? 'active' : '' }}">
            <span class="icon">📁</span> Categorías
        </a>

        <div class="nav-section">Clientes</div>
        <a href="{{ route('admin.vips.index') }}" class="{{ request()->routeIs('admin.vips*') ? 'active' : '' }}">
            <span class="icon">⭐</span> Clientes VIP
        </a>

        <div class="nav-section">Operación</div>
        <a href="{{ route('admin.ordenes.index') }}" class="{{ request()->routeIs('admin.ordenes*') ? 'active' : '' }}">
            <span class="icon">📋</span> Órdenes
        </a>
        <a href="{{ route('admin.anuncios.index') }}" class="{{ request()->routeIs('admin.anuncios*') ? 'active' : '' }}">
            <span class="icon">📢</span> Anuncios
        </a>

        <div class="nav-section">Sistema</div>
        <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}">
            <span class="icon">👥</span> Usuarios
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(session('usuario_nombre'), 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ session('usuario_nombre') }}</div>
                <div class="user-rol">{{ session('usuario_rol') }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-logout" type="submit">Cerrar sesión</button>
        </form>
    </div>
</div>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <h2>@yield('page_title', 'Dashboard')</h2>
        <span class="breadcrumb">@yield('breadcrumb', 'Inicio')</span>
    </div>
    <div class="content">
        @yield('content')
    </div>
</div>

</body>
</html>