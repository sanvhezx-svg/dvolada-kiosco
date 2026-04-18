<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'Volada Kiosco — Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(ellipse at 20% 50%, #1a0a2e 0%, transparent 50%),
                              radial-gradient(ellipse at 80% 20%, #0d1f0d 0%, transparent 50%);
        }

        .card {
            background: #111;
            border: 1px solid #222;
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 60px rgba(255,180,0,0.05);
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo h1 {
            font-size: 2.5rem;
            font-weight: 900;
            color: #FFB400;
            letter-spacing: -1px;
        }

        .logo p {
            color: #555;
            font-size: 0.85rem;
            margin-top: 4px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .error {
            background: #1a0000;
            border: 1px solid #500;
            color: #ff6b6b;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #888;
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 14px 18px;
            color: #fff;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #FFB400;
        }

        button {
            width: 100%;
            background: #FFB400;
            color: #000;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            letter-spacing: 0.5px;
            transition: background 0.2s, transform 0.1s;
        }

        button:hover { background: #ffc933; }
        button:active { transform: scale(0.98); }

        .version {
            text-align: center;
            color: #333;
            font-size: 0.75rem;
            margin-top: 32px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <h1>Nuvi-IA</h1>
            <p>Sistema de Kiosco</p>
        </div>

        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" value="{{ old('usuario') }}"
                    placeholder="Ingresa tu usuario" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password"
                    placeholder="******" required>
            </div>
            <button type="submit">Entrar</button>
        </form>

        <div class="version">v1.0.0 — Nuvi-IA Kiosco System</div>
    </div>
</body>
</html>