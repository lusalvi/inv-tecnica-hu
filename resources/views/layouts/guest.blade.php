<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventario Técnica — HU</title>
    <link rel="icon" href="{{ asset('images/hu_icon.png') }}" type="image/x-icon">

    {{-- Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --hu-azul:   #003764;
            --hu-dorado: #C7A36E;
            --hu-texto:  #59595B;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F4F6F9;
            color: var(--hu-texto);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* =========================
           Card de autenticación
           ========================= */

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,55,100,.12);
            padding: 2.5rem 2rem 2rem;
        }

        /* La vista de registro necesita un poco más de ancho
           para distribuir los campos en dos columnas */
        .login-card.register-card {
            max-width: 780px;
        }

        .login-logo {
            display: block;
            margin: 0 auto 1.5rem;
            max-width: 220px;
        }

        /* =========================
           Formularios
           ========================= */

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            font-family: 'Montserrat', sans-serif;
            font-size: .875rem;
        }

        .form-control:focus {
            border-color: var(--hu-azul);
            box-shadow: 0 0 0 0.2rem rgba(0,55,100,.15);
        }

        .form-label {
            font-weight: 600;
            font-size: .82rem;
            color: var(--hu-azul);
        }

        /* =========================
           Botón principal
           ========================= */

        .btn-hu-login {
            background-color: var(--hu-azul);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .9rem;
            padding: .55rem 1rem;
            width: 100%;
            transition: background-color .2s ease, box-shadow .2s ease;
        }

        .btn-hu-login:hover {
            background-color: #00254a;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,55,100,.25);
        }

        /* =========================
           Login
           ========================= */

        .divider {
            border-top: 1px solid rgba(0,55,100,.1);
            margin: 1.5rem 0 1rem;
        }

        .register-link {
            font-size: .8rem;
            color: var(--hu-texto);
            text-align: center;
        }

        .register-link a {
            color: var(--hu-azul);
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* =========================
           Registro
           ========================= */

        .register-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 24px;
        }

        .register-field {
            min-width: 0;
        }

        .register-field .form-label {
            display: block;
            margin-bottom: 7px;
        }

        .register-role {
            margin-top: 18px;
        }

        .register-role .form-label {
            display: block;
            margin-bottom: 7px;
        }

        .register-role .form-control {
            background-color: #f8f9fa;
            color: #495057;
            cursor: default;
        }

        .register-role-help {
            margin: 7px 0 0;
            font-size: .78rem;
            line-height: 1.4;
            color: #6c757d;
        }

        .register-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 24px;
        }

        .register-login-link {
            font-size: .8rem;
            color: var(--hu-texto);
            text-decoration: none;
        }

        .register-login-link:hover {
            color: var(--hu-azul);
        }

        .register-login-link span {
            color: var(--hu-azul);
            font-weight: 600;
        }

        .register-actions .btn-hu-login {
            width: auto;
            min-width: 150px;
        }

        /* =========================
           Responsive
           ========================= */

        @media (max-width: 640px) {

            body {
                padding: 1rem;
            }

            .login-card.register-card {
                max-width: 420px;
            }

            .register-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .register-actions {
                flex-direction: column-reverse;
                align-items: stretch;
                gap: 14px;
                margin-top: 20px;
            }

            .register-actions .btn-hu-login {
                width: 100%;
            }

            .register-login-link {
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <div class="login-card {{ request()->routeIs('register') ? 'register-card' : '' }}">

        {{-- Logo HU --}}
        <a href="/login">
            <img
                src="{{ asset('images/hu_logo.png') }}"
                alt="Hospital Universitario"
                class="login-logo"
            >
        </a>

        <p
            class="text-center mb-4"
            style="font-size:.78rem;color:var(--hu-texto);letter-spacing:.04em;text-transform:uppercase;"
        >
            Sistema de Inventario — Área Técnica
        </p>

        {{ $slot }}

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
