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

        /* ── DataTables: controles y paginación ── */
        .table-responsive {
            overflow: visible !important;
        }

        .dataTables_wrapper {
            width: 100%;
            font-family: 'Montserrat', sans-serif;
            color: var(--hu-texto);
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
            font-size: .75rem;
            color: #718096;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin: 0;
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            min-height: 34px;
            border: 1px solid #dbe3ea !important;
            border-radius: 8px !important;
            background: #fff;
            color: #46576a;
            padding: .35rem .65rem !important;
            font-size: .76rem !important;
            font-family: 'Montserrat', sans-serif;
            box-shadow: none !important;
            outline: none;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #9db5c9 !important;
            box-shadow: 0 0 0 3px rgba(0, 55, 100, .07) !important;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: .55rem;
            font-size: .72rem;
            color: #8290a0;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: .25rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 32px;
            height: 32px;
            margin-left: 4px !important;
            padding: .35rem .6rem !important;
            border: 1px solid transparent !important;
            border-radius: 8px !important;
            background: transparent !important;
            color: #66778a !important;
            font-size: .72rem;
            line-height: 1.3;
            transition: all .15s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover .page-link {
            background: #f8fafc !important;
            border-color: #cbd9e3 !important;
            color: var(--hu-azul) !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            border-color: var(--hu-azul) !important;
            background: var(--hu-azul) !important;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(0, 55, 100, .14);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: .4;
            cursor: default !important;
            background: transparent !important;
            border-color: transparent !important;
        }

        .dataTables_wrapper .dataTables_paginate .ellipsis {
            padding: .35rem .25rem;
            color: #8a98a8;
        }

        .dataTables_wrapper .dataTables_processing {
            border: 1px solid #e3e9ef;
            border-radius: 10px;
            background: rgba(255, 255, 255, .96);
            box-shadow: 0 8px 24px rgba(0, 55, 100, .1);
            color: var(--hu-azul);
        }

        /* ── Tablas modernas ── */
        table.hu-modern-table {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border: 1px solid #e4eaf0 !important;
            border-radius: 10px;
            overflow: hidden;
        }

        table.hu-modern-table thead th {
            background: #f7f9fb !important;
            color: #66778a !important;
            border: 0 !important;
            border-bottom: 1px solid #e4eaf0 !important;
            padding: 10px 14px !important;
            font-size: .71rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        table.hu-modern-table tbody td {
            background: #fff !important;
            color: var(--hu-texto) !important;
            border: 0 !important;
            border-bottom: 1px solid #edf1f5 !important;
            padding: 9px 14px !important;
            font-size: .79rem;
            vertical-align: middle;
        }

        table.hu-modern-table tbody tr:last-child td {
            border-bottom: 0 !important;
        }

        table.hu-modern-table tbody tr:hover td {
            background: #fafcff !important;
            cursor: default;
        }

        .hu-table-section {
            border-top: 1px solid #e8edf2;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
        }

        .hu-table-section-title {
            display: flex;
            align-items: center;
            gap: .45rem;
            margin: 0 0 1rem;
            color: var(--hu-azul);
            font-size: .9rem;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .table-responsive {
                overflow-x: auto !important;
                overflow-y: visible !important;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: left !important;
            }

            .dataTables_wrapper .dataTables_filter {
                margin-top: .75rem;
            }

            .dataTables_wrapper .dataTables_paginate {
                margin-top: .5rem;
            }
        }

        /* ── Paginación Laravel / Bootstrap ── */

        .pagination {
            gap: 4px;
            margin-bottom: 0;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            min-width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: .35rem .65rem;
            border: 1px solid #d8e2ea !important;
            border-radius: 8px !important;

            background: #fff !important;
            color: #66778a !important;

            font-family: 'Montserrat', sans-serif;
            font-size: .72rem;
            font-weight: 600;

            box-shadow: none !important;
            transition: all .15s ease;
        }

        .pagination .page-link:hover {
            background: #f5f8fb !important;
            border-color: #cbd9e3 !important;
            color: var(--hu-azul) !important;
        }

        .pagination .page-item.active .page-link {
            background: var(--hu-azul) !important;
            border-color: var(--hu-azul) !important;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(0, 55, 100, .14) !important;
        }

        .pagination .page-item.disabled .page-link {
            background: #f7f9fb !important;
            border-color: #e4eaf0 !important;
            color: #a5afb8 !important;
            opacity: .75;
        }

        .pagination .page-link:focus {
            box-shadow: 0 0 0 3px rgba(0, 55, 100, .07) !important;
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

        .hu-wizard .btn-hu-gold:disabled,
        .hu-wizard .btn-hu-gold.disabled {
            opacity: .72;
            cursor: not-allowed;
            filter: none;
        }

        /* Modales de mantenimiento — navegación lateral */
        .hu-maintenance-modal {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 24px 70px rgba(0, 55, 100, .20);
            background: #fff;
        }

        .hu-maintenance-modal .maintenance-header {
            min-height: 82px;
            padding: 1.15rem 1.35rem;
            background: #fff;
            border-bottom: 1px solid #e8edf2 !important;
        }

        .hu-maintenance-modal .maintenance-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            background: #edf5fc;
            color: var(--hu-azul);
        }

        .hu-maintenance-modal .maintenance-icon .material-symbols-outlined {
            font-size: 22px;
        }

        .hu-maintenance-modal .maintenance-title {
            color: var(--hu-azul);
            font-size: 1.05rem;
            font-weight: 750;
            line-height: 1.2;
            margin: 0;
        }

        .hu-maintenance-modal .maintenance-subtitle {
            color: #7c8794;
            font-size: .76rem;
            margin-top: .22rem;
        }

        .hu-maintenance-modal .maintenance-body {
            padding: 0;
            overflow: hidden;
        }

        .hu-maintenance-modal .maintenance-layout {
            display: grid;
            grid-template-columns: 205px minmax(0, 1fr);
            min-height: 455px;
        }

        .hu-maintenance-modal .maintenance-sidebar {
            padding: 1rem .75rem;
            background: #f7f9fb;
            border-right: 1px solid #e8edf2;
        }

        .hu-maintenance-modal .maintenance-nav-title {
            padding: .25rem .75rem .65rem;
            color: #98a1ab;
            font-size: .66rem;
            font-weight: 750;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .hu-maintenance-modal .maintenance-nav-link {
            width: 100%;
            display: flex;
            align-items: center;
            gap: .7rem;
            position: relative;
            padding: .72rem .7rem;
            margin-bottom: .28rem;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #52606d;
            text-align: left;
            transition: background .16s ease, color .16s ease, transform .16s ease;
        }

        .hu-maintenance-modal .maintenance-nav-link:hover {
            background: #eef4f9;
            color: var(--hu-azul);
        }

        .hu-maintenance-modal .maintenance-nav-link.active {
            background: #eaf3fb;
            color: var(--hu-azul);
        }

        .hu-maintenance-modal .maintenance-nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 9px;
            bottom: 9px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--hu-azul);
        }

        .hu-maintenance-modal .maintenance-nav-link>.material-symbols-outlined {
            flex: 0 0 22px;
            font-size: 20px;
        }

        .hu-maintenance-modal .maintenance-nav-link span:last-child {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: .08rem;
        }

        .hu-maintenance-modal .maintenance-nav-link strong {
            font-size: .76rem;
            font-weight: 750;
            line-height: 1.15;
        }

        .hu-maintenance-modal .maintenance-nav-link small {
            color: #8b96a2;
            font-size: .64rem;
            line-height: 1.2;
        }

        .hu-maintenance-modal .maintenance-nav-link.active small {
            color: #66809a;
        }

        .hu-maintenance-modal .maintenance-content {
            min-width: 0;
            padding: .75rem 1rem 0;
            background: #fff;
            overflow: hidden;
        }

        .hu-maintenance-modal .maintenance-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .78rem .9rem;
            margin-bottom: .95rem;
            border: 1px solid #dceaf6;
            border-radius: 11px;
            background: #f1f7fc;
        }

        .hu-maintenance-modal .maintenance-status-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            background: #fff;
            color: var(--hu-azul);
            border: 1px solid #dceaf6;
        }

        .hu-maintenance-modal .maintenance-status-icon .material-symbols-outlined {
            font-size: 21px;
        }

        .hu-maintenance-modal .maintenance-status-title {
            color: var(--hu-azul);
            font-size: .79rem;
            font-weight: 750;
        }

        .hu-maintenance-modal .maintenance-status-text {
            margin-top: .12rem;
            color: #7d8995;
            font-size: .66rem;
        }

        .hu-maintenance-modal .maintenance-status-action {
            display: inline-flex;
            align-items: center;
            gap: .48rem;
            flex: 0 0 auto;
            padding: .48rem .68rem;
            border: 1px solid #cbddea;
            border-radius: 8px;
            background: #fff;
            color: var(--hu-azul);
            font-size: .7rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
        }

        .hu-maintenance-modal .maintenance-status-action .form-check-input {
            cursor: pointer;
        }

        .hu-maintenance-modal .maintenance-tab-content {
            height: 390px;
            max-width: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            padding-right: .15rem;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .hu-maintenance-modal .maintenance-tab-content::-webkit-scrollbar {
            display: none;
        }

        .hu-maintenance-modal .maintenance-tab-content>.tab-pane {
            min-width: 0;
            width: 100%;
        }

        .hu-maintenance-modal .maintenance-section {
            min-width: 0;
        }

        .hu-maintenance-modal .maintenance-section .row {
            --bs-gutter-y: .65rem;
        }

        .hu-maintenance-modal .maintenance-section .form-control,
        .hu-maintenance-modal .maintenance-section .form-select {
            max-width: 100%;
            min-width: 0;
        }

        .hu-maintenance-modal .maintenance-section .input-group {
            min-width: 0;
            max-width: 100%;
        }

        .hu-maintenance-modal .maintenance-section {
            padding: .1rem .05rem .65rem;
        }

        .hu-maintenance-modal .maintenance-section>.fw-bold.mb-3 {
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: .65rem !important;
            padding-bottom: .55rem;
            border-bottom: 1px solid #eef1f4;
            color: #263746 !important;
            font-size: .92rem !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
        }

        .hu-maintenance-modal .maintenance-section>.fw-bold.mb-3 .material-symbols-outlined {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #f0f6fb;
            color: var(--hu-azul);
            font-size: 18px !important;
            margin-right: 0 !important;
        }

        .hu-maintenance-modal .maintenance-section-heading {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding-bottom: .85rem;
            margin-bottom: .95rem;
            border-bottom: 1px solid #eef1f4;
        }

        .hu-maintenance-modal .maintenance-section-heading>.material-symbols-outlined {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #f0f6fb;
            color: var(--hu-azul);
            font-size: 18px;
        }

        .hu-maintenance-modal .maintenance-section-heading h6 {
            margin: 0;
            color: #263746;
            font-size: .92rem;
            font-weight: 750;
        }

        .hu-maintenance-modal .maintenance-section-heading p {
            margin: .12rem 0 0;
            color: #8a949e;
            font-size: .68rem;
        }

        .hu-maintenance-modal .maintenance-section>.row,
        .hu-maintenance-modal .maintenance-section>.mb-3,
        .hu-maintenance-modal .maintenance-section>.p-3,
        .hu-maintenance-modal .maintenance-section>hr {
            margin-bottom: .85rem !important;
        }

        .hu-maintenance-modal .maintenance-section .form-label {
            color: #596571;
        }

        .hu-maintenance-modal .maintenance-toggle {
            border: 1px solid #f1dfb2;
            border-radius: 10px;
            background: #fffaf0;
            padding: .78rem !important;
        }

        .hu-maintenance-modal .maintenance-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .6rem;
            min-height: 66px;
            padding: .75rem 1.15rem;
            border-top: 1px solid #e8edf2;
            background: #fff;
        }

        .hu-maintenance-modal .maintenance-footer .btn-hu {
            min-width: 150px;
        }

        .hu-maintenance-modal .maintenance-add-btn {
            width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px !important;
            flex: 0 0 40px;
        }

        .hu-maintenance-modal .maintenance-add-btn .material-symbols-outlined,
        .hu-maintenance-modal .maintenance-remove-btn .material-symbols-outlined {
            font-size: 18px;
        }

        .hu-maintenance-modal .maintenance-remove-btn {
            flex: 0 0 40px;
            color: #b42318;
            border-color: #d0d7de;
        }

        .hu-maintenance-modal .maintenance-remove-btn:hover {
            color: #b42318;
            background: #fef2f2;
            border-color: #f0b7b2;
        }

        .hu-maintenance-modal .input-group>.form-control,
        .hu-maintenance-modal .input-group>.form-select {
            min-width: 0;
        }

        @media (max-width: 767px) {
            .hu-maintenance-modal .maintenance-header {
                padding: .95rem 1rem;
            }

            .hu-maintenance-modal .maintenance-layout {
                display: block;
                min-height: 0;
            }

            .hu-maintenance-modal .maintenance-sidebar {
                display: flex;
                gap: .35rem;
                overflow-x: auto;
                padding: .65rem;
                border-right: 0;
                border-bottom: 1px solid #e8edf2;
            }

            .hu-maintenance-modal .maintenance-nav-title {
                display: none;
            }

            .hu-maintenance-modal .maintenance-nav-link {
                width: auto;
                flex: 0 0 auto;
                margin: 0;
                padding: .55rem .7rem;
            }

            .hu-maintenance-modal .maintenance-nav-link small {
                display: none;
            }

            .hu-maintenance-modal .maintenance-nav-link span:last-child {
                display: block;
            }

            .hu-maintenance-modal .maintenance-nav-link strong {
                font-size: .7rem;
            }

            .hu-maintenance-modal .maintenance-content {
                padding: .7rem .8rem 0;
            }

            .hu-maintenance-modal .maintenance-status {
                align-items: flex-start;
                flex-direction: column;
            }

            .hu-maintenance-modal .maintenance-status-action {
                width: 100%;
            }

            .hu-maintenance-modal .maintenance-tab-content {
                height: auto;
                max-height: 52vh;
                overflow-x: hidden;
                overflow-y: auto;
                scrollbar-width: none;
            }

            .hu-maintenance-modal .maintenance-tab-content::-webkit-scrollbar {
                display: none;
            }

            .hu-maintenance-modal .maintenance-footer {
                padding: .7rem .9rem;
            }
        }

        @media (max-width: 767px) {
            .hu-maintenance-modal .maintenance-header {
                padding: 1rem 1rem .8rem;
            }

            .hu-maintenance-modal .maintenance-body {
                padding: .9rem 1rem .4rem;
                max-height: 70vh;
            }

            .hu-maintenance-modal .maintenance-footer {
                padding: .65rem 1rem 1rem;
            }
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

        .maintenance-component-danger-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #f1b8b5;
            background: #fff7f6;
            color: #b42318;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: .74rem;
            font-weight: 600;
            transition: .15s ease;
        }

        .maintenance-component-danger-btn:hover {
            background: #fef0ef;
            border-color: #e99b96;
        }

        .maintenance-component-danger-btn.is-active {
            background: #b42318;
            border-color: #b42318;
            color: #fff;
        }

        .maintenance-component-danger-btn .material-symbols-outlined {
            font-size: 17px;
        }

        .maintenance-component-warning {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            padding: 9px 10px;
            border: 1px solid #f3d19c;
            background: #fff9ed;
            color: #8a5a00;
            border-radius: 8px;
            font-size: .72rem;
            line-height: 1.35;
        }

        .maintenance-component-warning .material-symbols-outlined {
            font-size: 17px;
            flex: 0 0 auto;
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

    {{-- Configuración común de DataTables --}}
    <script>
        if (window.jQuery && $.fn.DataTable) {
            $.extend(true, $.fn.dataTable.defaults, {
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'Todos']
                ],
                pagingType: 'simple_numbers',
                language: {
                    emptyTable: 'No hay datos disponibles.',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_',
                    infoEmpty: 'Mostrando 0 a 0 de 0',
                    infoFiltered: '(filtrado de _MAX_ registros)',
                    lengthMenu: 'Mostrar _MENU_',
                    loadingRecords: 'Cargando...',
                    processing: 'Procesando...',
                    search: 'Buscar:',
                    zeroRecords: 'No se encontraron registros coincidentes.',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });
        }
    </script>

    {{-- Comportamiento compartido de los modales de mantenimiento:
         cambia de pestaña y panel sin duplicar lógica en cada pantalla. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.hu-maintenance-modal').forEach(function(modal) {
                const buttons = modal.querySelectorAll('.maintenance-nav-link[data-bs-target]');
                const panes = modal.querySelectorAll('.maintenance-tab-content > .tab-pane');

                buttons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const targetSelector = button.getAttribute('data-bs-target');
                        if (!targetSelector) return;

                        const target = modal.querySelector(targetSelector);
                        if (!target) return;

                        buttons.forEach(function(item) {
                            item.classList.remove('active');
                            item.setAttribute('aria-selected', 'false');
                        });

                        panes.forEach(function(pane) {
                            pane.classList.remove('show', 'active');
                        });

                        button.classList.add('active');
                        button.setAttribute('aria-selected', 'true');
                        target.classList.add('active');

                        // Permite que la transición .fade de Bootstrap se ejecute
                        // incluso cuando cambiamos de sección manualmente.
                        requestAnimationFrame(function() {
                            target.classList.add('show');
                        });
                    });
                });
            });
        });
    </script>

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
