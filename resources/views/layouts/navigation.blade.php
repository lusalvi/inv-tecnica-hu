<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventario Técnica — HU</title>
    <link rel="icon" href="{{ asset('images/hu_icon.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --hu-azul:   #003764;
            --hu-dorado: #C7A36E;
            --hu-texto:  #59595B;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #e8edf3;
            color: var(--hu-texto);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* ── Tarjeta principal ── */
        .auth-card {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 520px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 12px 48px rgba(0, 55, 100, .18);
            overflow: hidden;
        }

        /* ── Panel izquierdo: formulario ── */
        .auth-form-panel {
            flex: 0 0 420px;
            padding: 3rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-logo {
            display: block;
            max-width: 180px;
            margin-bottom: 2rem;
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 900;
            color: var(--hu-azul);
            margin: 0 0 .35rem;
            line-height: 1.15;
        }

        .auth-subtitle {
            font-size: .8rem;
            color: #8a9ab0;
            margin: 0 0 2rem;
            font-weight: 400;
        }

        /* ── Inputs underline ── */
        .auth-field {
            margin-bottom: 1.4rem;
        }

        .auth-field label {
            display: block;
            font-size: .72rem;
            font-weight: 700;
            color: var(--hu-azul);
            margin-bottom: .35rem;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .auth-input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #ccd6e0;
            border-radius: 0;
            padding: .45rem 0 .45rem;
            font-family: 'Montserrat', sans-serif;
            font-size: .875rem;
            color: var(--hu-texto);
            outline: none;
            transition: border-color .2s;
        }

        .auth-input::placeholder { color: #b0bec8; }

        .auth-input:focus {
            border-bottom-color: var(--hu-azul);
        }

        .auth-input.is-invalid {
            border-bottom-color: #dc3545;
        }

        .invalid-feedback { font-size: .75rem; }

        /* ── Recordarme ── */
        .auth-remember {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.75rem;
        }

        .auth-remember input[type="checkbox"] {
            accent-color: var(--hu-azul);
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .auth-remember label {
            font-size: .78rem;
            color: var(--hu-texto);
            cursor: pointer;
            margin: 0;
        }

        /* ── Botón principal ── */
        .btn-hu {
            display: block;
            width: 100%;
            background-color: var(--hu-azul);
            color: #fff;
            border: none;
            border-radius: 999px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .875rem;
            padding: .7rem 1rem;
            cursor: pointer;
            transition: background-color .2s, box-shadow .2s;
            text-align: center;
            text-decoration: none;
        }

        .btn-hu:hover {
            background-color: #00254a;
            box-shadow: 0 6px 18px rgba(0, 55, 100, .28);
            color: #fff;
        }

        /* ── Link secundario ── */
        .auth-link {
            margin-top: 1.25rem;
            font-size: .78rem;
            color: var(--hu-texto);
            text-align: center;
        }

        .auth-link a {
            color: var(--hu-azul);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-link a:hover { text-decoration: underline; }

        /* ── Panel derecho: decoración hexagonal ── */
        .auth-deco-panel {
            flex: 1;
            background-color: var(--hu-azul);
            position: relative;
            overflow: hidden;
        }

        .auth-deco-panel svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        /* ── Registro: card más ancha ── */
        .auth-card.register-card {
            max-width: 980px;
        }

        .auth-card.register-card .auth-form-panel {
            flex: 0 0 520px;
            padding: 2.5rem 2.75rem;
        }

        .register-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 1.5rem;
        }

        .register-role-note {
            font-size: .72rem;
            color: #8a9ab0;
            margin-top: .4rem;
        }

        .register-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1.75rem;
        }

        .register-actions .btn-hu {
            width: auto;
            min-width: 150px;
            display: inline-block;
        }

        .register-login-link {
            font-size: .78rem;
            color: var(--hu-texto);
            text-decoration: none;
        }

        .register-login-link span {
            color: var(--hu-azul);
            font-weight: 700;
        }

        .register-login-link:hover span { text-decoration: underline; }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            .auth-deco-panel { display: none; }

            .auth-card,
            .auth-card.register-card { max-width: 440px; }

            .auth-form-panel,
            .auth-card.register-card .auth-form-panel {
                flex: 1;
                padding: 2.5rem 1.75rem;
            }

            .register-grid { grid-template-columns: 1fr; }

            .register-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .register-actions .btn-hu { width: 100%; }
        }
    </style>
</head>

<body>

    <div class="auth-card {{ request()->routeIs('register') ? 'register-card' : '' }}">

        {{-- Panel formulario --}}
        <div class="auth-form-panel">

            <a href="/login">
                <img src="{{ asset('images/hu_logo.png') }}" alt="Hospital Universitario" class="auth-logo">
            </a>

            {{ $slot }}

        </div>

        {{-- Panel decorativo hexagonal --}}
        <div class="auth-deco-panel">
            <svg viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
                <!-- Hexágono relleno grande - centro derecha -->
                <polygon points="310,200 370,165 430,200 430,270 370,305 310,270"
                         fill="#1a5a9a" opacity="0.85"/>
                <!-- Hexágono relleno mediano - arriba centro -->
                <polygon points="230,120 275,95 320,120 320,170 275,195 230,170"
                         fill="#2a70b8" opacity="0.7"/>
                <!-- Hexágono outline grande - esquina superior derecha -->
                <polygon points="360,50 420,15 480,50 480,120 420,155 360,120"
                         fill="none" stroke="#5a9fd4" stroke-width="2" opacity="0.6"/>
                <!-- Hexágono outline mediano - derecha medio -->
                <polygon points="400,270 445,245 490,270 490,320 445,345 400,320"
                         fill="none" stroke="#4a8fc4" stroke-width="1.5" opacity="0.5"/>
                <!-- Hexágono relleno pequeño - arriba izquierda -->
                <polygon points="160,80 192,62 224,80 224,116 192,134 160,116"
                         fill="#3a80c8" opacity="0.6"/>
                <!-- Hexágono relleno mini - entre mediano y grande arriba -->
                <polygon points="280,75 303,62 326,75 326,101 303,114 280,101"
                         fill="#2a70b8" opacity="0.5"/>
                <!-- Hexágono outline grande - abajo izquierda -->
                <polygon points="150,340 220,300 290,340 290,420 220,460 150,420"
                         fill="none" stroke="#4a8fc4" stroke-width="2" opacity="0.4"/>
                <!-- Hexágono relleno grande - abajo centro -->
                <polygon points="270,390 340,352 410,390 410,466 340,504 270,466"
                         fill="#1a5a9a" opacity="0.6"/>
                <!-- Hexágono relleno mediano - abajo izquierda -->
                <polygon points="90,400 130,377 170,400 170,446 130,469 90,446"
                         fill="#2a70b8" opacity="0.5"/>
                <!-- Hexágono outline pequeño - centro izquierda -->
                <polygon points="100,230 128,214 156,230 156,262 128,278 100,262"
                         fill="none" stroke="#5a9fd4" stroke-width="1.5" opacity="0.55"/>
                <!-- Hexágono mini relleno - disperso arriba derecha -->
                <polygon points="440,140 458,130 476,140 476,160 458,170 440,160"
                         fill="#5a9fd4" opacity="0.5"/>
                <!-- Hexágono mini outline - disperso medio -->
                <polygon points="185,285 203,275 221,285 221,305 203,315 185,305"
                         fill="none" stroke="#6aaee4" stroke-width="1.5" opacity="0.5"/>
                <!-- Hexágono mini relleno - abajo derecha -->
                <polygon points="430,460 448,450 466,460 466,480 448,490 430,480"
                         fill="#4a8fc4" opacity="0.45"/>
                <!-- Hexágono outline mini - arriba izquierda extremo -->
                <polygon points="60,60 80,49 100,60 100,82 80,93 60,82"
                         fill="none" stroke="#5a9fd4" stroke-width="1.5" opacity="0.4"/>
                <!-- Punto decorativo pequeño -->
                <polygon points="340,310 350,304 360,310 360,322 350,328 340,322"
                         fill="#5a9fd4" opacity="0.6"/>
            </svg>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>