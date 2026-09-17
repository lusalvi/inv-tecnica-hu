<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventario Técnica — HU</title>
    <link rel="icon" href="{{ asset('images/hu_icon.png') }}" type="image/x-icon">

    {{-- Montserrat desde Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">

    {{-- Google Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" />

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Estilos compilados (Tailwind + app.css) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS opcionales: cada vista pushea solo lo que necesita --}}
    @stack('styles')

    <style>
        /* ── Alpine: ocultar hasta inicialización ── */
        [x-cloak] { display: none !important; }

        /* ── Variables institucionales ── */
        :root {
            --hu-azul:   #003764;
            --hu-dorado: #C7A36E;
            --hu-texto:  #59595B;
        }

        /* ── Tipografía base ── */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F4F6F9;
            color: var(--hu-texto);
        }

        /* ── DataTables: búsqueda y paginación ── */
        .dataTables_filter {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .dataTables_filter input {
            border: 1px solid #ced4da !important;
            border-radius: 8px !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-family: 'Montserrat', sans-serif;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            border: 1px solid var(--hu-azul) !important;
            background-color: #f0f4f8 !important;
            color: var(--hu-azul) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--hu-azul) !important;
            color: #fff !important;
            border: 1px solid var(--hu-azul) !important;
            border-radius: 6px;
        }

        /* ── Tablas ── */
        table.dataTable tbody tr:hover > td {
            background-color: #e8eff7 !important;
            cursor: pointer;
        }

        table.dataTable thead th {
            background-color: #fff !important;
            color: var(--hu-azul) !important;
            font-weight: 600;
            border-bottom: 2px solid var(--hu-azul) !important;
        }

        table.dataTable tbody td {
            color: var(--hu-texto) !important;
            font-weight: 400 !important;
            border: none !important;
            vertical-align: middle;
        }

        table tbody td b,
        table tbody td strong {
            font-weight: 400 !important;
        }

        table.dataTable {
            border: none !important;
        }

        /* Excepciones para tablas de historia */
        #table_historias thead,
        #table_historias_pc thead {
            display: table-header-group !important;
        }

        /* ── Cards del home ── */
        .card {
            border: 1px solid rgba(0, 55, 100, 0.1);
            border-radius: 12px;
            transition: box-shadow 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
            background-color: #ffffff;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 55, 100, 0.12);
            transform: translateY(-2px);
            border-color: rgba(0, 55, 100, 0.25);
        }

        /* ── Botones institucionales ── */
        .btn-hu {
            background-color: var(--hu-azul);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.45rem 1rem;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-hu:hover {
            background-color: #00254a;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 55, 100, 0.25);
        }

        .btn-hu-outline {
            background-color: transparent;
            color: var(--hu-azul);
            border: 1.5px solid var(--hu-azul);
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.45rem 1rem;
            transition: all 0.2s ease;
        }

        .btn-hu-outline:hover {
            background-color: var(--hu-azul);
            color: #fff;
        }

        .btn-hu-outline-dorado {
            background-color: transparent;
            color: var(--hu-dorado);
            border: 1.5px solid var(--hu-dorado);
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.45rem 1rem;
            transition: all 0.2s ease;            
        }

        .btn-hu-outline-dorado:hover {
            background-color: var(--hu-dorado);
            color: #fff;
        }

        /* ── Alertas flash ── */
        .alert-hu-success {
            background-color: #e6f4ec;
            border-left: 4px solid #2e7d52;
            color: #1d5234;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .alert-hu-error {
            background-color: #fdecea;
            border-left: 4px solid #c62828;
            color: #7f1d1d;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
    </style>
</head>

<body class="font-sans antialiased">

    {{-- Alertas flash globales --}}
    @if (session('success'))
        <div class="alert-hu-success mx-4 mt-3" id="flash-msg" role="alert">
            <span class="material-symbols-outlined" style="font-size:1rem; vertical-align:middle;">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-hu-error mx-4 mt-3" id="flash-msg" role="alert">
            <span class="material-symbols-outlined" style="font-size:1rem; vertical-align:middle;">error</span>
            {{ session('error') }}
        </div>
    @endif

    <div class="min-h-screen" style="background-color: #F4F6F9;">
        @include('layouts.navigation')

        @isset($header)
            <header style="background-color: #fff; border-bottom: 1px solid rgba(0,55,100,0.1);">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bootstrap 5 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS opcionales: cada vista pushea solo lo que necesita --}}
    @stack('vendor-scripts')

    {{-- Auto-ocultar alertas flash --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const flash = document.getElementById('flash-msg');
            if (flash) {
                setTimeout(() => {
                    flash.style.transition = 'opacity 0.5s ease';
                    flash.style.opacity = '0';
                    setTimeout(() => flash.remove(), 500);
                }, 4000);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
