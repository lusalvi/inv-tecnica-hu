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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" />

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Estilos compilados (Tailwind + app.css) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS opcionales: cada vista pushea solo lo que necesita --}}
    @stack('styles')

    <style>
        /* ── Alpine: ocultar hasta inicialización ── */
        [x-cloak] {
            display: none !important;
        }

        /* ── Variables institucionales ── */
        :root {
            --hu-azul: #003764;
            --hu-dorado: #C7A36E;
            --hu-texto: #59595B;
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
        table.dataTable tbody tr:hover>td {
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

        /* Wizard de alta de PC — identidad visual Hospital Universitario */
        .hu-wizard {
            font-family: 'Montserrat', sans-serif;
        }

        .hu-wizard .modal-content {
            border-radius: 12px;
            border: 0;
            box-shadow: 0 12px 40px rgba(0, 55, 100, .16);
        }

        .hu-wizard .wizard-stepper {
            display: flex;
            align-items: flex-start;
            gap: 0;
        }

        .hu-wizard .wizard-step {
            flex: 1;
            position: relative;
            text-align: center;
            min-width: 0;
        }

        .hu-wizard .wizard-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            left: calc(50% + 17px);
            right: calc(-50% + 17px);
            height: 2px;
            background: #dfe5ea;
            z-index: 0;
        }

        .hu-wizard .wizard-step.is-complete:not(:last-child)::after,
        .hu-wizard .wizard-step.is-active:not(:last-child)::after {
            background: var(--hu-azul);
        }

        .hu-wizard .wizard-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin: 0 auto 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            background: #eef1f4;
            color: #8a949d;
            font-size: .76rem;
            font-weight: 700;
        }

        .hu-wizard .wizard-step.is-active .wizard-dot {
            background: var(--hu-azul);
            color: #fff;
        }

        .hu-wizard .wizard-step.is-complete .wizard-dot {
            background: var(--hu-azul);
            color: #fff;
        }

        .hu-wizard .wizard-label {
            display: block;
            font-size: .68rem;
            line-height: 1.2;
            color: #7c8790;
            font-weight: 600;
        }

        .hu-wizard .wizard-step.is-active .wizard-label,
        .hu-wizard .wizard-step.is-complete .wizard-label {
            color: var(--hu-azul);
        }

        .hu-wizard .wizard-panel {
            border: 1px solid #e5e9ed;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
        }

        .hu-wizard .wizard-panel-title {
            color: var(--hu-azul);
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .hu-wizard .wizard-panel-help {
            color: #737d85;
            font-size: .76rem;
            margin-bottom: 18px;
        }

        .hu-wizard .location-choice {
            border: 1.5px solid #dfe5ea;
            border-radius: 12px;
            padding: 14px;
            cursor: pointer;
            transition: .18s ease;
            height: 100%;
        }

        .hu-wizard .location-choice:hover {
            border-color: var(--hu-azul);
            background: #fbfdff;
        }

        .hu-wizard .location-choice.is-selected {
            border-color: var(--hu-azul);
            box-shadow: 0 0 0 2px rgba(0, 55, 100, .08);
            background: #f7fbff;
        }

        .hu-wizard .location-choice .material-symbols-outlined {
            color: var(--hu-azul);
            font-size: 22px;
        }

        .hu-wizard .location-choice-title {
            color: #27333d;
            font-weight: 700;
            font-size: .82rem;
        }

        .hu-wizard .location-choice-help {
            color: #7a858d;
            font-size: .72rem;
            line-height: 1.35;
        }

        .hu-wizard .comp-card {
            border: 1px solid #e5e9ed;
            border-radius: 10px;
            padding: 13px;
            background: #fff;
        }

        .hu-wizard .hu-component-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .hu-wizard .hu-component-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .hu-wizard .hu-component-row {
            min-height: 58px;
            display: grid;
            grid-template-columns: 38px minmax(0, 1fr) minmax(190px, 42%);
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border: 1px solid #e5e9ed;
            border-radius: 10px;
            background: #fff;
        }

        .hu-wizard .hu-component-row:hover {
            border-color: #cbd9e3;
        }

        .hu-wizard .hu-component-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f5c67;
        }

        .hu-wizard .hu-component-icon .material-symbols-outlined {
            font-size: 25px;
        }

        .hu-wizard .hu-component-label {
            min-width: 0;
            color: #27333d;
            font-size: .8rem;
            font-weight: 700;
        }

        .hu-wizard .hu-component-label small {
            display: block;
            color: #7a858d;
            font-size: .67rem;
            font-weight: 500;
            margin-top: 1px;
        }

        .hu-wizard .hu-required {
            color: #c0392b;
        }

        .hu-wizard .hu-component-selected {
            color: #7b858d;
            font-size: .67rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        .hu-wizard .hu-component-selected.is-selected {
            color: var(--hu-azul);
            font-weight: 600;
        }

        .hu-wizard .hu-component-action {
            min-width: 0;
        }

        .hu-wizard .hu-component-open {
            width: 100%;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 1.5px solid #9cc8ff;
            border-radius: 7px;
            background: #fff;
            color: #1478e8;
            font-family: 'Montserrat', sans-serif;
            font-size: .76rem;
            font-weight: 700;
            transition: .16s ease;
        }

        .hu-wizard .hu-component-open:hover {
            background: #f5faff;
            border-color: #1478e8;
        }

        .hu-wizard .hu-component-open .material-symbols-outlined {
            font-size: 19px;
        }

        .hu-wizard .component-hidden-field {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        .hu-wizard .hu-add-multi {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            border: 0;
            background: transparent;
            color: var(--hu-azul);
            font-size: .72rem;
            font-weight: 700;
            padding: 2px 8px 2px 45px;
        }

        .hu-wizard .hu-add-multi:hover {
            text-decoration: underline;
        }

        .hu-wizard .hu-add-multi .material-symbols-outlined {
            font-size: 17px;
        }

        .hu-wizard .hu-multi-remove {
            width: 28px;
            height: 28px;
            border: 0;
            background: transparent;
            color: #8a949d;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .hu-wizard .hu-multi-remove:hover {
            background: #f3f4f5;
            color: #b02a37;
        }

        .hu-wizard .hu-multi-remove .material-symbols-outlined {
            font-size: 17px;
        }

        .hu-wizard .hu-component-detail-panel {
            padding: 16px;
        }

        .hu-wizard .hu-component-back {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            border: 0;
            background: transparent;
            padding: 0;
            margin-bottom: 10px;
            color: #66727c;
            font-family: 'Montserrat', sans-serif;
            font-size: .72rem;
            font-weight: 600;
        }

        .hu-wizard .hu-component-back:hover {
            color: var(--hu-azul);
        }

        .hu-wizard .hu-component-back .material-symbols-outlined {
            font-size: 17px;
        }

        .hu-wizard .hu-component-detail-heading {
            margin-bottom: 13px;
        }

        .hu-wizard .hu-component-tabs {
            display: flex;
            border-bottom: 1px solid #e1e6ea;
            margin-bottom: 14px;
        }

        .hu-wizard .hu-component-tab {
            flex: 1;
            border: 0;
            border-bottom: 2px solid transparent;
            background: transparent;
            color: #7a858d;
            padding: 9px 8px 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: .72rem;
            font-weight: 600;
        }

        .hu-wizard .hu-component-tab:hover {
            color: var(--hu-azul);
        }

        .hu-wizard .hu-component-tab.is-active {
            color: #1478e8;
            border-bottom-color: #1478e8;
        }

        .hu-wizard .hu-component-search-wrap {
            position: relative;
            margin-bottom: 10px;
        }

        .hu-wizard .hu-component-search-wrap .material-symbols-outlined {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b969f;
            font-size: 19px;
            pointer-events: none;
        }

        .hu-wizard .hu-component-search-wrap .form-control {
            padding-left: 35px;
            font-size: .76rem;
        }

        .hu-wizard .hu-component-table-wrap {
            max-height: 300px;
            overflow: auto;
            border: 1px solid #e5e9ed;
            border-radius: 8px;
        }

        .hu-wizard .hu-component-table {
            font-size: .7rem;
        }

        .hu-wizard .hu-component-table thead th {
            background: #f7f9fa;
            color: #596670;
            font-weight: 700;
            border-bottom: 1px solid #e1e6ea;
            white-space: nowrap;
        }

        .hu-wizard .hu-component-table td {
            color: #35424d;
            border-color: #edf0f2;
        }

        .hu-wizard .hu-component-table tr.hu-stock-row {
            cursor: pointer;
        }

        .hu-wizard .hu-component-table tr.hu-stock-row:hover {
            background: #f7fbff;
        }

        .hu-wizard .hu-component-table tr.hu-stock-row.is-selected {
            background: #eef6ff;
        }

        .hu-wizard .hu-stock-radio {
            width: 17px;
            height: 17px;
            accent-color: #1478e8;
        }

        .hu-wizard .hu-stock-name {
            font-weight: 700;
        }

        .hu-wizard .hu-stock-meta {
            color: #7a858d;
            font-size: .64rem;
        }

        .hu-wizard .hu-component-info {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            padding: 10px 12px;
            border: 1px solid #b9d8ff;
            border-radius: 8px;
            background: #f2f8ff;
            color: #285273;
            font-size: .69rem;
            line-height: 1.45;
        }

        .hu-wizard .hu-component-info .material-symbols-outlined {
            color: #1478e8;
            font-size: 18px;
        }

        .hu-wizard .hu-component-warning {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 13px;
            border: 1px solid #f2c77a;
            border-radius: 8px;
            background: #fff7e8;
            color: #7a5b00;
            font-size: .7rem;
            line-height: 1.45;
        }

        .hu-wizard .hu-component-warning .material-symbols-outlined {
            color: #e58b00;
            font-size: 20px;
        }

        .hu-wizard .hu-component-detail-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            border-top: 1px solid #edf0f2;
            margin-top: 16px;
            padding-top: 13px;
        }

        @media (max-width: 767px) {
            .hu-wizard .hu-component-row {
                grid-template-columns: 34px minmax(0, 1fr);
            }

            .hu-wizard .hu-component-action {
                grid-column: 2;
            }

            .hu-wizard .hu-add-multi {
                padding-left: 39px;
            }

            .hu-wizard .hu-component-table-wrap {
                max-height: 260px;
            }
        }

        .hu-wizard .comp-card-title {
            font-size: .8rem;
            font-weight: 700;
            color: #27333d;
        }

        .hu-wizard .comp-card .form-control {
            font-size: .78rem;
        }

        .hu-wizard .hu-summary-section {
            border: 1px solid #e5e9ed;
            border-radius: 10px;
            padding: 14px;
        }

        .hu-wizard .hu-summary-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #7b858d;
            font-weight: 700;
        }

        .hu-wizard .hu-summary-value {
            font-size: .82rem;
            color: #27333d;
            font-weight: 600;
        }

        .hu-wizard .hu-summary-component {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #edf0f2;
        }

        .hu-wizard .hu-summary-component:last-child {
            border-bottom: 0;
        }

        .hu-wizard .hu-pill {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
            align-self: flex-start;
            white-space: nowrap;
            border-radius: 6px;
            padding: 3px 10px;
            line-height: 1.3;
            border: 1px solid #d6e3ec;
            background: #f0f5f8;
            color: var(--hu-azul);
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .01em;
        }

        .hu-wizard .hu-pill--stock {
            background: #eaf2f8;
            border-color: #cfe0ee;
            color: var(--hu-azul);
        }

        .hu-wizard .hu-pill--sin-stock {
            background: #fdf5e4;
            border-color: #f1dfb2;
            color: #8a6410;
        }

        .hu-wizard .hu-pill--registrar {
            background: #e9f6ee;
            border-color: #c6e6d2;
            color: #1f6b3d;
        }

        .hu-wizard .hu-pill--no-identificada {
            background: #f1f3f5;
            border-color: #dde1e5;
            color: #5d6770;
        }


        .hu-wizard .btn-hu-confirm {
            border-radius: 999px;
            background: var(--hu-azul);
            border: 1px solid var(--hu-azul);
            color: #fff;
            font-weight: 600;
        }

        .hu-wizard .btn-hu-confirm:hover {
            background: #002d52;
            border-color: #002d52;
            color: #fff;
        }

        .hu-wizard .btn-hu-gold {
            border-radius: 999px;
            background: var(--hu-dorado);
            border: 1px solid var(--hu-dorado);
            color: #fff;
            font-weight: 600;
        }

        .hu-wizard .btn-hu-gold:hover {
            filter: brightness(.94);
            color: #fff;
        }

        @media (max-width: 767px) {
            .hu-wizard .wizard-label {
                font-size: .6rem;
            }

            .hu-wizard .wizard-panel {
                padding: 14px;
            }

            .hu-wizard .wizard-step:not(:last-child)::after {
                left: calc(50% + 16px);
                right: calc(-50% + 16px);
            }
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
        document.addEventListener('DOMContentLoaded', function() {
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
