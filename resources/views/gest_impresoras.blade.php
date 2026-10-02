<?php use App\Models\ComponenteModel; ?>

<x-app-layout>
    @php
        $rolActual = data_get(Auth::user(), 'rol.nombre');
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-lg leading-tight" style="color: var(--hu-azul);">
            Impresoras
            <button type="button" class="btn btn-hu-outline-dorado btn-sm ms-2" data-bs-toggle="modal"
                data-bs-target="#infoModal" style="padding: 2px 8px; font-size:.78rem;">
                <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">info</span>
            </button>
        </h2>
    </x-slot>

    {{-- Modal info general --}}
    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Impresoras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    Aquí se agregan las impresoras y se asignan a un área o depósito.
                </div>
            </div>
        </div>
    </div>

    {{-- Modal detalle de impresora --}}
    <div class="modal fade" id="infoImpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Información de la impresora</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <dl class="row g-2 mb-0" style="font-size:.88rem;">
                        <dt class="col-5 text-muted fw-semibold">Nº Inventario</dt>
                        <dd class="col-7 mb-1 fw-semibold" id="idenInfo" style="color:var(--hu-texto);"></dd>
                        <dt class="col-5 text-muted fw-semibold">Nombre</dt>
                        <dd class="col-7 mb-1" id="nombreInfo"></dd>
                        <dt class="col-5 text-muted fw-semibold">Marca y Modelo</dt>
                        <dd class="col-7 mb-1" id="marcaInfo"></dd>
                        <dt class="col-5 text-muted fw-semibold">IP</dt>
                        <dd class="col-7 mb-1" id="ipInfo"></dd>
                        <dt class="col-5 text-muted fw-semibold" id="titleAsig"></dt>
                        <dd class="col-7 mb-1" id="infoAsig"></dd>
                        <dt class="col-5 text-muted fw-semibold">Tóner</dt>
                        <dd class="col-7 mb-1" id="tonerInfo"></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal: Cargar impresora (wizard 4 pasos) --}}
    {{-- Modal: Cargar impresora (wizard 4 pasos) --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content hu-wizard"
                style="border-radius:12px;border:none;box-shadow:0 12px 40px rgba(0,55,100,.16);">
                <div class="modal-header border-0 pb-0">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="modal-title fw-semibold mb-1" style="color:var(--hu-azul);">Cargar impresora
                                </h5>
                                <div class="text-muted" style="font-size:.72rem;">Completá los datos para registrar la
                                    impresora.</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        {{-- Stepper --}}
                        <div class="wizard-stepper" aria-label="Progreso del alta de impresora">
                            <div class="wizard-step is-active" data-imp-step="1">
                                <div class="wizard-dot">1</div>
                                <span class="wizard-label">Datos generales</span>
                            </div>
                            <div class="wizard-step" data-imp-step="2">
                                <div class="wizard-dot">2</div>
                                <span class="wizard-label">Ubicación</span>
                            </div>
                            <div class="wizard-step" data-imp-step="3">
                                <div class="wizard-dot">3</div>
                                <span class="wizard-label">Tóner</span>
                            </div>
                            <div class="wizard-step" data-imp-step="4">
                                <div class="wizard-dot">4</div>
                                <span class="wizard-label">Resumen</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <form action="{{ route('store_impresoras') }}" method="POST" id="addImpForm"
                        style="display:flex;flex-direction:column;gap:20px;">
                        @csrf
                        <input type="hidden" name="addEnUso" id="addImpEnUso" value="0">

                        {{-- ── PASO 1: Datos generales ── --}}
                        <div id="imp-step-1">
                            <div class="wizard-panel">
                                <div class="wizard-panel-title">Datos generales</div>
                                <div class="wizard-panel-help">Completá la información básica de la impresora.</div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-3">
                                        <label for="addImpIdentificador" class="form-label fw-semibold"
                                            style="font-size:.78rem;">
                                            Nº de inventario <span style="color:#b02a37;">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('addIdentificador') is-invalid @enderror"
                                            id="addImpIdentificador" name="addIdentificador"
                                            placeholder="IMP-2025-001" required>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="addImpNombre" class="form-label fw-semibold"
                                            style="font-size:.78rem;">
                                            Nombre <span style="color:#b02a37;">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('addNombre') is-invalid @enderror"
                                            id="addImpNombre" name="addNombre" placeholder="Impresora Farmacia 01"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="addImpMarca" class="form-label fw-semibold"
                                            style="font-size:.78rem;">
                                            Marca y Modelo <span style="color:#b02a37;">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('addMarca') is-invalid @enderror"
                                            id="addImpMarca" name="addMarca" placeholder="HP LaserJet M404dn"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="addImpIp" class="form-label fw-semibold"
                                            style="font-size:.78rem;">
                                            IP <span style="color:#b02a37;">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('addIp') is-invalid @enderror" id="addImpIp"
                                            name="addIp" placeholder="192.168.1.50" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── PASO 2: Ubicación ── --}}
                        <div id="imp-step-2" style="display:none;">
                            <div class="wizard-panel">
                                <div class="wizard-panel-title">Ubicación</div>
                                <div class="wizard-panel-help">Indicá si la impresora está instalada en un área o
                                    guardada en un depósito.</div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label class="location-choice d-block is-selected" id="imp-location-use-card">
                                            <input type="radio" name="imp_ubicacion" value="uso"
                                                id="imp-location-use" class="visually-hidden" checked>
                                            <div class="d-flex gap-2 align-items-start">
                                                <span class="material-symbols-outlined">domain</span>
                                                <div>
                                                    <div class="location-choice-title">Está en uso</div>
                                                    <div class="location-choice-help">Asignala a un área de la
                                                        institución.</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="location-choice d-block" id="imp-location-storage-card">
                                            <input type="radio" name="imp_ubicacion" value="deposito"
                                                id="imp-location-storage" class="visually-hidden">
                                            <div class="d-flex gap-2 align-items-start">
                                                <span class="material-symbols-outlined">inventory_2</span>
                                                <div>
                                                    <div class="location-choice-title">No está en uso</div>
                                                    <div class="location-choice-help">Asignala a un depósito para
                                                        indicar dónde queda físicamente.</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div id="imp-location-area-panel">
                                    <label for="addImpArea" class="form-label fw-semibold" style="font-size:.78rem;">
                                        Área <span style="color:#b02a37;">*</span>
                                    </label>
                                    <select class="form-control @error('addArea') is-invalid @enderror"
                                        id="addImpArea" name="addArea">
                                        <option value="" selected>Seleccioná un área</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2" id="addImpNroConsul_div" style="display:none;">
                                        <label for="addImpNroConsul" class="form-label fw-semibold"
                                            style="font-size:.78rem;">Nº de consultorio</label>
                                        <input type="text"
                                            class="form-control @error('addNroConsul') is-invalid @enderror"
                                            id="addImpNroConsul" name="addNroConsul" placeholder="Nº de consultorio">
                                    </div>
                                </div>

                                <div id="imp-location-storage-panel" style="display:none;">
                                    <label for="addImpDeposito" class="form-label fw-semibold"
                                        style="font-size:.78rem;">
                                        Depósito <span style="color:#b02a37;">*</span>
                                    </label>
                                    <select class="form-control @error('addDeposito') is-invalid @enderror"
                                        id="addImpDeposito" name="addDeposito">
                                        <option value="" selected>Seleccioná un depósito</option>
                                        @foreach ($depositos as $deposito)
                                            <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ── PASO 3: Tóner ── --}}
                        <div id="imp-step-3" style="display:none;">
                            {{-- Campos ocultos del tóner --}}
                            <input type="hidden" name="addToner_modo" id="addTonerModo" value="stock">
                            <select name="addToner" id="addTonerSelect" class="component-hidden-field"
                                style="display:none;">
                                <option value=""></option>
                                @foreach ($toners as $toner)
                                    <option value="{{ $toner->id }}" data-stock="{{ $toner->stock }}"
                                        data-estado="{{ $toner->estado_id }}"
                                        data-deposito="{{ $toner->deposito->nombre ?? 'Sin depósito' }}">
                                        {{ $toner->nombre . ' — ' . ($toner->deposito->nombre ?? 'sin depósito') }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="addToner_nombre" id="addTonerNombre" value="">
                            <select name="addToner_deposito_origen" id="addTonerDepositoOrigen"
                                class="component-hidden-field" style="display:none;">
                                <option value="">Sin depósito de origen</option>
                                @foreach ($depositos as $deposito)
                                    <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="addToner_cantidad" id="addTonerCantidad" value="1">
                            <input type="hidden" name="addToner_motivo" id="addTonerMotivo" value="">
                            <input type="hidden" name="addToner_observaciones" id="addTonerObservaciones"
                                value="">

                            {{-- Vista lista (selección de modo) --}}
                            <div id="imp-toner-list-view">
                                <div class="wizard-panel">
                                    <div class="wizard-panel-title">Tóner</div>
                                    <div class="wizard-panel-help">Seleccioná el tóner instalado en la impresora. Podés
                                        elegirlo desde el stock, registrar uno nuevo o marcarlo como no identificado.
                                    </div>

                                    <div class="hu-component-list">
                                        <div class="hu-component-row" id="imp-toner-row">
                                            <div class="hu-component-icon">
                                                <span class="material-symbols-outlined">ink_eraser</span>
                                            </div>
                                            <div class="hu-component-label">
                                                <div>Tóner <span class="hu-required">*</span></div>
                                                <div class="hu-component-selected" id="imp-toner-selection-label">Sin
                                                    seleccionar</div>
                                            </div>
                                            <div class="hu-component-action">
                                                <button type="button" class="hu-component-open"
                                                    id="imp-toner-open-btn">
                                                    <span class="material-symbols-outlined">add_circle</span>
                                                    <span id="imp-toner-open-label">Seleccionar tóner</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Vista detalle (selector de tóner) --}}
                            <div id="imp-toner-detail-view" style="display:none;">
                                <div class="wizard-panel hu-component-detail-panel">
                                    <button type="button" class="hu-component-back" id="imp-toner-back">
                                        <span class="material-symbols-outlined">arrow_back</span> Tóner
                                    </button>
                                    <div class="hu-component-detail-heading">
                                        <div class="wizard-panel-title" id="imp-toner-detail-title">Seleccionar tóner
                                        </div>
                                        <div class="wizard-panel-help mb-0">Tóner</div>
                                    </div>

                                    <div class="hu-component-tabs" role="tablist">
                                        <button type="button" class="hu-component-tab is-active"
                                            data-toner-mode="stock">Desde stock</button>
                                        <button type="button" class="hu-component-tab"
                                            data-toner-mode="registrar">Registrar nuevo</button>
                                        <button type="button" class="hu-component-tab"
                                            data-toner-mode="no-identificado">No identificado</button>
                                    </div>

                                    <div class="hu-component-detail-content">

                                        {{-- Panel: Desde stock --}}
                                        <div class="hu-detail-panel" data-toner-panel="stock">
                                            <div class="hu-component-search-wrap">
                                                <span class="material-symbols-outlined">search</span>
                                                <input type="search" id="imp-toner-search" class="form-control"
                                                    placeholder="Buscar por tóner o depósito..." autocomplete="off">
                                            </div>
                                            <div class="table-responsive hu-component-table-wrap">
                                                <table class="table table-sm align-middle mb-0 hu-component-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:42px;"></th>
                                                            <th>Tóner</th>
                                                            <th>Depósito</th>
                                                            <th class="text-end">Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="imp-toner-stock-body"></tbody>
                                                </table>
                                            </div>
                                            <div id="imp-toner-empty" class="text-muted text-center py-4"
                                                style="display:none;font-size:.78rem;">No hay tóners disponibles para
                                                mostrar.</div>

                                            {{-- Panel de reposición (sin stock) --}}
                                            <div id="imp-toner-replenishment" style="display:none;" class="mt-3">
                                                <div class="hu-component-info">
                                                    <span class="material-symbols-outlined">inventory</span>
                                                    <div>
                                                        <strong>Este tóner está sin stock.</strong>
                                                        <div>Para utilizarlo en esta impresora primero se debe ingresar
                                                            stock.</div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1">
                                                    <div class="col-12 col-md-6">
                                                        <label for="imp-toner-quantity" class="form-label fw-semibold"
                                                            style="font-size:.78rem;">
                                                            Cantidad a ingresar <span class="hu-required">*</span>
                                                        </label>
                                                        <input type="number" id="imp-toner-quantity"
                                                            class="form-control" min="1" step="1"
                                                            value="1">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label for="imp-toner-reason" class="form-label fw-semibold"
                                                            style="font-size:.78rem;">
                                                            Motivo <span class="hu-required">*</span>
                                                        </label>
                                                        <input type="text" id="imp-toner-reason"
                                                            class="form-control" placeholder="Ej. Tóner recibido">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Panel: Registrar nuevo --}}
                                        <div class="hu-detail-panel" data-toner-panel="registrar"
                                            style="display:none;">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="imp-toner-new-name" class="form-label fw-semibold"
                                                        style="font-size:.78rem;">
                                                        Nombre del tóner <span class="hu-required">*</span>
                                                    </label>
                                                    <input type="text" id="imp-toner-new-name"
                                                        class="form-control" placeholder="Ej. HP CF258A">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="imp-toner-new-origin" class="form-label fw-semibold"
                                                        style="font-size:.78rem;">
                                                        Depósito de origen <span class="hu-required">*</span>
                                                    </label>
                                                    <select id="imp-toner-new-origin" class="form-control">
                                                        <option value="">Seleccioná un depósito</option>
                                                        @foreach ($depositos as $deposito)
                                                            <option value="{{ $deposito->id }}">
                                                                {{ $deposito->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="imp-toner-new-quantity" class="form-label fw-semibold"
                                                        style="font-size:.78rem;">
                                                        Cantidad <span class="hu-required">*</span>
                                                    </label>
                                                    <input type="number" id="imp-toner-new-quantity"
                                                        class="form-control" min="1" step="1"
                                                        value="1">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="imp-toner-new-reason" class="form-label fw-semibold"
                                                        style="font-size:.78rem;">
                                                        Motivo <span class="hu-required">*</span>
                                                    </label>
                                                    <input type="text" id="imp-toner-new-reason"
                                                        class="form-control" placeholder="Ej. Tóner recibido">
                                                </div>
                                            </div>
                                            <div class="hu-component-info mt-3">
                                                <span class="material-symbols-outlined">info</span>
                                                <span>
                                                    El tóner se registrará como <strong>En uso</strong> y se asignará
                                                    directamente a esta impresora.
                                                    El depósito indicado se guarda como origen.
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Panel: No identificado --}}
                                        <div class="hu-detail-panel" data-toner-panel="no-identificado"
                                            style="display:none;">
                                            <div class="hu-component-warning">
                                                <span class="material-symbols-outlined">warning</span>
                                                <div>
                                                    <strong>El tóner no está identificado</strong>
                                                    <div>Usá esta opción si la impresora tiene un tóner instalado pero
                                                        no se conoce la marca o el modelo. Podrás identificarlo más
                                                        adelante.</div>
                                                </div>
                                            </div>
                                            <label class="form-label fw-semibold mt-3" style="font-size:.78rem;">
                                                Observaciones <span class="text-muted fw-normal">(opcional)</span>
                                            </label>
                                            <textarea id="imp-toner-obs" class="form-control" rows="3"
                                                placeholder="Ej. Tóner genérico, se desconoce el modelo."></textarea>
                                        </div>
                                    </div>

                                    <div class="hu-component-detail-footer">
                                        <button type="button" class="btn btn-hu-outline"
                                            id="imp-toner-cancel">Cancelar</button>
                                        <button type="button" class="btn btn-hu-confirm"
                                            id="imp-toner-confirm">Seleccionar tóner</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── PASO 4: Resumen ── --}}
                        <div id="imp-step-4" style="display:none;">
                            <div class="wizard-panel">
                                <div class="wizard-panel-title">Resumen</div>
                                <div class="wizard-panel-help">Revisá la información antes de guardar la impresora.
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-5">
                                        <div class="hu-summary-section mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="hu-summary-label">Datos generales</span>
                                                <button type="button" class="btn btn-sm btn-link p-0"
                                                    data-imp-summary-back="1"
                                                    style="color:var(--hu-azul);font-size:.7rem;">Editar</button>
                                            </div>
                                            <div class="mb-2">
                                                <div class="hu-summary-label">Nº inventario</div>
                                                <div class="hu-summary-value" id="imp-summary-identificador">—</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="hu-summary-label">Nombre</div>
                                                <div class="hu-summary-value" id="imp-summary-nombre">—</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="hu-summary-label">Marca y Modelo</div>
                                                <div class="hu-summary-value" id="imp-summary-marca">—</div>
                                            </div>
                                            <div>
                                                <div class="hu-summary-label">IPv4</div>
                                                <div class="hu-summary-value" id="imp-summary-ip">—</div>
                                            </div>
                                        </div>
                                        <div class="hu-summary-section">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="hu-summary-label">Ubicación</span>
                                                <button type="button" class="btn btn-sm btn-link p-0"
                                                    data-imp-summary-back="2"
                                                    style="color:var(--hu-azul);font-size:.7rem;">Editar</button>
                                            </div>
                                            <div class="mb-2">
                                                <div class="hu-summary-label">Estado</div>
                                                <div class="hu-summary-value" id="imp-summary-estado">—</div>
                                            </div>
                                            <div>
                                                <div class="hu-summary-label">Ubicación asignada</div>
                                                <div class="hu-summary-value" id="imp-summary-ubicacion">—</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <div class="hu-summary-section">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="hu-summary-label">Tóner</span>
                                                <button type="button" class="btn btn-sm btn-link p-0"
                                                    data-imp-summary-back="3"
                                                    style="color:var(--hu-azul);font-size:.7rem;">Editar</button>
                                            </div>
                                            <div id="imp-summary-toner">
                                                <div class="text-muted" style="font-size:.78rem;">Sin seleccionar
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer navegación --}}
                        <div class="modal-footer border-0 px-0 pb-0 d-flex justify-content-between">
                            <button type="button" id="imp-btn-back" class="btn btn-hu-outline"
                                style="display:none;">
                                <span class="material-symbols-outlined"
                                    style="font-size:16px;vertical-align:middle;">arrow_back</span> Atrás
                            </button>
                            <div class="ms-auto d-flex gap-2">
                                <button type="button" id="imp-btn-next" class="btn btn-hu-confirm">
                                    Siguiente <span class="material-symbols-outlined"
                                        style="font-size:16px;vertical-align:middle;">arrow_forward</span>
                                </button>
                                <button type="submit" id="imp-btn-submit" class="btn btn-hu-gold"
                                    style="display:none;">
                                    <span class="material-symbols-outlined"
                                        style="font-size:16px;vertical-align:middle;">check</span> Cargar impresora
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal mantenimiento --}}
    <div class="modal fade" id="editImpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Mantenimiento de impresora</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit_impresoras') }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="editId" id="editId">

                        <div class="p-3 rounded-2 mb-4 d-flex align-items-center gap-2"
                            style="background:#EFF4FB;border:1px solid rgba(0,55,100,.15);">
                            <input class="form-check-input m-0" type="checkbox" id="editEn-uso" name="en-uso"
                                style="cursor:pointer;">
                            <label class="form-check-label fw-semibold mb-0" for="editEn-uso"
                                style="font-size:.88rem;cursor:pointer;">
                                En uso (asignada a un área)
                            </label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Nº Inventario</label>
                                <input type="text"
                                    class="form-control @error('editIdentificador') is-invalid @enderror"
                                    id="editIdentificador" name="editIdentificador" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Nombre</label>
                                <input type="text" class="form-control @error('editNombre') is-invalid @enderror"
                                    id="editNombre" name="editNombre" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Marca y Modelo</label>
                                <input type="text" class="form-control @error('editMarca') is-invalid @enderror"
                                    id="editMarca" name="editMarca" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">IP</label>
                                <input type="text" class="form-control @error('editIp') is-invalid @enderror"
                                    id="editIp" name="editIp" placeholder="192.168.x.x" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Área</label>
                                <select class="form-control @error('editArea') is-invalid @enderror" id="editArea"
                                    name="editArea" disabled>
                                    <option value="" disabled selected>Seleccioná un área</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                    @endforeach
                                </select>
                                <div id="editNroConsul_div" class="mt-2" style="display:none;">
                                    <label class="form-label fw-semibold" style="font-size:.85rem;">Nº
                                        consultorio</label>
                                    <input type="text"
                                        class="form-control @error('editNroConsul') is-invalid @enderror"
                                        id="editNroConsul" name="editNroConsul" placeholder="Nº">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Depósito</label>
                                <select class="form-control @error('editDeposito') is-invalid @enderror"
                                    id="editDeposito" name="editDeposito">
                                    <option value="" disabled selected>Seleccioná un depósito</option>
                                    @foreach ($depositos as $deposito)
                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Tóner</label>
                            <select class="form-control @error('editToner') is-invalid @enderror" id="editToner"
                                name="editToner" required>
                                <option value="" disabled selected>Seleccioná un tóner</option>
                                @foreach ($toners as $toner)
                                    <option value="{{ $toner->id }}">
                                        {{ $toner->nombre . ' — ' . ($toner->deposito->nombre ?? 'sin depósito') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-3">

                        <div class="p-3 rounded-2 mb-3 d-flex align-items-center gap-2"
                            style="background:#FFFBEB;border:1px solid #FDE68A;">
                            <input class="form-check-input m-0" type="checkbox" id="en-uso-mantenimineto"
                                style="cursor:pointer;">
                            <label class="form-check-label fw-semibold mb-0" for="en-uso-mantenimineto"
                                style="font-size:.88rem;cursor:pointer;color:#92400E;">
                                Se realizó mantenimiento
                            </label>
                        </div>
                        <div id="div-detalle-mant">
                            <div class="mb-3" style="display:none;">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Detalle del
                                    mantenimiento</label>
                                <input type="text" class="form-control @error('editDetalle') is-invalid @enderror"
                                    id="editDetalle" name="editDetalle">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Motivo del cambio</label>
                            <input type="text" class="form-control @error('editMotivo') is-invalid @enderror"
                                id="editMotivo" name="editMotivo" required>
                        </div>

                        <button type="submit" class="btn btn-hu w-100">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal eliminar --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">¿Eliminar impresora?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('delete_impresoras') }}" method="POST">
                        @csrf
                        <input type="hidden" id="deleteId" name="deleteId">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Motivo</label>
                            <input type="text" class="form-control @error('removeMotivo') is-invalid @enderror"
                                id="removeMotivo" name="removeMotivo" required>
                        </div>
                        <div class="p-2 rounded-2 mb-3"
                            style="background:#FEF2F2;border:1px solid #FECACA;font-size:.82rem;color:#991B1B;">
                            <span class="material-symbols-outlined"
                                style="font-size:15px;vertical-align:middle;">warning</span>
                            El tóner usado en esta impresora volverá al stock con estado DISPONIBLE.
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-hu-outline flex-fill"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger flex-fill">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal historial --}}
    <div class="modal fade" id="historyImpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Historial de la impresora</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table id="table_historias_pc" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>Técnico</th>
                                <th>Detalle</th>
                                <th>Motivo</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="table_historias_tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenido principal --}}
    <div class="px-4 px-md-5 py-4" style="max-width:1400px;margin:0 auto;">
        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <span class="fw-semibold"
                    style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--hu-azul);">
                    Listado de impresoras
                </span>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm"
                        style="max-width:220px;border:1px solid #ced4da;border-radius:.375rem;">
                        <span class="input-group-text" style="background:#fff;border:0;">
                            <span class="material-symbols-outlined"
                                style="font-size:15px;color:var(--hu-azul);">search</span>
                        </span>
                        <input type="text" id="buscador" class="form-control" placeholder="Buscar..."
                            style="border:0;box-shadow:none;">
                    </div>
                    @if (in_array($rolActual, ['Administrador', 'Super administrador', 'Tecnico'], true))
                        <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addModal" style="min-width: 180px; white-space: nowrap;">
                            <span class="material-symbols-outlined"
                                style="font-size:16px;vertical-align:middle;">add</span>
                            Cargar impresora
                        </button>
                    @endif
                </div>
            </div>

            <div id="div-impresora" class="d-flex flex-wrap gap-3 justify-content-start">
                @foreach ($impresoras as $impresora)
                    @php $enUso = $impresora->area_id !== null; @endphp
                    <div class="imp" data-nombre="{{ strtolower($impresora->nombre) }}"
                        data-id="{{ strtolower($impresora->identificador) }}"
                        style="width:200px;background:#fff;border:1px solid rgba(0,55,100,.12);border-radius:12px;padding:1rem;display:flex;flex-direction:column;gap:.4rem;transition:box-shadow .2s,transform .2s;cursor:pointer;"
                        onmouseenter="this.style.boxShadow='0 8px 24px rgba(0,55,100,.12)';this.style.transform='translateY(-2px)'"
                        onmouseleave="this.style.boxShadow='none';this.style.transform='none'">

                        <div class="d-flex align-items-center justify-content-between">
                            <span class="material-symbols-outlined"
                                style="font-size:32px;color:var(--hu-azul);">print</span>
                            <span class="badge"
                                style="background:{{ $enUso ? '#D1FAE5' : '#FEF3C7' }};color:{{ $enUso ? '#065F46' : '#92400E' }};font-size:.7rem;font-weight:600;border-radius:6px;">
                                {{ $enUso ? 'En uso' : 'Depósito' }}
                            </span>
                        </div>

                        <div class="fw-semibold" style="font-size:.9rem;color:var(--hu-azul);line-height:1.2;">
                            {{ $impresora->nombre }}</div>
                        <div style="font-size:.78rem;color:var(--hu-texto);">
                            <span class="text-muted">Inv:</span> {{ $impresora->identificador }}
                        </div>
                        <div style="font-size:.78rem;color:var(--hu-texto);">
                            <span class="text-muted">IP:</span> {{ $impresora->ip ?? '—' }}
                        </div>
                        <div style="font-size:.78rem;color:var(--hu-texto);">
                            @if ($enUso)
                                <span class="text-muted">Área:</span> {{ $impresora->area->nombre ?? '—' }}
                            @else
                                <span class="text-muted">Depósito:</span> {{ $impresora->deposito->nombre ?? '—' }}
                            @endif
                        </div>

                        <div class="d-flex gap-1 mt-auto pt-2">
                            <button type="button" class="btn btn-hu btn-sm flex-fill infoBtn" data-bs-toggle="modal"
                                data-bs-target="#infoImpModal" data-id="{{ $impresora->id }}"
                                data-identificador="{{ $impresora->identificador }}"
                                data-nombre="{{ $impresora->nombre }}" data-ip="{{ $impresora->ip }}"
                                data-area="{{ $impresora->area->nombre ?? 'Área no asignada' }}"
                                data-deposito="{{ $impresora->deposito->nombre ?? 'Depósito no asignado' }}"
                                data-enuso="{{ $enUso ? 'true' : 'false' }}"
                                data-toner="{{ ComponenteModel::find($impresora->toner_id)->nombre ?? 'Sin tóner' }}"
                                data-marca="{{ $impresora->marca_modelo ?? '' }}" title="Ver detalle">
                                <span class="material-symbols-outlined" style="font-size:14px;">info</span>
                            </button>

                            @if (in_array($rolActual, ['Administrador', 'Super administrador', 'Tecnico'], true))
                                <button type="button" class="btn btn-hu btn-sm flex-fill maintenanceBtn"
                                    data-bs-toggle="modal" data-bs-target="#editImpModal"
                                    data-id="{{ $impresora->id }}"
                                    data-identificador="{{ $impresora->identificador }}"
                                    data-nombre="{{ $impresora->nombre }}" data-ip="{{ $impresora->ip }}"
                                    data-area="{{ $impresora->area_id }}"
                                    data-deposito="{{ $impresora->deposito_id }}"
                                    data-enuso="{{ $enUso ? 'true' : 'false' }}"
                                    data-toner="{{ ComponenteModel::find($impresora->toner_id) }}"
                                    data-marca="{{ $impresora->marca_modelo ?? '' }}" title="Editar">
                                    <span class="material-symbols-outlined" style="font-size:14px;">build</span>
                                </button>
                            @endif

                            <button type="button" class="btn btn-hu btn-sm flex-fill historyBtn"
                                data-bs-toggle="modal" data-bs-target="#historyImpModal"
                                data-id="{{ $impresora->id }}" data-nro_inv="{{ $impresora->identificador }}"
                                data-nombre="{{ $impresora->nombre }}" data-tipo="Impresora" title="Historial">
                                <span class="material-symbols-outlined" style="font-size:14px;">history</span>
                            </button>

                            @if ($rolActual === 'Super administrador')
                                <button type="button" class="btn btn-danger btn-sm flex-fill" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-id="{{ $impresora->id }}" title="Eliminar">
                                    <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if ($impresoras->isEmpty())
                    <div class="text-center text-muted py-5 w-100" style="font-size:.9rem;">
                        <span class="material-symbols-outlined d-block mb-2" style="font-size:2.5rem;">print</span>
                        No hay impresoras registradas.
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    @endpush

    @push('vendor-scripts')
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    @endpush


    @push('scripts')
        <script>
            $(document).ready(function() {

                // ── Checkbox "en uso" al AGREGAR ──
                // ════════════════════════════════════════════════════════════════
                // ── Wizard: Cargar impresora (4 pasos) ──────────────────────────
                // ════════════════════════════════════════════════════════════════
                (function() {

                    // ── Catálogo de tóners (inyectado desde PHP) ─────────────────
                    @php
                        $tonerCatalog = $toners
                            ->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'nombre' => $item->nombre,
                                    'deposito' => $item->deposito->nombre ?? 'Sin depósito',
                                    'stock' => (int) $item->stock,
                                    'estado_id' => (int) $item->estado_id,
                                ];
                            })
                            ->values();
                    @endphp
                    var tonerCatalog = @json($tonerCatalog);

                    // ── Estado del wizard ─────────────────────────────────────────
                    var impStep = 1;
                    var impTonerMode = 'stock'; // 'stock' | 'sin-stock' | 'registrar' | 'no-identificado'

                    // ── Helpers ubicación ─────────────────────────────────────────
                    function impSetLocationMode(mode) {
                        var enUso = mode === 'uso';
                        $('#imp-location-use-card').toggleClass('is-selected', enUso);
                        $('#imp-location-storage-card').toggleClass('is-selected', !enUso);
                        $('#imp-location-area-panel').toggle(enUso);
                        $('#imp-location-storage-panel').toggle(!enUso);
                        $('#addImpEnUso').val(enUso ? '1' : '0');
                        $('#addImpArea').prop('disabled', !enUso).prop('required', enUso);
                        $('#addImpDeposito').prop('disabled', enUso).prop('required', !enUso);
                        if (enUso) {
                            $('#addImpDeposito').val('');
                        } else {
                            $('#addImpArea').val('');
                            $('#addImpNroConsul').val('');
                            $('#addImpNroConsul_div').hide();
                        }
                    }

                    // ── Stepper ───────────────────────────────────────────────────
                    function impUpdateStepper(step) {
                        $('[data-imp-step]').each(function() {
                            var n = parseInt($(this).data('imp-step'), 10);
                            $(this).removeClass('is-active is-complete');
                            if (n < step) {
                                $(this).addClass('is-complete');
                                $(this).find('.wizard-dot').text('✓');
                            } else {
                                if (n === step) $(this).addClass('is-active');
                                $(this).find('.wizard-dot').text(n);
                            }
                        });
                    }

                    // ── Validación por paso ───────────────────────────────────────
                    function impValidateStep(step) {
                        if (step === 1) {
                            var valid = true;
                            ['#addImpIdentificador', '#addImpNombre', '#addImpMarca', '#addImpIp'].forEach(function(
                                sel) {
                                var $f = $(sel),
                                    ok = $f.val().trim() !== '';
                                $f.toggleClass('is-invalid', !ok);
                                if (!ok && valid) $f.focus();
                                valid = valid && ok;
                            });
                            return valid;
                        }
                        if (step === 2) {
                            var enUso = $('#imp-location-use').is(':checked');
                            if (enUso) {
                                var okArea = !!$('#addImpArea').val();
                                $('#addImpArea').toggleClass('is-invalid', !okArea);
                                if (!okArea) $('#addImpArea').focus();
                                return okArea;
                            }
                            var okDep = !!$('#addImpDeposito').val();
                            $('#addImpDeposito').toggleClass('is-invalid', !okDep);
                            if (!okDep) $('#addImpDeposito').focus();
                            return okDep;
                        }
                        if (step === 3) {
                            // El tóner es obligatorio. Se considera seleccionado si el modo es
                            // cualquiera excepto 'stock' sin valor, o no-identificado (siempre válido).
                            var modo = $('#addTonerModo').val();
                            if (modo === 'stock' && !$('#addTonerSelect').val()) {
                                // Abrir el selector si no está abierto
                                if ($('#imp-toner-list-view').is(':visible')) {
                                    impOpenTonerSelector();
                                }
                                return false;
                            }
                            if (modo === 'sin-stock' && !$('#addTonerSelect').val()) return false;
                            if (modo === 'registrar' && !$('#addTonerNombre').val().trim()) return false;
                            return true;
                        }
                        return true;
                    }

                    // ── Navegar a un paso ─────────────────────────────────────────
                    function impGoToStep(step) {
                        // Si el selector de tóner está abierto, cerrarlo antes de navegar
                        if ($('#imp-toner-detail-view').is(':visible')) {
                            impCloseTonerSelector();
                        }
                        impStep = step;
                        $('#imp-step-1, #imp-step-2, #imp-step-3, #imp-step-4').hide();
                        $('#imp-step-' + step).show();
                        $('#imp-btn-back').toggle(step > 1);
                        $('#imp-btn-next').toggle(step < 4);
                        $('#imp-btn-submit').toggle(step === 4);
                        impUpdateStepper(step);
                        if (step === 4) impUpdateSummary();
                    }

                    // ── Resumen ───────────────────────────────────────────────────
                    function impUpdateSummary() {
                        $('#imp-summary-identificador').text($('#addImpIdentificador').val().trim() || '—');
                        $('#imp-summary-nombre').text($('#addImpNombre').val().trim() || '—');
                        $('#imp-summary-marca').text($('#addImpMarca').val().trim() || '—');
                        $('#imp-summary-ip').text($('#addImpIp').val().trim() || 'Sin asignar');

                        var enUso = $('#imp-location-use').is(':checked');
                        $('#imp-summary-estado').text(enUso ? 'Está en uso' : 'No está en uso');
                        if (enUso) {
                            var areaText = $('#addImpArea option:selected').text().trim();
                            if ($('#addImpNroConsul').val().trim()) areaText += ' ' + $('#addImpNroConsul').val()
                                .trim();
                            $('#imp-summary-ubicacion').text(areaText || 'Sin área');
                        } else {
                            $('#imp-summary-ubicacion').text($('#addImpDeposito option:selected').text().trim() ||
                                'Sin depósito');
                        }

                        // Tóner en resumen
                        var modo = $('#addTonerModo').val();
                        var $sumToner = $('#imp-summary-toner').empty();
                        var tonerText = '',
                            tonerDetail = '',
                            tonerPill = '';

                        if (modo === 'stock' || modo === 'sin-stock') {
                            var selectedOpt = $('#addTonerSelect option:selected');
                            tonerText = selectedOpt.text().replace(/ — .*/, '').trim();
                            var deposito = selectedOpt.data ? selectedOpt.attr('data-deposito') : '';
                            if (modo === 'sin-stock') {
                                var cant = $('#addTonerCantidad').val();
                                var motivo = $('#addTonerMotivo').val().trim();
                                tonerDetail = 'Se ingresarán ' + cant + (Number(cant) === 1 ? ' unidad' :
                                    ' unidades') + (motivo ? ' · Motivo: ' + motivo : '');
                                tonerPill = 'Ingreso de stock';
                            } else {
                                tonerPill = 'Desde stock';
                            }
                        } else if (modo === 'registrar') {
                            tonerText = $('#addTonerNombre').val().trim();
                            var cant2 = $('#addTonerCantidad').val();
                            var motivo2 = $('#addTonerMotivo').val().trim();
                            tonerDetail = 'Cantidad: ' + cant2 + (motivo2 ? ' · Motivo: ' + motivo2 : '');
                            tonerPill = 'Registrar nuevo';
                        } else if (modo === 'no-identificado') {
                            tonerText = 'Tóner no identificado';
                            tonerPill = 'No identificado';
                        }

                        if (!tonerText) {
                            $sumToner.html(
                                '<div class="text-muted" style="font-size:.78rem;">Sin seleccionar</div>');
                            return;
                        }
                        $sumToner.append(
                            '<div class="hu-summary-component"><div>' +
                            '<div class="hu-summary-label">Tóner</div>' +
                            '<div class="hu-summary-value">' + $('<div>').text(tonerText).html() + '</div>' +
                            (tonerDetail ? '<div class="text-muted" style="font-size:.72rem;">' + $('<div>')
                                .text(tonerDetail).html() + '</div>' : '') +
                            '</div>' +
                            (tonerPill ? '<span class="hu-pill">' + $('<div>').text(tonerPill).html() +
                                '</span>' : '') +
                            '</div>'
                        );
                    }

                    // ── Selector de tóner: renderizar tabla stock ─────────────────
                    function impRenderTonerStock() {
                        var search = ($('#imp-toner-search').val() || '').trim().toLowerCase();
                        var $body = $('#imp-toner-stock-body').empty();
                        var selected = $('#addTonerSelect').val();
                        var visible = 0;

                        tonerCatalog.forEach(function(item) {

                            var haystack = (item.nombre + ' ' + item.deposito).toLowerCase();
                            if (search && !haystack.includes(search)) return;

                            var isSinStock = item.estado_id === 7 || item.stock <= 0;
                            var isSelected = String(item.id) === String(selected);
                            var checked = isSelected ? ' checked' : '';
                            var rowClass = isSelected ? 'hu-stock-row is-selected' : 'hu-stock-row';

                            visible++;
                            $body.append(
                                '<tr class="' + rowClass + '" data-toner-id="' + item.id +
                                '" data-sin-stock="' + (isSinStock ? '1' : '0') + '">' +
                                '<td><input class="hu-stock-radio" type="radio" name="imp_toner_stock_radio" value="' +
                                item.id + '"' + checked + '></td>' +
                                '<td><span class="hu-stock-name">' + $('<div>').text(item.nombre)
                            .html() + '</span></td>' +
                                '<td>' + $('<div>').text(item.deposito).html() + '</td>' +
                                '<td class="text-end">' + (isSinStock ?
                                    '<span class="badge bg-secondary">Sin stock</span>' : item.stock) +
                                '</td>' +
                                '</tr>'
                            );
                        });

                        $('#imp-toner-empty').toggle(visible === 0);
                    }

                    // ── Selector de tóner: cambiar pestaña ───────────────────────
                    function impSetTonerMode(mode) {
                        impTonerMode = mode;
                        $('.hu-component-tab[data-toner-mode]').each(function() {
                            $(this).toggleClass('is-active', $(this).data('toner-mode') === mode);
                        });
                        $('.hu-detail-panel[data-toner-panel]').each(function() {
                            $(this).toggle($(this).data('toner-panel') === mode);
                        });
                        $('#imp-toner-confirm').text(mode === 'stock' ? 'Seleccionar tóner' :
                        'Confirmar selección');
                        if (mode === 'stock') {
                            $('#imp-toner-replenishment').hide();
                            $('#imp-toner-quantity').val('1');
                            $('#imp-toner-reason').val('');
                            impRenderTonerStock();
                        }
                    }

                    // ── Selector de tóner: abrir ──────────────────────────────────
                    function impOpenTonerSelector() {
                        var currentMode = $('#addTonerModo').val();
                        var restoreSinStock = (currentMode === 'sin-stock');
                        var openMode = restoreSinStock ? 'stock' : currentMode;

                        $('#imp-toner-search').val('');
                        impSetTonerMode(openMode);

                        // Restaurar cantidad/motivo para sin-stock
                        if (restoreSinStock) {
                            $('#imp-toner-quantity').val($('#addTonerCantidad').val() || '1');
                            $('#imp-toner-reason').val($('#addTonerMotivo').val() || '');
                            $('#imp-toner-replenishment').show();
                        }

                        // Restaurar campos de "registrar nuevo"
                        if (currentMode === 'registrar') {
                            $('#imp-toner-new-name').val($('#addTonerNombre').val() || '');
                            $('#imp-toner-new-origin').val($('#addTonerDepositoOrigen').val() || '');
                            $('#imp-toner-new-quantity').val($('#addTonerCantidad').val() || '1');
                            $('#imp-toner-new-reason').val($('#addTonerMotivo').val() || '');
                        }

                        // Restaurar observaciones para no-identificado
                        if (currentMode === 'no-identificado') {
                            $('#imp-toner-obs').val($('#addTonerObservaciones').val() || '');
                        }

                        $('#imp-toner-list-view').hide();
                        $('#imp-toner-detail-view').show();
                        $('#imp-btn-next, #imp-btn-submit, #imp-btn-back').hide();
                    }

                    // ── Selector de tóner: cerrar ─────────────────────────────────
                    function impCloseTonerSelector() {
                        $('#imp-toner-detail-view').hide();
                        $('#imp-toner-list-view').show();
                        $('#imp-btn-back').toggle(impStep > 1);
                        $('#imp-btn-next').toggle(impStep < 4);
                        $('#imp-btn-submit').toggle(impStep === 4);
                        impRefreshTonerRow();
                    }

                    // ── Actualizar texto en la fila del paso 3 ───────────────────
                    function impRefreshTonerRow() {
                        var modo = $('#addTonerModo').val();
                        var text = '';

                        if (modo === 'stock' || modo === 'sin-stock') {
                            text = $('#addTonerSelect option:selected').text().replace(/ — .*/, '').trim();
                        } else if (modo === 'registrar') {
                            text = $('#addTonerNombre').val().trim();
                        } else if (modo === 'no-identificado') {
                            text = 'Tóner no identificado';
                        }

                        var label = text || 'Sin seleccionar';
                        $('#imp-toner-selection-label')
                            .text(label)
                            .toggleClass('is-selected', !!text);
                        $('#imp-toner-open-label').text(text ? 'Cambiar tóner' : 'Seleccionar tóner');
                    }

                    // ── Botón confirmar tóner ─────────────────────────────────────
                    $('#imp-toner-confirm').on('click', function() {
                        var mode = impTonerMode;

                        if (mode === 'stock') {
                            var value = $('input[name="imp_toner_stock_radio"]:checked').val();
                            if (!value) {
                                $('#imp-toner-empty').text('Seleccioná un tóner para continuar.').show();
                                return;
                            }

                            var item = tonerCatalog.find(function(t) {
                                return String(t.id) === String(value);
                            });
                            var isSinStock = item && (Number(item.estado_id) === 7 || item.stock <= 0);

                            if (isSinStock) {
                                // Validar reposición
                                var cantidad = parseInt($('#imp-toner-quantity').val(), 10);
                                var motivo = $('#imp-toner-reason').val().trim();

                                $('#imp-toner-quantity').toggleClass('is-invalid', !Number.isInteger(
                                    cantidad) || cantidad < 1);
                                $('#imp-toner-reason').toggleClass('is-invalid', !motivo);

                                if (!Number.isInteger(cantidad) || cantidad < 1) {
                                    $('#imp-toner-quantity').focus();
                                    return;
                                }
                                if (!motivo) {
                                    $('#imp-toner-reason').focus();
                                    return;
                                }

                                $('#addTonerSelect').val(value);
                                $('#addTonerNombre').val('');
                                $('#addTonerDepositoOrigen').val('');
                                $('#addTonerCantidad').val(cantidad);
                                $('#addTonerMotivo').val(motivo);
                                $('#addTonerObservaciones').val('');
                                $('#addTonerModo').val('sin-stock');
                            } else {
                                $('#addTonerSelect').val(value);
                                $('#addTonerNombre').val('');
                                $('#addTonerDepositoOrigen').val('');
                                $('#addTonerCantidad').val('1');
                                $('#addTonerMotivo').val('');
                                $('#addTonerObservaciones').val('');
                                $('#addTonerModo').val('stock');
                            }

                        } else if (mode === 'registrar') {
                            var nombre = $('#imp-toner-new-name').val().trim();
                            var origen = $('#imp-toner-new-origin').val();
                            var cant = parseInt($('#imp-toner-new-quantity').val(), 10);
                            var motivo = $('#imp-toner-new-reason').val().trim();

                            $('#imp-toner-new-name').toggleClass('is-invalid', !nombre);
                            $('#imp-toner-new-origin').toggleClass('is-invalid', !origen);
                            $('#imp-toner-new-quantity').toggleClass('is-invalid', !Number.isInteger(
                                cant) || cant < 1);
                            $('#imp-toner-new-reason').toggleClass('is-invalid', !motivo);

                            if (!nombre) {
                                $('#imp-toner-new-name').focus();
                                return;
                            }
                            if (!origen) {
                                $('#imp-toner-new-origin').focus();
                                return;
                            }
                            if (!Number.isInteger(cant) || cant < 1) {
                                $('#imp-toner-new-quantity').focus();
                                return;
                            }
                            if (!motivo) {
                                $('#imp-toner-new-reason').focus();
                                return;
                            }

                            $('#addTonerSelect').val('');
                            $('#addTonerNombre').val(nombre);
                            $('#addTonerDepositoOrigen').val(origen);
                            $('#addTonerCantidad').val(cant);
                            $('#addTonerMotivo').val(motivo);
                            $('#addTonerObservaciones').val('');
                            $('#addTonerModo').val('registrar');

                        } else if (mode === 'no-identificado') {
                            var obs = $('#imp-toner-obs').val().trim();
                            $('#addTonerSelect').val('');
                            $('#addTonerNombre').val('Tóner no identificado');
                            $('#addTonerDepositoOrigen').val('');
                            $('#addTonerCantidad').val('1');
                            $('#addTonerMotivo').val('');
                            $('#addTonerObservaciones').val(obs);
                            $('#addTonerModo').val('no-identificado');
                        }

                        impCloseTonerSelector();
                    });

                    // ── Eventos del wizard ────────────────────────────────────────
                    $('#imp-location-use, #imp-location-storage').on('change', function() {
                        impSetLocationMode($(this).val());
                    });
                    $('#addImpArea').on('change', function() {
                        if ($(this).val() == 27) $('#addImpNroConsul_div').show();
                        else {
                            $('#addImpNroConsul_div').hide();
                            $('#addImpNroConsul').val('');
                        }
                    });

                    $('#imp-toner-open-btn').on('click', impOpenTonerSelector);

                    $(document).on('click', '.hu-component-tab[data-toner-mode]', function() {
                        impSetTonerMode($(this).data('toner-mode'));
                    });

                    $('#imp-toner-search').on('input', impRenderTonerStock);

                    $(document).on('click', '#imp-toner-stock-body .hu-stock-row', function() {
                        $(this).find('.hu-stock-radio').prop('checked', true).trigger('change');
                    });

                    $(document).on('change', '#imp-toner-stock-body .hu-stock-radio', function() {
                        var tonerId = String($(this).val());
                        var item = tonerCatalog.find(function(t) {
                            return String(t.id) === tonerId;
                        });
                        var isSinStock = item && (Number(item.estado_id) === 7 || item.stock <= 0);

                        $('#imp-toner-stock-body .hu-stock-row').removeClass('is-selected');
                        $(this).closest('.hu-stock-row').addClass('is-selected');
                        $('#imp-toner-replenishment').toggle(!!isSinStock);
                        $('#imp-toner-confirm').text(isSinStock ? 'Confirmar selección' :
                            'Seleccionar tóner'); // ← agregar

                        if (!isSinStock) {
                            $('#imp-toner-quantity').val('1');
                            $('#imp-toner-reason').val('');
                        }
                    });
                    $('#imp-toner-back, #imp-toner-cancel').on('click', function() {
                        impCloseTonerSelector();
                    });

                    // ── Botones de navegación ─────────────────────────────────────
                    $('#imp-btn-next').on('click', function() {
                        if (impValidateStep(impStep)) impGoToStep(impStep + 1);
                    });
                    $('#imp-btn-back').on('click', function() {
                        impGoToStep(impStep - 1);
                    });

                    // ── Botones "Editar" del resumen ──────────────────────────────
                    $(document).on('click', '[data-imp-summary-back]', function() {
                        impGoToStep(parseInt($(this).data('imp-summary-back'), 10));
                    });

                    // ── Reset wizard al cerrar modal ──────────────────────────────
                    $('#addModal').on('hidden.bs.modal', function() {
                        $('#addImpForm')[0].reset();
                        $('#addTonerModo').val('stock');
                        $('#addTonerSelect').val('');
                        $('#addTonerNombre').val('');
                        $('#addTonerDepositoOrigen').val('');
                        $('#addTonerCantidad').val('1');
                        $('#addTonerMotivo').val('');
                        $('#addTonerObservaciones').val('');
                        $('#imp-toner-detail-view').hide();
                        $('#imp-toner-list-view').show();
                        $('.is-invalid').removeClass('is-invalid');
                        impSetLocationMode('uso');
                        impGoToStep(1);
                    });

                    // ── Inicialización ────────────────────────────────────────────
                    impSetLocationMode('uso');

                })(); // fin wizard impresoras

                // ── Checkbox "en uso" al EDITAR ──
                $('#editEn-uso').on('change', function() {
                    const modal = $('#editImpModal');
                    if ($(this).is(':checked')) {
                        modal.find('#editDeposito').prop('disabled', true).val('');
                        modal.find('#editArea').prop('disabled', false).val('');
                    } else {
                        modal.find('#editDeposito').prop('disabled', false).val('');
                        modal.find('#editArea').prop('disabled', true).val('');
                        $('#editNroConsul_div').hide().find('input').removeAttr('required');
                    }
                });

                $('#editArea').on('change', function() {
                    if ($(this).val() == 27) {
                        $('#editNroConsul_div').show().find('input').attr('required', 'required');
                    } else {
                        $('#editNroConsul_div').hide().find('input').removeAttr('required');
                    }
                });

                // ── Abrir modal editar ──
                $('#editImpModal').on('show.bs.modal', function(event) {
                    const btn = $(event.relatedTarget);
                    const enUso = btn.data('enuso') === true || btn.data('enuso') === 'true';
                    const Toner = btn.data('toner');

                    $(this).find('#editId').val(btn.data('id'));
                    $(this).find('#editIdentificador').val(btn.data('identificador'));
                    $(this).find('#editNombre').val(btn.data('nombre'));
                    $(this).find('#editMarca').val(btn.data('marca'));
                    $(this).find('#editIp').val(btn.data('ip'));
                    $(this).find('#editEn-uso').prop('checked', enUso);

                    if (enUso) {
                        $(this).find('#editArea').prop('disabled', false).val(btn.data('area'));
                        $(this).find('#editDeposito').prop('disabled', true).val('');
                    } else {
                        $(this).find('#editArea').prop('disabled', true).val('');
                        $(this).find('#editDeposito').prop('disabled', false).val(btn.data('deposito'));
                    }

                    // Tóner — si está sin stock o en uso, lo inyectamos como opción seleccionada
                    const modal = $(this);
                    modal.find('#editToner option.deleteable-toner').remove();
                    if (Toner && (Toner.stock == 0 || Toner.estado_id == 5)) {
                        modal.find('#editToner').append(
                            `<option value="${Toner.id}" class="deleteable-toner" selected>${Toner.nombre} — Actual</option>`
                        );
                    } else if (Toner) {
                        modal.find('#editToner').val(Toner.id);
                    }
                });

                // ── Mantenimiento checkbox ──
                $('#en-uso-mantenimineto').on('change', function() {
                    const div = $('#div-detalle-mant > div');
                    if ($(this).is(':checked')) {
                        div.show().find('input').attr('required', 'required');
                    } else {
                        div.hide().find('input').removeAttr('required');
                    }
                });

                // ── Modal eliminar ──
                $('#deleteModal').on('show.bs.modal', function(event) {
                    $(this).find('#deleteId').val($(event.relatedTarget).data('id'));
                });

                // ── Modal info ──
                $('#infoImpModal').on('show.bs.modal', function(event) {
                    const btn = $(event.relatedTarget);
                    const enUso = btn.data('enuso') === true || btn.data('enuso') === 'true';
                    $(this).find('#idenInfo').text(btn.data('identificador'));
                    $(this).find('#nombreInfo').text(btn.data('nombre'));
                    $(this).find('#marcaInfo').text(btn.data('marca') || '—');
                    $(this).find('#ipInfo').text(btn.data('ip') || '—');
                    $(this).find('#titleAsig').text(enUso ? 'Área' : 'Depósito');
                    $(this).find('#infoAsig').text(enUso ? btn.data('area') : btn.data('deposito'));
                    $(this).find('#tonerInfo').text(btn.data('toner') || '—');
                });

                // ── Buscador ──
                $('#buscador').on('input', function() {
                    const term = $(this).val().toLowerCase();
                    $('.imp').each(function() {
                        const nombre = String($(this).data('nombre'));
                        const id = String($(this).data('id'));
                        $(this).toggle(nombre.includes(term) || id.includes(term));
                    });
                });

                // ── Click en tarjeta abre info ──
                $(document).on('click', '.imp', function(e) {
                    if (!$(e.target).closest('button').length) {
                        $(this).find('.infoBtn').trigger('click');
                    }
                });
                $('.maintenanceBtn, .historyBtn').on('click', function(e) {
                    e.stopPropagation();
                });

                // ── Historial AJAX ──
                var modalHandlerAttached = false;
                var nro_inv = '',
                    nombre = '';

                $('#historyImpModal').on('show.bs.modal', function(event) {
                    if (modalHandlerAttached) return;
                    modalHandlerAttached = true;
                    const btn = $(event.relatedTarget);
                    nro_inv = btn.data('nro_inv');
                    nombre = btn.data('nombre');
                    const table = $('#table_historias_pc').DataTable();
                    table.clear().draw();
                    let url = '{{ route('historia.get', ['tipo' => ':tipo', 'id' => ':id']) }}';
                    url = url.replace(':tipo', btn.data('tipo')).replace(':id', btn.data('id'));
                    $.ajax({
                        url,
                        method: 'GET',
                        success: function(response) {
                            const data = response.historia.map(h => {
                                const fecha = new Date(h.created_at).toLocaleDateString(
                                    'es-ES', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                return [h.tecnico, h.detalle,
                                    h.motivo || '', fecha
                                ];
                            });
                            table.rows.add(data).draw();
                        },
                        error: () => alert('Error al cargar las historias.')
                    });
                });
                $('#historyImpModal').on('hide.bs.modal', function() {
                    modalHandlerAttached = false;
                });

                // ── DataTable historial ──
                $('#table_historias_pc').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel',
                        className: 'btn btn-hu btn-sm',
                        title: () => 'Historia de ' + nro_inv + ' - ' + nombre
                    }],
                    responsive: true,
                    lengthChange: true,
                    autoWidth: true,
                    language: {
                        emptyTable: 'No hay datos disponibles en la tabla.',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                        infoEmpty: 'Mostrando 0 a 0 de 0 entradas',
                        infoFiltered: '(filtrado de _MAX_ totales)',
                        search: 'Buscar:',
                        zeroRecords: 'No se encontraron registros',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
