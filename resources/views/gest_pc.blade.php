<x-app-layout>
    @php
        $rolActual = data_get(Auth::user(), 'rol.nombre');
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-lg leading-tight" style="color: var(--hu-azul);">
            PCs
            <button type="button" class="btn btn-hu-outline-dorado btn-sm ms-2" data-bs-toggle="modal"
                data-bs-target="#infoModal" style="padding: 2px 8px; font-size:.78rem;">
                <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">info</span>
            </button>
        </h2>
    </x-slot>


    {{-- Modal info --}}
    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">PCs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    Aquí se registran las computadoras armadas y se les asigna un área o depósito.
                </div>
            </div>
        </div>
    </div>

    {{-- Modal info — Armar PC --}}
    <div class="modal fade" id="infoAddPc" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Armar PC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    Todos los componentes usados en el armado se quitarán del stock y se asignarán a la PC
                    automáticamente.
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: info de la PC --}}
    <div class="modal fade" id="infoPcModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Información de la PC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="d-flex flex-column gap-1 mb-3">
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold" style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">Nº
                                Inventario</span>
                            <span id="idenInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">Nombre</span>
                            <span id="nombreInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">IPv4</span>
                            <span id="ipInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold" id="titleAsig"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;"></span>
                            <span id="infoAsig" style="font-size:.9rem;"></span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex flex-column gap-1">
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">Placa madre</span>
                            <span id="motherInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">Procesador</span>
                            <span id="proceInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">Discos</span>
                            <span id="discosInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">RAMs</span>
                            <span id="ramsInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">Fuente</span>
                            <span id="fuenteInfo" style="font-size:.9rem;"></span>
                        </div>
                        <div class="d-flex gap-2 align-items-baseline">
                            <span class="fw-semibold"
                                style="font-size:.82rem;color:var(--hu-azul);min-width:110px;">Placa de video</span>
                            <span id="placavidInfo" style="font-size:.9rem;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Armar PC (multistep) --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content hu-wizard"
                style="border-radius:12px;border:none;box-shadow:0 12px 40px rgba(0,55,100,.16);">
                <div class="modal-header border-0 pb-0">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="modal-title fw-semibold mb-1" style="color:var(--hu-azul);">Agregar PC</h5>
                                <div class="text-muted" style="font-size:.72rem;">Completá los datos para registrar y
                                    armar la PC.</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        {{-- Stepper del wizard --}}
                        <div class="wizard-stepper" aria-label="Progreso del alta de PC">
                            <div class="wizard-step is-active" data-wizard-step="1">
                                <div class="wizard-dot">1</div>
                                <span class="wizard-label">Datos generales</span>
                            </div>
                            <div class="wizard-step" data-wizard-step="2">
                                <div class="wizard-dot">2</div>
                                <span class="wizard-label">Ubicación</span>
                            </div>
                            <div class="wizard-step" data-wizard-step="3">
                                <div class="wizard-dot">3</div>
                                <span class="wizard-label">Componentes</span>
                            </div>
                            <div class="wizard-step" data-wizard-step="4">
                                <div class="wizard-dot">4</div>
                                <span class="wizard-label">Resumen</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <form action="{{ route('store_pc') }}" method="POST" id="addPcForm"
                        style="display:flex;flex-direction:column;gap:20px;">
                        @csrf

                        {{-- ── PASO 1: Datos generales ── --}}
                        <div id="add-step-1">
                            <div class="wizard-panel">
                                <div class="wizard-panel-title">Datos generales</div>
                                <div class="wizard-panel-help">Completá la información básica de la PC.</div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label for="addIdentificador" class="form-label fw-semibold"
                                            style="font-size:.78rem;">Nº de inventario <span
                                                style="color:#b02a37;">*</span></label>
                                        <input type="text"
                                            class="form-control @error('addIdentificador') is-invalid @enderror"
                                            id="addIdentificador" name="addIdentificador" placeholder="PC-2025-001"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="addNombre" class="form-label fw-semibold"
                                            style="font-size:.78rem;">Nombre <span
                                                style="color:#b02a37;">*</span></label>
                                        <input type="text"
                                            class="form-control @error('addNombre') is-invalid @enderror"
                                            id="addNombre" name="addNombre" placeholder="PC Administración 01"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="addIp" class="form-label fw-semibold"
                                            style="font-size:.78rem;">IPv4 <span
                                                class="text-muted fw-normal">(opcional)</span></label>
                                        <input type="text"
                                            class="form-control @error('addIp') is-invalid @enderror" id="addIp"
                                            name="addIp" placeholder="192.168.1.25">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── PASO 2: Ubicación ── --}}
                        <div id="add-step-2" style="display:none;">
                            <div class="wizard-panel">
                                <div class="wizard-panel-title">Ubicación</div>
                                <div class="wizard-panel-help">Indicá si la PC queda instalada en un área o permanece
                                    en un depósito.</div>

                                <input type="hidden" id="addEnUso" name="addEnUso" value="0">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label class="location-choice d-block is-selected" id="location-use-card">
                                            <input type="radio" name="pc_ubicacion" value="uso"
                                                id="location-use" class="visually-hidden" checked>
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
                                        <label class="location-choice d-block" id="location-storage-card">
                                            <input type="radio" name="pc_ubicacion" value="deposito"
                                                id="location-storage" class="visually-hidden">
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

                                <div id="location-area-panel">
                                    <label for="addArea" class="form-label fw-semibold"
                                        style="font-size:.78rem;">Área <span style="color:#b02a37;">*</span></label>
                                    <select class="form-control @error('addArea') is-invalid @enderror" id="addArea"
                                        name="addArea">
                                        <option value="" selected>Seleccioná un área</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2" id="addNroConsul_div" style="display:none;">
                                        <label for="addNroConsul" class="form-label fw-semibold"
                                            style="font-size:.78rem;">Nº de consultorio</label>
                                        <input type="text"
                                            class="form-control @error('addNroConsul') is-invalid @enderror"
                                            id="addNroConsul" name="addNroConsul" placeholder="Nº de consultorio">
                                    </div>
                                </div>

                                <div id="location-storage-panel" style="display:none;">
                                    <label for="addDeposito" class="form-label fw-semibold"
                                        style="font-size:.78rem;">Depósito <span
                                            style="color:#b02a37;">*</span></label>
                                    <select class="form-control @error('addDeposito') is-invalid @enderror"
                                        id="addDeposito" name="addDeposito">
                                        <option value="" selected>Seleccioná un depósito</option>
                                        @foreach ($depositos as $deposito)
                                            <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ── PASO 3: Componentes ── --}}
                        <div id="add-step-3" style="display:none;">
                            @php
                                $componentesSingulares = [
                                    [
                                        'key' => 'Motherboard',
                                        'label' => 'Placa madre',
                                        'icon' => 'developer_board',
                                        'items' => $motherboards,
                                        'required' => true,
                                    ],
                                    [
                                        'key' => 'Procesador',
                                        'label' => 'Procesador',
                                        'icon' => 'memory',
                                        'items' => $procesadores,
                                        'required' => true,
                                    ],
                                    [
                                        'key' => 'Fuente',
                                        'label' => 'Fuente',
                                        'icon' => 'power',
                                        'items' => $fuentes,
                                        'required' => true,
                                    ],
                                    [
                                        'key' => 'Placavid',
                                        'label' => 'Placa de video',
                                        'icon' => 'videogame_asset',
                                        'items' => $placasvid,
                                        'required' => false,
                                    ],
                                ];
                            @endphp

                            {{-- Vista principal del paso 3 --}}
                            <div id="component-list-view">
                                <div class="wizard-panel">
                                    <div class="wizard-panel-title">Componentes</div>
                                    <div class="wizard-panel-help">Seleccioná los componentes que forman parte de esta
                                        PC. Podés elegirlos desde el stock, registrar uno nuevo o marcarlo como no
                                        identificado.</div>

                                    <div class="hu-component-list">
                                        @foreach ($componentesSingulares as $comp)
                                            @php $field = 'add' . $comp['key']; @endphp
                                            <div class="hu-component-row" data-component-key="{{ $comp['key'] }}"
                                                data-component-kind="singular" data-field="{{ $field }}">
                                                <div class="hu-component-icon">
                                                    <span class="material-symbols-outlined">{{ $comp['icon'] }}</span>
                                                </div>
                                                <div class="hu-component-label">
                                                    <div>{{ $comp['label'] }} @if ($comp['required'])
                                                            <span class="hu-required">*</span>
                                                        @endif
                                                    </div>
                                                    @if (!$comp['required'])
                                                        <small>Opcional</small>
                                                    @endif
                                                    <div class="hu-component-selected"
                                                        data-selection-label="{{ $field }}">Sin seleccionar
                                                    </div>
                                                </div>
                                                <div class="hu-component-action">
                                                    <button type="button" class="hu-component-open"
                                                        data-component-key="{{ $comp['key'] }}"
                                                        data-component-kind="singular"
                                                        data-field="{{ $field }}">
                                                        <span class="material-symbols-outlined">add_circle</span>
                                                        <span class="component-open-label">Seleccionar
                                                            componente</span>
                                                    </button>
                                                </div>

                                                <input type="hidden" name="{{ $field }}_modo"
                                                    value="stock">
                                                <select id="{{ $field }}" name="{{ $field }}"
                                                    class="component-hidden-field">
                                                    <option value=""></option>
                                                    @foreach ($comp['items'] as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->nombre . ' — ' . ($item->deposito->nombre ?? 'sin depósito') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="{{ $field }}_nombre"
                                                    value="">

                                                <select name="{{ $field }}_deposito_origen"
                                                    class="component-hidden-field">
                                                    <option value="">Depósito de origen desconocido</option>
                                                    @foreach ($depositos as $deposito)
                                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" name="{{ $field }}_cantidad"
                                                    value="1">
                                                <input type="hidden" name="{{ $field }}_motivo"
                                                    value="">
                                                <input type="hidden" name="{{ $field }}_observaciones"
                                                    value="">
                                            </div>
                                        @endforeach

                                        {{-- Discos --}}
                                        <div class="hu-component-group" id="input-container-1"
                                            data-component-group="discos">
                                            <div class="hu-component-row hu-component-row-multi"
                                                data-component-key="Disco" data-component-kind="multi"
                                                data-group="discos">
                                                <div class="hu-component-icon"><span
                                                        class="material-symbols-outlined">hard_drive</span></div>
                                                <div class="hu-component-label">
                                                    <div>Discos</div><small>Opcional</small>
                                                    <div class="hu-component-selected" data-selection-label="discos">
                                                        Sin seleccionar</div>
                                                </div>
                                                <div class="hu-component-action">
                                                    <button type="button" class="hu-component-open"
                                                        data-component-key="Disco" data-component-kind="multi"
                                                        data-group="discos">
                                                        <span class="material-symbols-outlined">add_circle</span>
                                                        <span class="component-open-label">Seleccionar
                                                            componente</span>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="discos1_modo[]" value="stock">
                                                <select name="discos1[]" class="component-hidden-field">
                                                    <option value=""></option>
                                                    @foreach ($discos as $disco)
                                                        <option value="{{ $disco->id }}"
                                                            data-stock="{{ $disco->stock }}">
                                                            {{ $disco->nombre . ' - ' . $disco->tipo->nombre . ' — ' . ($disco->deposito->nombre ?? 'sin depósito') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="discos1_nombre[]" value="">

                                                <select name="discos1_deposito_origen[]"
                                                    class="component-hidden-field">
                                                    <option value="">Depósito de origen desconocido</option>
                                                    @foreach ($depositos as $deposito)
                                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" name="discos1_cantidad[]" value="1">
                                                <input type="hidden" name="discos1_motivo[]" value="">
                                                <input type="hidden" name="discos1_observaciones[]" value="">
                                                <button type="button" class="hu-multi-remove remove-multi-row"
                                                    style="display:none;" aria-label="Quitar disco"><span
                                                        class="material-symbols-outlined">close</span></button>
                                            </div>
                                            <button type="button" class="hu-add-multi add-disco-row"><span
                                                    class="material-symbols-outlined">add</span> Agregar otro
                                                disco</button>
                                        </div>

                                        {{-- RAMs --}}
                                        <div class="hu-component-group" id="material-container-1"
                                            data-component-group="rams">
                                            <div class="hu-component-row hu-component-row-multi"
                                                data-component-key="RAM" data-component-kind="multi"
                                                data-group="rams">
                                                <div class="hu-component-icon"><span
                                                        class="material-symbols-outlined">memory_alt</span></div>
                                                <div class="hu-component-label">
                                                    <div>RAMs</div><small>Opcional</small>
                                                    <div class="hu-component-selected" data-selection-label="rams">Sin
                                                        seleccionar</div>
                                                </div>
                                                <div class="hu-component-action">
                                                    <button type="button" class="hu-component-open"
                                                        data-component-key="RAM" data-component-kind="multi"
                                                        data-group="rams">
                                                        <span class="material-symbols-outlined">add_circle</span>
                                                        <span class="component-open-label">Seleccionar
                                                            componente</span>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="rams1_modo[]" value="stock">
                                                <select name="rams1[]" class="component-hidden-field">
                                                    <option value=""></option>
                                                    @foreach ($rams as $ram)
                                                        <option value="{{ $ram->id }}"
                                                            data-stock="{{ $ram->stock }}">
                                                            {{ $ram->nombre . ' — ' . ($ram->deposito->nombre ?? 'sin depósito') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="rams1_nombre[]" value="">

                                                <select name="rams1_deposito_origen[]" class="component-hidden-field">
                                                    <option value="">Depósito de origen desconocido</option>
                                                    @foreach ($depositos as $deposito)
                                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" name="rams1_cantidad[]" value="1">
                                                <input type="hidden" name="rams1_motivo[]" value="">
                                                <input type="hidden" name="rams1_observaciones[]" value="">
                                                <button type="button" class="hu-multi-remove remove-multi-row"
                                                    style="display:none;" aria-label="Quitar RAM"><span
                                                        class="material-symbols-outlined">close</span></button>
                                            </div>
                                            <button type="button" class="hu-add-multi add-ram-row"><span
                                                    class="material-symbols-outlined">add</span> Agregar otra
                                                RAM</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Vista interna del wizard para seleccionar/configurar un componente. No es un modal. --}}
                            <div id="component-detail-view" style="display:none;">
                                <div class="wizard-panel hu-component-detail-panel">
                                    <button type="button" class="hu-component-back" id="component-detail-back">
                                        <span class="material-symbols-outlined">arrow_back</span> Componentes
                                    </button>
                                    <div class="hu-component-detail-heading">
                                        <div class="wizard-panel-title" id="component-detail-title">Seleccionar
                                            componente</div>
                                        <div class="wizard-panel-help mb-0" id="component-detail-subtitle">Placa madre
                                        </div>
                                    </div>

                                    <div class="hu-component-tabs" role="tablist">
                                        <button type="button" class="hu-component-tab is-active"
                                            data-detail-mode="stock">Desde stock</button>
                                        <button type="button" class="hu-component-tab"
                                            data-detail-mode="registrar">Registrar nuevo</button>
                                        <button type="button" class="hu-component-tab"
                                            data-detail-mode="no-identificada">No identificado</button>
                                    </div>

                                    <div class="hu-component-detail-content">
                                        <div class="hu-detail-panel" data-detail-panel="stock">
                                            <div class="hu-component-search-wrap">
                                                <span class="material-symbols-outlined">search</span>
                                                <input type="search" id="component-detail-search"
                                                    class="form-control"
                                                    placeholder="Buscar por componente o depósito..."
                                                    autocomplete="off">
                                            </div>
                                            <div class="table-responsive hu-component-table-wrap">
                                                <table class="table table-sm align-middle mb-0 hu-component-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:42px;"></th>
                                                            <th>Componente</th>
                                                            <th>Depósito</th>
                                                            <th class="text-end">Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="component-detail-stock-body"></tbody>
                                                </table>
                                            </div>
                                            <div id="component-detail-empty" class="text-muted text-center py-4"
                                                style="display:none;font-size:.78rem;">No hay componentes disponibles
                                                para mostrar.</div>
                                            <div id="component-detail-stock-replenishment" style="display:none;"
                                                class="mt-3">

                                                <div class="hu-component-info">
                                                    <span class="material-symbols-outlined">inventory</span>
                                                    <div>
                                                        <strong>Este componente está sin stock.</strong>
                                                        <div>
                                                            Para utilizarlo en esta PC primero se debe ingresar stock.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mt-1">
                                                    <div class="col-12 col-md-6">
                                                        <label for="component-detail-stock-quantity"
                                                            class="form-label fw-semibold" style="font-size:.78rem;">
                                                            Cantidad a ingresar <span class="hu-required">*</span>
                                                        </label>

                                                        <input type="number" id="component-detail-stock-quantity"
                                                            class="form-control" min="1" step="1"
                                                            value="1">
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label for="component-detail-stock-reason"
                                                            class="form-label fw-semibold" style="font-size:.78rem;">
                                                            Motivo <span class="hu-required">*</span>
                                                        </label>

                                                        <input type="text" id="component-detail-stock-reason"
                                                            class="form-control"
                                                            placeholder="Ej. Componente recibido">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hu-detail-panel" data-detail-panel="registrar"
                                            style="display:none;">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="component-detail-name" class="form-label fw-semibold"
                                                        style="font-size:.78rem;">
                                                        Nombre del componente <span class="hu-required">*</span>
                                                    </label>
                                                    <input type="text" id="component-detail-name"
                                                        class="form-control"
                                                        placeholder="Ej. NVIDIA GeForce GT 1030 2 GB">
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <label for="component-detail-origin"
                                                        class="form-label fw-semibold" style="font-size:.78rem;">
                                                        Depósito de origen <span class="hu-required">*</span>
                                                    </label>
                                                    <select id="component-detail-origin" class="form-control">
                                                        <option value="">Seleccioná un depósito</option>
                                                        @foreach ($depositos as $deposito)
                                                            <option value="{{ $deposito->id }}">
                                                                {{ $deposito->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <label for="component-detail-quantity"
                                                        class="form-label fw-semibold" style="font-size:.78rem;">
                                                        Cantidad <span class="hu-required">*</span>
                                                    </label>
                                                    <input type="number" id="component-detail-quantity"
                                                        class="form-control" min="1" step="1"
                                                        value="1">
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <label for="component-detail-reason"
                                                        class="form-label fw-semibold" style="font-size:.78rem;">
                                                        Motivo <span class="hu-required">*</span>
                                                    </label>
                                                    <input type="text" id="component-detail-reason"
                                                        class="form-control" placeholder="Ej. Componente recibido">
                                                </div>
                                            </div>

                                            <div class="hu-component-info mt-3">
                                                <span class="material-symbols-outlined">info</span>
                                                <span>
                                                    El componente se registrará como <strong>En uso</strong> y se
                                                    asignará directamente a esta PC.
                                                    El depósito indicado se guarda como origen.
                                                </span>
                                            </div>
                                        </div>

                                        <div class="hu-detail-panel" data-detail-panel="no-identificada"
                                            style="display:none;">
                                            <div class="hu-component-warning">
                                                <span class="material-symbols-outlined">warning</span>
                                                <div>
                                                    <strong>Este componente no está identificado</strong>
                                                    <div>Podés usar esta opción si la PC tiene el componente, pero no se
                                                        conoce la marca, el modelo o el número de serie. Podrás
                                                        identificarlo y reemplazarlo más adelante.</div>
                                                </div>
                                            </div>
                                            <label class="form-label fw-semibold mt-3"
                                                style="font-size:.78rem;">Observaciones <span
                                                    class="text-muted fw-normal">(opcional)</span></label>
                                            <textarea id="component-detail-observations" class="form-control" rows="4"
                                                placeholder="Ej.: Placa madre genérica, se desconoce el modelo, etc."></textarea>
                                        </div>
                                    </div>

                                    <div class="hu-component-detail-footer">
                                        <button type="button" class="btn btn-hu-outline"
                                            id="component-detail-cancel">Cancelar</button>
                                        <button type="button" class="btn btn-hu-confirm"
                                            id="component-detail-confirm">Seleccionar componente</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── PASO 4: Resumen ── --}}
                        <div id="add-step-4" style="display:none;">
                            <div class="wizard-panel">
                                <div class="wizard-panel-title">Resumen</div>
                                <div class="wizard-panel-help">Revisá la información antes de guardar la PC.</div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-5">
                                        <div class="hu-summary-section mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="hu-summary-label">Datos generales</span>
                                                <button type="button" class="btn btn-sm btn-link p-0"
                                                    data-summary-back="1"
                                                    style="color:var(--hu-azul);font-size:.7rem;">Editar</button>
                                            </div>
                                            <div class="mb-2">
                                                <div class="hu-summary-label">Nº inventario</div>
                                                <div class="hu-summary-value" id="summary-identificador">—</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="hu-summary-label">Nombre</div>
                                                <div class="hu-summary-value" id="summary-nombre">—</div>
                                            </div>
                                            <div>
                                                <div class="hu-summary-label">IPv4</div>
                                                <div class="hu-summary-value" id="summary-ip">—</div>
                                            </div>
                                        </div>
                                        <div class="hu-summary-section">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="hu-summary-label">Ubicación</span>
                                                <button type="button" class="btn btn-sm btn-link p-0"
                                                    data-summary-back="2"
                                                    style="color:var(--hu-azul);font-size:.7rem;">Editar</button>
                                            </div>
                                            <div class="mb-2">
                                                <div class="hu-summary-label">Estado</div>
                                                <div class="hu-summary-value" id="summary-estado">—</div>
                                            </div>
                                            <div>
                                                <div class="hu-summary-label">Ubicación asignada</div>
                                                <div class="hu-summary-value" id="summary-ubicacion">—</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <div class="hu-summary-section">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="hu-summary-label">Componentes</span>
                                                <button type="button" class="btn btn-sm btn-link p-0"
                                                    data-summary-back="3"
                                                    style="color:var(--hu-azul);font-size:.7rem;">Editar</button>
                                            </div>
                                            <div id="summary-components"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer con navegación --}}
                        <div class="modal-footer border-0 px-0 pb-0 d-flex justify-content-between">
                            <button type="button" id="btn-step-back" class="btn btn-hu-outline"
                                style="display:none;">
                                <span class="material-symbols-outlined"
                                    style="font-size:16px;vertical-align:middle;">arrow_back</span> Atrás
                            </button>
                            <div class="ms-auto d-flex gap-2">
                                <button type="button" id="btn-step-next" class="btn btn-hu-confirm">
                                    Siguiente <span class="material-symbols-outlined"
                                        style="font-size:16px;vertical-align:middle;">arrow_forward</span>
                                </button>
                                <button type="submit" id="btn-step-submit" class="btn btn-hu-gold"
                                    style="display:none;">
                                    <span class="material-symbols-outlined"
                                        style="font-size:16px;vertical-align:middle;">check</span> Guardar PC
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('success') === 'PC guardada correctamente.')
        <div class="modal fade" id="pcSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content hu-wizard"
                    style="border-radius:12px;border:0;box-shadow:0 12px 40px rgba(0,55,100,.16);">
                    <div class="modal-body text-center p-4 p-md-5">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width:64px;height:64px;border-radius:50%;background:#e8f6ee;color:#198754;">
                            <span class="material-symbols-outlined" style="font-size:38px;">check_circle</span>
                        </div>
                        <h5 class="fw-bold mb-2" style="color:var(--hu-azul);">PC agregada correctamente</h5>
                        <p class="text-muted mb-4" style="font-size:.8rem;">La PC se registró correctamente junto con
                            los componentes seleccionados.</p>
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                            <button type="button" class="btn btn-hu-outline" id="btnAddAnotherPc">Agregar otra PC</button>
                            <button type="button" class="btn btn-hu-confirm" data-bs-dismiss="modal">Ir al listado
                                de PCs</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: Editar PC --}}
    <div class="modal fade" id="editPcModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content hu-maintenance-modal">
                <div class="modal-header border-0 maintenance-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="maintenance-icon"><span class="material-symbols-outlined">computer</span></div>
                        <div>
                            <h5 class="modal-title maintenance-title">Mantenimiento de PC</h5>
                            <div class="maintenance-subtitle">Modificá los datos del equipo o registrá un mantenimiento.</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body maintenance-body p-0">
                    <form action="{{ route('edit_pc') }}" method="POST" id="editPcForm">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="editId" id="editId">
                        <div class="maintenance-layout">
                            <aside class="maintenance-sidebar">
                                <div class="maintenance-nav-title">Secciones</div>
                                <button type="button" class="maintenance-nav-link active" data-bs-target="#editPcDatos" aria-selected="true">
                                    <span class="material-symbols-outlined">description</span><span><strong>Datos generales</strong><small>Información básica</small></span>
                                </button>
                                <button type="button" class="maintenance-nav-link" data-bs-target="#editPcUbicacion">
                                    <span class="material-symbols-outlined">location_on</span><span><strong>Ubicación</strong><small>Área y depósito</small></span>
                                </button>
                                <button type="button" class="maintenance-nav-link" data-bs-target="#editPcComponentes">
                                    <span class="material-symbols-outlined">memory</span><span><strong>Componentes</strong><small>Hardware de la PC</small></span>
                                </button>
                                <button type="button" class="maintenance-nav-link" data-bs-target="#editPcMantenimiento">
                                    <span class="material-symbols-outlined">build</span><span><strong>Mantenimiento</strong><small>Registro y cambios</small></span>
                                </button>
                            </aside>
                            <main class="maintenance-content">
                                <div class="tab-content maintenance-tab-content">
                                    <div class="tab-pane fade show active" id="editPcDatos">                        {{-- Sección: Datos generales --}}
                        <div class="maintenance-section">
                            <div class="fw-bold mb-3" style="font-size:.7rem;color:#8a9099;text-transform:uppercase;letter-spacing:.07em;">
                                <span class="material-symbols-outlined" style="font-size:13px;vertical-align:middle;margin-right:4px;">info</span>Datos generales
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="editIdentificador" class="form-label fw-semibold" style="font-size:.78rem;">Nº inventario</label>
                                    <input type="text" class="form-control @error('editIdentificador') is-invalid @enderror"
                                        id="editIdentificador" name="editIdentificador" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="editNombre" class="form-label fw-semibold" style="font-size:.78rem;">Nombre</label>
                                    <input type="text" class="form-control @error('editNombre') is-invalid @enderror"
                                        id="editNombre" name="editNombre" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="editIp" class="form-label fw-semibold" style="font-size:.78rem;">IPv4</label>
                                    <input type="text" class="form-control @error('editIp') is-invalid @enderror"
                                        id="editIp" name="editIp" placeholder="192.168.x.x" required>
                                </div>
                            </div>
                        </div>


                                    </div>
                                    <div class="tab-pane fade" id="editPcUbicacion">
                                <div class="maintenance-status">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="maintenance-status-icon"><span class="material-symbols-outlined">computer</span></div>
                                        <div><div class="maintenance-status-title" id="editPcStatusTitle">Estado del equipo</div><div class="maintenance-status-text">La disponibilidad y asignación se administran desde este formulario.</div></div>
                                    </div>
                                    <label class="maintenance-status-action">
                                        <input class="form-check-input m-0" type="checkbox" id="editEn-uso" name="en-uso">
                                        <span>En uso — asignada a un área</span>
                                    </label>
                                </div>                        {{-- Sección: Ubicación --}}
                        <div class="maintenance-section">
                            <div class="fw-bold mb-3" style="font-size:.7rem;color:#8a9099;text-transform:uppercase;letter-spacing:.07em;">
                                <span class="material-symbols-outlined" style="font-size:13px;vertical-align:middle;margin-right:4px;">location_on</span>Ubicación
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6" id="edit-content-container">
                                    <div id="edit-area-select">
                                        <label for="editArea" class="form-label fw-semibold" style="font-size:.78rem;">Área</label>
                                        <select class="form-control @error('editArea') is-invalid @enderror"
                                            id="editArea" name="editArea" required>
                                            <option value="" disabled selected>Seleccioná un área</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mt-2" id="editNroConsul_div" style="display:none;">
                                            <label for="editNroConsul" class="form-label fw-semibold" style="font-size:.78rem;">Nº consultorio</label>
                                            <input type="text" class="form-control @error('editNroConsul') is-invalid @enderror"
                                                id="editNroConsul" name="editNroConsul">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="editDeposito" class="form-label fw-semibold" style="font-size:.78rem;">Depósito</label>
                                    <select class="form-control @error('editDeposito') is-invalid @enderror"
                                        id="editDeposito" name="editDeposito" required>
                                        <option value="" disabled selected>Seleccioná un depósito</option>
                                        @foreach ($depositos as $deposito)
                                            <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                                    </div>
                                    <div class="tab-pane fade" id="editPcComponentes">                        {{-- Sección: Componentes --}}
                        <div class="maintenance-section">
                            <div class="fw-bold mb-3" style="font-size:.7rem;color:#8a9099;text-transform:uppercase;letter-spacing:.07em;">
                                <span class="material-symbols-outlined" style="font-size:13px;vertical-align:middle;margin-right:4px;">memory</span>Componentes
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="editMotherboard" class="form-label fw-semibold" style="font-size:.78rem;">Placa madre <span style="color:#b02a37;">*</span></label>
                                    <select class="form-control @error('editMotherboard') is-invalid @enderror"
                                        id="editMotherboard" name="editMotherboard" required>
                                        <option value="" disabled selected>Seleccioná una placa madre</option>
                                        @foreach ($motherboardsMantenimiento as $motherboard)
                                            <option value="{{ $motherboard->id }}">{{ $motherboard->nombre . ' — ' . ($motherboard->deposito->nombre ?? 'sin depósito') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editProcesador" class="form-label fw-semibold" style="font-size:.78rem;">Procesador <span style="color:#b02a37;">*</span></label>
                                    <select class="form-control @error('editProcesador') is-invalid @enderror"
                                        id="editProcesador" name="editProcesador" required>
                                        <option value="" disabled selected>Seleccioná un procesador</option>
                                        @foreach ($procesadoresMantenimiento as $procesador)
                                            <option value="{{ $procesador->id }}">{{ $procesador->nombre . ' — ' . ($procesador->deposito->nombre ?? 'sin depósito') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editPlacavid" class="form-label fw-semibold" style="font-size:.78rem;">Placa de video</label>
                                    <select class="form-control @error('editPlacavid') is-invalid @enderror"
                                        id="editPlacavid" name="editPlacavid">
                                        <option value="" disabled selected>Seleccioná una placa de video</option>
                                        @foreach ($placasvidMantenimiento as $placavid)
                                            <option value="{{ $placavid->id }}">{{ $placavid->nombre . ' — ' . ($placavid->deposito->nombre ?? 'sin depósito') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editFuente" class="form-label fw-semibold" style="font-size:.78rem;">Fuente <span style="color:#b02a37;">*</span></label>
                                    <select class="form-control @error('editFuente') is-invalid @enderror"
                                        id="editFuente" name="editFuente" required>
                                        <option value="" disabled selected>Seleccioná una fuente</option>
                                        @foreach ($fuentesMantenimiento as $fuente)
                                            <option value="{{ $fuente->id }}">{{ $fuente->nombre . ' — ' . ($fuente->deposito->nombre ?? 'sin depósito') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:.78rem;">Discos</label>
                                    <div id="input-container-2">
                                        <div class="form-group input-group select">
                                            <select id="discos2-1" name="discos2[]" class="form-control" style="border-top-left-radius:6px;border-bottom-left-radius:6px;">
                                                <option value="" disabled selected>Seleccioná un disco</option>
                                                @foreach ($discosMantenimiento as $disco)
                                                    <option value="{{ $disco->id }}" data-stock="{{ $disco->stock }}">
                                                        @if ($disco->tipo->nombre == 'SDD') {{ $disco->nombre . ' - SDD - ' . ($disco->deposito->nombre ?? 'sin depósito') }} @endif
                                                        @if ($disco->tipo->nombre == 'HDD') {{ $disco->nombre . ' - HDD - ' . ($disco->deposito->nombre ?? 'sin depósito') }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-hu-outline add-input-disc maintenance-add-btn" type="button" aria-label="Agregar otro disco"><span class="material-symbols-outlined">add</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:.78rem;">RAMs</label>
                                    <div id="material-container-2">
                                        <div class="form-group input-group select">
                                            <select id="rams2-1" name="rams2[]" class="form-control" style="border-top-left-radius:6px;border-bottom-left-radius:6px;">
                                                <option value="" disabled selected>Seleccioná una RAM</option>
                                                @foreach ($ramsMantenimiento as $ram)
                                                    <option value="{{ $ram->id }}" data-stock="{{ $ram->stock }}">{{ $ram->nombre . ' — ' . ($ram->deposito->nombre ?? 'sin depósito') }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-hu-outline add-input-ram maintenance-add-btn" type="button" aria-label="Agregar otra RAM"><span class="material-symbols-outlined">add</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                                    </div>
                                    <div class="tab-pane fade" id="editPcMantenimiento">
                        {{-- Pill: Mantenimiento --}}
                        <div class="d-flex align-items-center gap-2 p-3 mb-2 maintenance-toggle"
                           >
                            <input class="form-check-input m-0 flex-shrink-0" type="checkbox" id="en-uso-mantenimineto" style="cursor:pointer;accent-color:#92400E;">
                            <label class="fw-semibold mb-0" for="en-uso-mantenimineto" style="font-size:.85rem;color:#92400E;cursor:pointer;">Se realizó mantenimiento</label>
                        </div>
                        <div id="div-detalle-mant">
                            <div class="mb-3" style="display:none;">
                                <label for="editDetalle" class="form-label fw-semibold" style="font-size:.78rem;">Detalle del mantenimiento</label>
                                <input type="text" class="form-control @error('editDetalle') is-invalid @enderror"
                                    id="editDetalle" name="editDetalle" placeholder="Ej: limpieza de ventiladores, reemplazo de pasta térmica">
                            </div>
                        </div>

                        {{-- Motivo --}}
                        <div class="mb-3">
                            <label for="editMotivo" class="form-label fw-semibold" style="font-size:.78rem;">Motivo del cambio <span style="color:#b02a37;">*</span></label>
                            <input type="text" class="form-control @error('editMotivo') is-invalid @enderror"
                                id="editMotivo" name="editMotivo" placeholder="Ej: traslado de área, actualización de datos" required>
                        </div>


                                    </div>
                                </div>
                            </main>
                        </div>
                        {{-- Footer --}}
                        <div class="d-flex justify-content-between gap-2 pt-1 pb-2 maintenance-footer">
                            <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" id="abrirRetirarRotoBtn" disabled>
                                <span class="material-symbols-outlined" style="font-size:15px;">build_circle</span>
                                Retirar componente como roto
                            </button>
                            <button type="submit" class="btn btn-hu">Guardar cambios</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Retirar componente como roto --}}
    <div class="modal fade" id="retirarRotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <div>
                        <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Retirar componente como roto
                        </h5>
                        <div id="retirarRotoPcLabel" class="text-muted" style="font-size:.8rem;"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('retirar_componente_roto') }}" method="POST" id="retirarRotoForm">
                    @csrf
                    <input type="hidden" name="pc_id" id="retirarRotoPcId">
                    <input type="hidden" name="pc_identificador" id="retirarRotoPcIdentificador">
                    <input type="hidden" name="pc_nombre" id="retirarRotoPcNombre">
                    <div class="modal-body">
                        <div class="rounded-3 p-3 mb-3"
                            style="background:#FEF2F2;border-left:4px solid #DC2626;font-size:.85rem;color:#7f1d1d;">
                            El componente se quitará de la PC y se registrará como <strong>Roto</strong>.
                            Si tiene depósito de origen, volverá a ese depósito.
                        </div>
                        <div class="mb-3">
                            <label for="retirarRotoComponente" class="form-label fw-semibold"
                                style="font-size:.85rem;">Componente</label>
                            <select class="form-control" name="componente_id" id="retirarRotoComponente" required>
                                <option value="" selected disabled>Seleccioná el componente</option>
                            </select>
                        </div>
                        <div>
                            <label for="retirarRotoMotivo" class="form-label fw-semibold"
                                style="font-size:.85rem;">Motivo</label>
                            <input type="text" class="form-control" name="motivo" id="retirarRotoMotivo"
                                maxlength="1000" required placeholder="Ej.: falla, daño físico, no enciende...">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-hu-outline" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Retirar como roto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Eliminar PC --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">¿Eliminar PC?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('delete_pc') }}" method="POST" id="deletePcForm">
                        @csrf
                        <input type="hidden" id="deleteId" name="deleteId">
                        <div class="mb-3">
                            <label for="removeMotivo" class="form-label fw-semibold"
                                style="font-size:.85rem;">Motivo</label>
                            <input type="text" class="form-control @error('removeMotivo') is-invalid @enderror"
                                id="removeMotivo" name="removeMotivo" required>
                        </div>
                        <div class="rounded-3 p-3 mb-3"
                            style="background:#FEF2F2;border-left:4px solid #DC2626;font-size:.85rem;color:#7f1d1d;">
                            Todos los componentes de esta PC volverán al stock en estado DISPONIBLE.
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-hu-outline"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Historia de la PC --}}
    <div class="modal fade" id="historyPcModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Historia de la PC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table_historias_pc" class="table hu-modern-table w-100">
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
    </div>

    {{-- ── Contenido principal ── --}}
    <div class="px-4 px-md-5 py-4" style="max-width:1400px;margin:0 auto;">
        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">

            {{-- Barra superior --}}
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold"
                        style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--hu-azul);">
                        Listado de PCs
                    </span>
                    @if (in_array($rolActual, ['Administrador', 'Super administrador', 'Tecnico'], true))
                        <button type="button" class="btn btn-hu-outline-dorado btn-sm" data-bs-toggle="modal"
                            data-bs-target="#infoAddPc" style="padding:2px 8px;font-size:.78rem;">
                            <span class="material-symbols-outlined"
                                style="font-size:15px;vertical-align:middle;">info</span>
                        </button>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm"
                        style="max-width:220px;border:1px solid #ced4da;border-radius:.375rem;">
                        <span class="input-group-text" style="background:#fff;border:0;">
                            <span class="material-symbols-outlined"
                                style="font-size:15px; color:var(--hu-azul);">search</span>
                        </span>
                        <input type="text" id="search_pc" class="form-control" placeholder="Buscar..."
                            style="border:0;box-shadow:none;">
                    </div>
                    @if (in_array($rolActual, ['Administrador', 'Super administrador', 'Tecnico'], true))
                        <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addModal" style="min-width: 150px; white-space: nowrap;">
                            <span class="material-symbols-outlined"
                                style="font-size:16px;vertical-align:middle;">add</span>
                            Armar PC
                        </button>
                    @endif
                </div>
            </div>

            {{-- Grid de cards --}}
            <div id="div-pc" class="d-flex flex-wrap gap-3 justify-content-start">
                @foreach ($pcs as $pc)
                    <div class="pc-card" data-nombre="{{ strtolower($pc->nombre) }}"
                        data-id="{{ strtolower($pc->identificador) }}"
                        style="width:250px;background:#fff;border:1px solid rgba(0,55,100,.12);border-radius:12px;padding:1rem;display:flex;flex-direction:column;gap:.4rem;transition:box-shadow .2s,transform .2s;cursor:pointer;"
                        onmouseenter="this.style.boxShadow='0 8px 24px rgba(0,55,100,.12)';this.style.transform='translateY(-2px)'"
                        onmouseleave="this.style.boxShadow='none';this.style.transform='none'">

                        <div class="d-flex align-items-center justify-content-between">
                            <span class="material-symbols-outlined"
                                style="font-size:32px;color:var(--hu-azul);">computer</span>
                            <span class="badge"
                                style="background:{{ $pc->area_id ? '#D1FAE5' : '#FEF3C7' }};color:{{ $pc->area_id ? '#065F46' : '#92400E' }};font-size:.7rem;font-weight:600;border-radius:6px;">
                                {{ $pc->area_id ? 'En uso' : 'Depósito' }}
                            </span>
                        </div>

                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.9rem;color:var(--hu-azul);line-height:1.2;">
                                {{ $pc->nombre }}</div>
                            <div style="font-size:.78rem;color:var(--hu-texto);"><span class="text-muted">Inv:</span>
                                {{ $pc->identificador }}</div>
                            <div style="font-size:.78rem;color:var(--hu-texto);"><span class="text-muted">IP:</span>
                                {{ $pc->ip ?? '—' }}</div>
                            @if ($pc->area_id)
                                <div style="font-size:.78rem;color:var(--hu-texto);"><span
                                        class="text-muted">Área:</span> {{ $pc->area->nombre ?? '—' }}</div>
                            @else
                                <div style="font-size:.78rem;color:var(--hu-texto);"><span
                                        class="text-muted">Depósito:</span> {{ $pc->deposito->nombre ?? '—' }}</div>
                            @endif
                        </div>

                        <div class="d-flex gap-1 mt-auto pt-2">
                            <button type="button" class="btn btn-hu btn-sm flex-fill infoBtn" data-bs-toggle="modal"
                                data-bs-target="#infoPcModal" data-id="{{ $pc->id }}"
                                data-identificador="{{ $pc->identificador }}" data-nombre="{{ $pc->nombre }}"
                                data-ip="{{ $pc->ip }}"
                                data-area="{{ $pc->area->nombre ?? 'Área no asignada' }}"
                                data-deposito="{{ $pc->deposito->nombre ?? 'Depósito no asignado' }}"
                                data-enuso="{{ $pc->area && $pc->area->nombre ? 'true' : 'false' }}"
                                data-mother="{{ $pc->componentes->firstWhere('tipo_id', 5)->nombre ?? '—' }}"
                                data-proce="{{ $pc->componentes->firstWhere('tipo_id', 4)->nombre ?? '—' }}"
                                data-fuente="{{ $pc->componentes->firstWhere('tipo_id', 2)->nombre ?? '—' }}"
                                data-placavid="{{ $pc->componentes->firstWhere('tipo_id', 7)->nombre ?? '—' }}"
                                data-discos="{{ $pc->componentes->whereIn('tipo_id', [6, 3])->pluck('nombre')->implode(', ') }}"
                                data-rams="{{ $pc->componentes->where('tipo_id', 1)->pluck('nombre')->implode(', ') }}">
                                <span class="material-symbols-outlined" style="font-size:14px;">info</span>
                            </button>

                            @if (in_array($rolActual, ['Administrador', 'Super administrador', 'Tecnico'], true))
                                <button type="button" class="btn btn-hu btn-sm flex-fill maintenanceBtn"
                                    data-bs-toggle="modal" data-bs-target="#editPcModal"
                                    data-id="{{ $pc->id }}" data-identificador="{{ $pc->identificador }}"
                                    data-nombre="{{ $pc->nombre }}" data-ip="{{ $pc->ip }}"
                                    data-area="{{ $pc->area_id }}" data-deposito="{{ $pc->deposito_id }}"
                                    data-enuso="{{ $pc->area_id ? 'true' : 'false' }}"
                                    data-mother='@json($pc->componentes->firstWhere('tipo_id', 5))'
                                    data-proce='@json($pc->componentes->firstWhere('tipo_id', 4))'
                                    data-fuente='@json($pc->componentes->firstWhere('tipo_id', 2))'
                                    data-placavid='@json($pc->componentes->firstWhere('tipo_id', 7))'
                                    data-discosids="{{ $pc->componentes->whereIn('tipo_id', [6, 3])->pluck('id')->implode(', ') }}"
                                    data-discosobj='@json($pc->componentes->whereIn('tipo_id', [6, 3]))'
                                    data-ramsids="{{ $pc->componentes->where('tipo_id', 1)->pluck('id')->implode(', ') }}"
                                    data-ramsobj='@json($pc->componentes->where('tipo_id', 1))'>
                                    <span class="material-symbols-outlined" style="font-size:14px;">build</span>
                                </button>
                            @endif

                            <button type="button" class="btn btn-hu btn-sm flex-fill historyBtn"
                                data-bs-toggle="modal" data-bs-target="#historyPcModal"
                                data-id="{{ $pc->id }}" data-nro_inv="{{ $pc->identificador }}"
                                data-nombre="{{ $pc->nombre }}" data-tipo="PC">
                                <span class="material-symbols-outlined" style="font-size:14px;">history</span>
                            </button>

                            @if ($rolActual === 'Super administrador')
                                <button type="button" class="btn btn-danger btn-sm flex-fill"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    data-id="{{ $pc->id }}">
                                    <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if ($pcs->isEmpty())
                    <div class="text-center text-muted py-5 w-100" style="font-size:.9rem;">
                        <span class="material-symbols-outlined d-block mb-2"
                            style="font-size:2.5rem;">computer</span>
                        No hay PCs registradas.
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

    {{-- Interacciones propias de PCs: edición dinámica de componentes y sincronización de campos de ubicación. --}}
    @push('scripts')
        <script>
            $(document).ready(function() {

                let componentesPcActuales = [];

                // Agrega dinámicamente un campo para registrar otro disco en la configuración de la PC.
                function addInputDisc(button) {
                    const $group = $(button).closest('.input-group');
                    if (!$group.length) return;

                    const $clone = $group.clone(false);
                    $clone.find('select').val('').removeAttr('id');
                    $clone.find('option.deleteable-option').remove();

                    // La primera fila conserva el botón "+" para seguir agregando discos.
                    // Las filas adicionales tienen un botón para quitarlas.
                    $clone.find('.add-input-disc').replaceWith(
                        '<button class="btn btn-hu-outline remove-input-disc-modal2 maintenance-remove-btn" type="button" aria-label="Quitar disco" title="Quitar disco">' +
                        '<span class="material-symbols-outlined">delete</span></button>'
                    );

                    $group.closest('#input-container-2').append($clone);
                    updateOptionsDiscModal2();
                }

                function addInputRam(button) {
                    const $group = $(button).closest('.input-group');
                    if (!$group.length) return;

                    const $clone = $group.clone(false);
                    $clone.find('select').val('').removeAttr('id');
                    $clone.find('option.deleteable-option2').remove();

                    // La primera fila conserva el botón "+" para seguir agregando RAMs.
                    // Las filas adicionales tienen un botón para quitarlas.
                    $clone.find('.add-input-ram').replaceWith(
                        '<button class="btn btn-hu-outline remove-input-ram-modal2 maintenance-remove-btn" type="button" aria-label="Quitar RAM" title="Quitar RAM">' +
                        '<span class="material-symbols-outlined">delete</span></button>'
                    );

                    $group.closest('#material-container-2').append($clone);
                    updateOptionsRamModal2();
                }

                // ── Modal editar: cargar datos ──────────────────────────────────────
                $('#editPcModal').on('show.bs.modal', function(event) {
                    var modal = $(this);
                    var container = modal.find('#input-container-2');
                    var inputs = container.find('.select');
                    if (inputs.length > 1) {
                        inputs.not(':first').remove();
                    }

                    var materialContainer = modal.find('#material-container-2');
                    var materials = materialContainer.find('.select');
                    if (materials.length > 1) {
                        materials.not(':first').remove();
                    }

                    var button = $(event.relatedTarget);
                    var Id = button.data('id');
                    var Identificador = button.data('identificador');
                    var Nombre = button.data('nombre');
                    var Ip = button.data('ip');
                    var Area_id = button.data('area');
                    var Deposito_id = button.data('deposito');
                    var enUso = button.data('enuso');
                    var Motherboard = button.data('mother');
                    var Procesador = button.data('proce');
                    var Fuente = button.data('fuente');
                    var Placavid = button.data('placavid');

                    var discosIds = button.data('discosids') ? button.data('discosids').toString().split(', ') :
                        [];
                    var DiscosObj = button.data('discosobj') || {};
                    var DiscosArray = Object.values(DiscosObj);
                    var cant_discos = discosIds.length;

                    var ramsIds = button.data('ramsids') ? button.data('ramsids').toString().split(', ') : [];
                    var RamsObj = button.data('ramsobj') || {};
                    var RamsArray = Object.values(RamsObj);
                    var cant_rams = ramsIds.length;

                    componentesPcActuales = [Motherboard, Procesador, Fuente, Placavid]
                        .concat(DiscosArray, RamsArray)
                        .filter(function(componente) {
                            return componente && componente.id;
                        });

                    var $retirarRotoBtn = modal.find('#abrirRetirarRotoBtn');
                    $retirarRotoBtn.prop('disabled', componentesPcActuales.length === 0);
                    $retirarRotoBtn.data('pc-id', Id);
                    $retirarRotoBtn.data('pc-identificador', Identificador);
                    $retirarRotoBtn.data('pc-nombre', Nombre);

                    modal.find('#editEn-uso').prop('checked', enUso);
                    if (!enUso) {
                        modal.find('#editDeposito').prop('disabled', false);
                        modal.find('#editArea').prop('disabled', true);
                    } else {
                        modal.find('#editDeposito').prop('disabled', true);
                        modal.find('#editArea').prop('disabled', false);
                    }
                    modal.find('#editNombre').val(Nombre);
                    modal.find('#editId').val(Id);
                    modal.find('#editIdentificador').val(Identificador);
                    modal.find('#editIp').val(Ip);
                    modal.find('#editArea').val(Area_id);
                    modal.find('#editDeposito').val(Deposito_id);

                    var motherboardToRemove = Motherboard ? Motherboard.nombre : null;
                    var procesadorToRemove = Procesador ? Procesador.nombre : null;
                    var fuenteToRemove = Fuente ? Fuente.nombre : null;
                    var placavidToRemove = Placavid ? Placavid.nombre : null;

                    ['#editMotherboard', '#editProcesador', '#editFuente', '#editPlacavid'].forEach(function(
                        sel) {
                        modal.find(sel + ' option').each(function() {
                            if ($(this).text().endsWith(' - Actual')) {
                                $(this).remove();
                            }
                        });
                    });

                    if (Motherboard) {
                        if (Motherboard.stock == 0 || Motherboard.estado_id == 5) {
                            modal.find('#editMotherboard').append('<option value="' + Motherboard.id +
                                '" selected>' + Motherboard.nombre + ' - Actual</option>');
                        } else {
                            modal.find('#editMotherboard').val(Motherboard.id);
                        }
                    }
                    if (Procesador) {
                        if (Procesador.stock == 0 || Procesador.estado_id == 5) {
                            modal.find('#editProcesador').append('<option value="' + Procesador.id +
                                '" selected>' + Procesador.nombre + ' - Actual</option>');
                        } else {
                            modal.find('#editProcesador').val(Procesador.id);
                        }
                    }
                    if (Fuente) {
                        if (Fuente.stock == 0 || Fuente.estado_id == 5) {
                            modal.find('#editFuente').append('<option value="' + Fuente.id + '" selected>' +
                                Fuente.nombre + ' - Actual</option>');
                        } else {
                            modal.find('#editFuente').val(Fuente.id);
                        }
                    }
                    if (Placavid) {
                        if (Placavid.stock == 0 || Placavid.estado_id == 5) {
                            modal.find('#editPlacavid').append('<option value="' + Placavid.id + '" selected>' +
                                Placavid.nombre + ' - Actual</option>');
                        } else {
                            modal.find('#editPlacavid').val(Placavid.id);
                        }
                    }

                    for (var i = 0; i < cant_discos - 1; i++) {
                        addInputDisc(modal.find('.add-input-disc').first());
                    }
                    for (var i = 0; i < cant_rams - 1; i++) {
                        addInputRam(modal.find('.add-input-ram').first());
                    }

                    container.find('.input-group').each(function(index) {
                        var select = $(this).find('select');
                        select.find('option.deleteable-option').remove();
                        if (index < cant_discos) {
                            if (DiscosArray[index].stock == 0 || DiscosArray[index].estado_id == 5) {
                                select.append('<option value="' + DiscosArray[index].id +
                                    '" class="deleteable-option" selected>' + DiscosArray[index]
                                    .nombre + ' - ' + DiscosArray[index].tipo.nombre + '</option>');
                            } else {
                                select.val(discosIds[index]);
                            }
                        }
                    });

                    materialContainer.find('.input-group').each(function(index) {
                        var select = $(this).find('select');
                        select.find('option.deleteable-option2').remove();
                        if (index < cant_rams) {
                            if (RamsArray[index].stock == 0 || RamsArray[index].estado_id == 5) {
                                select.append('<option value="' + RamsArray[index].id +
                                    '" class="deleteable-option2" selected>' + RamsArray[index]
                                    .nombre + ' ' + RamsArray[index].tipo.nombre + '</option>');
                            } else {
                                select.val(ramsIds[index]);
                            }
                        }
                    });

                    updateDiscos();
                    updateRams();
                });

                $('#editEn-uso').on('change', function() {
                    var modal = $('#editPcModal');
                    var isChecked = $(this).is(':checked');
                    if (!isChecked) {
                        modal.find('#editDeposito').prop('disabled', false);
                        modal.find('#editArea').prop('disabled', true);
                        modal.find('#editDeposito, #editArea').val(null);
                        $('#editNroConsul_div').css('display', 'none').attr('required', false);
                    } else {
                        modal.find('#editDeposito').prop('disabled', true);
                        modal.find('#editArea').prop('disabled', false);
                        modal.find('#editArea, #editDeposito').val(null);
                    }
                });

                // ── Modal eliminar ──────────────────────────────────────────────────
                $('#deleteModal').on('show.bs.modal', function(event) {
                    $(this).find('#deleteId').val($(event.relatedTarget).data('id'));
                });

                // ── Modal info PC ───────────────────────────────────────────────────
                $('#infoPcModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var modal = $(this);
                    var enUso = button.data('enuso');
                    modal.find('#idenInfo').text(button.data('identificador'));
                    modal.find('#ipInfo').text(button.data('ip'));
                    modal.find('#nombreInfo').text(button.data('nombre'));
                    if (enUso == true || enUso === 'true') {
                        modal.find('#titleAsig').text('Área:');
                        modal.find('#infoAsig').text(button.data('area'));
                    } else {
                        modal.find('#titleAsig').text('Depósito:');
                        modal.find('#infoAsig').text(button.data('deposito'));
                    }
                    modal.find('#motherInfo').text(button.data('mother'));
                    modal.find('#proceInfo').text(button.data('proce'));
                    modal.find('#fuenteInfo').text(button.data('fuente'));
                    modal.find('#placavidInfo').text(button.data('placavid'));
                    modal.find('#discosInfo').text(button.data('discos'));
                    modal.find('#ramsInfo').text(button.data('rams'));
                });

                @php
                    $catalogBuilder = function ($items, $includeTipo = false) {
                        return $items
                            ->map(function ($item) use ($includeTipo) {
                                $nombre = $item->nombre;
                                if ($includeTipo) {
                                    $nombre .= ' - ' . ($item->tipo->nombre ?? '');
                                }

                                return [
                                    'id' => $item->id,
                                    'nombre' => $nombre,
                                    'deposito' => $item->deposito->nombre ?? 'Sin depósito',
                                    'stock' => (int) $item->stock,
                                    'estado_id' => (int) $item->estado_id,
                                ];
                            })
                            ->values();
                    };

                    $motherboardCatalog = $catalogBuilder($motherboards);
                    $procesadorCatalog = $catalogBuilder($procesadores);
                    $fuenteCatalog = $catalogBuilder($fuentes);
                    $placavidCatalog = $catalogBuilder($placasvid);
                    $discoCatalog = $catalogBuilder($discos, true);
                    $ramCatalog = $catalogBuilder($rams);
                @endphp

                // ── Wizard: Armar PC ─────────────────────────────────────────────────
                var currentStep = 1;

                // Muestra los campos de ubicación correspondientes al modo de asignación seleccionado.
                function setLocationMode(mode) {
                    var enUso = mode === 'uso';
                    $('#location-use-card').toggleClass('is-selected', enUso);
                    $('#location-storage-card').toggleClass('is-selected', !enUso);
                    $('#location-area-panel').toggle(enUso);
                    $('#location-storage-panel').toggle(!enUso);
                    $('#addEnUso').val(enUso ? '1' : '0');
                    $('#addArea').prop('disabled', !enUso).prop('required', enUso);
                    $('#addDeposito').prop('disabled', enUso).prop('required', !enUso);
                    if (enUso) {
                        $('#addDeposito').val('');
                    } else {
                        $('#addArea').val('');
                        $('#addNroConsul').val('');
                        $('#addNroConsul_div').hide();
                    }
                }

                function updateWizardStepper(step) {
                    $('.wizard-step').each(function() {
                        var n = parseInt($(this).data('wizard-step'), 10);
                        $(this).removeClass('is-active is-complete');
                        if (n < step) $(this).addClass('is-complete');
                        if (n === step) $(this).addClass('is-active');
                        if (n < step) $(this).find('.wizard-dot').text('✓');
                        else $(this).find('.wizard-dot').text(n);
                    });
                }

                function componentModeLabel(mode) {
                    if (mode === 'stock') return 'Desde stock';
                    if (mode === 'sin-stock') return 'Ingreso de stock';
                    if (mode === 'registrar') return 'Registrar nuevo';
                    return 'No identificado';
                }

                const componentCatalogs = {
                    Motherboard: @json($motherboardCatalog),
                    Procesador: @json($procesadorCatalog),
                    Fuente: @json($fuenteCatalog),
                    Placavid: @json($placavidCatalog),
                    Disco: @json($discoCatalog),
                    RAM: @json($ramCatalog)
                };

                let activeComponentTarget = null;
                let activeComponentMode = 'stock';

                function getTargetRow(target) {
                    if (!target) return $();
                    if (target.kind === 'singular') return $(
                        '.hu-component-row[data-component-kind="singular"][data-field="' + target.field + '"]');
                    return target.row;
                }

                function getTargetFieldName(target, suffix) {
                    if (target.kind === 'singular') return target.field + suffix;
                    return target.group === 'discos' ? 'discos1' + suffix + '[]' : 'rams1' + suffix + '[]';
                }

                function getTargetValue(target, suffix) {
                    var row = getTargetRow(target);
                    var name = getTargetFieldName(target, suffix);
                    return row.find('[name="' + name + '"]').val() || '';
                }

                function setTargetValue(target, suffix, value) {
                    var row = getTargetRow(target);
                    var name = getTargetFieldName(target, suffix);
                    row.find('[name="' + name + '"]').val(value || '');
                }

                function targetCatalogKey(target) {
                    return target.kind === 'singular' ? target.key : (target.group === 'discos' ? 'Disco' : 'RAM');
                }

                function targetLabel(target) {
                    if (target.kind === 'singular') return ({
                        Motherboard: 'Placa madre',
                        Procesador: 'Procesador',
                        Fuente: 'Fuente',
                        Placavid: 'Placa de video'
                    })[target.key];
                    return target.group === 'discos' ? 'Disco' : 'RAM';
                }

                function targetIsRequired(target) {
                    return target.kind === 'singular' && ['Motherboard', 'Procesador', 'Fuente'].includes(target.key);
                }

                // ── Helpers para leer el estado de una fila del paso 3 ──────────────
                function rowTarget($row) {
                    return $row.data('component-kind') === 'singular' ? {
                        kind: 'singular',
                        key: $row.data('component-key'),
                        field: $row.data('field')
                    } : {
                        kind: 'multi',
                        group: $row.data('group'),
                        row: $row
                    };
                }

                function getRowMode($row) {
                    return $row.find('input[name$="_modo"], input[name$="_modo[]"]').first().val() || 'stock';
                }

                // Devuelve { mode, text, detail } describiendo lo que el usuario eligió.
                // Cada modo se describe por separado: sin-stock NO es no-identificada.
                function describeRowSelection($row) {
                    var target = rowTarget($row);
                    var mode = getRowMode($row);
                    var text = '';
                    var detail = '';

                    if (mode === 'stock' || mode === 'sin-stock') {
                        text = $row.find('[name="' + getTargetFieldName(target, '') + '"] option:selected')
                            .text().trim();
                        if (text === 'Selecciona una opción') text = '';
                        if (mode === 'sin-stock' && text) {
                            var cant = getTargetValue(target, '_cantidad');
                            var motivo = getTargetValue(target, '_motivo').trim();
                            detail = 'Se ingresarán ' + cant + (Number(cant) === 1 ? ' unidad' : ' unidades') +
                                (motivo ? ' · Motivo: ' + motivo : '');
                        }
                    } else if (mode === 'registrar') {
                        text = getTargetValue(target, '_nombre').trim();
                    } else {
                        text = 'No identificado';
                    }

                    return {
                        mode: mode,
                        text: text,
                        detail: detail
                    };
                }

                function countSelectedStock(catalogKey, exceptTarget) {
                    var counts = {};
                    $('.hu-component-row').each(function() {
                        var $row = $(this);
                        var isSame = exceptTarget && getTargetRow(exceptTarget).is($row);
                        if (isSame) return;
                        var key = $row.data('component-kind') === 'singular' ?
                            $row.data('component-key') :
                            ($row.data('group') === 'discos' ? 'Disco' : 'RAM');
                        if (key !== catalogKey) return;
                        var mode = $row.find('input[name$="_modo"], input[name$="_modo[]"]').first().val() ||
                            'stock';
                        if (mode !== 'stock') return;
                        var value = $row.find('select[name="' + ($row.data('component-kind') === 'singular' ?
                            'add' + $row.data('component-key') : ($row.data('group') === 'discos' ?
                                'discos1[]' : 'rams1[]')) + '"]').val();
                        if (value) counts[value] = (counts[value] || 0) + 1;
                    });
                    return counts;
                }

                function renderComponentStockOptions() {
                    var catalogKey = targetCatalogKey(activeComponentTarget);
                    var items = componentCatalogs[catalogKey] || [];
                    var selectedId = getTargetValue(activeComponentTarget, activeComponentTarget.kind === 'singular' ?
                        '' : '');
                    var counts = countSelectedStock(catalogKey, activeComponentTarget);
                    var search = ($('#component-detail-search').val() || '').trim().toLowerCase();
                    var $body = $('#component-detail-stock-body').empty();
                    var visible = 0;

                    items.forEach(function(item) {
                        var haystack = (item.nombre + ' ' + item.deposito).toLowerCase();
                        if (search && !haystack.includes(search)) return;
                        var available = item.stock - (counts[item.id] || 0);
                        var isSelected = String(item.id) === String(selectedId);
                        var isSinStock = item.estado_id === 7 || available <= 0;

                        if (!isSinStock && !isSelected && available <= 0) return;
                        visible++;
                        var checked = isSelected ? ' checked' : '';
                        var disabled = (available <= 0 && !isSinStock && !isSelected) ? ' disabled' : '';
                        var rowClass = isSelected ? 'hu-stock-row is-selected' : 'hu-stock-row';
                        $body.append(
                            '<tr class="' + rowClass + '" data-component-id="' + item.id + '">' +
                            '<td><input class="hu-stock-radio" type="radio" name="component_detail_stock" value="' +
                            item.id + '"' + checked + disabled + '></td>' +
                            '<td><span class="hu-stock-name">' + $('<div>').text(item.nombre).html() +
                            '</span></td>' +
                            '<td>' + $('<div>').text(item.deposito).html() + '</td>' +
                            '<td class="text-end">' + (isSinStock ?
                                '<span class="badge bg-secondary">Sin stock</span>' : available) + '</td>' +
                            '</tr>'
                        );
                    });
                    $('#component-detail-empty').toggle(visible === 0);
                }

                function setDetailMode(mode) {
                    activeComponentMode = mode;

                    $('.hu-component-tab').each(function() {
                        $(this).toggleClass('is-active', $(this).data('detail-mode') === mode);
                    });

                    $('.hu-detail-panel').each(function() {
                        $(this).toggle($(this).data('detail-panel') === mode);
                    });

                    $('#component-detail-confirm').text(mode === 'stock' ? 'Seleccionar componente' :
                        'Confirmar selección');

                    if (mode === 'stock') {
                        $('#component-detail-stock-replenishment').hide();
                        $('#component-detail-stock-quantity').val('1');
                        $('#component-detail-stock-reason').val('');
                        renderComponentStockOptions();
                    }
                }

                function openComponentSelector(target) {
                    activeComponentTarget = target;
                    var row = getTargetRow(target);
                    $('#component-detail-title').text('Seleccionar componente');
                    $('#component-detail-subtitle').text(targetLabel(target));
                    $('#component-detail-search').val('');

                    var mode = getRowMode(row);

                    // sin-stock se edita desde la pestaña "Desde stock", pero conserva
                    // su selección, cantidad y motivo al volver a abrir el selector.
                    var restoreSinStock = (mode === 'sin-stock');
                    if (restoreSinStock) {
                        mode = 'stock';
                    }

                    setDetailMode(mode);

                    var currentName = getTargetValue(target, target.kind === 'singular' ? '_nombre' : '_nombre');
                    var currentOrigin = getTargetValue(target, target.kind === 'singular' ? '_deposito_origen' :
                        '_deposito_origen');
                    var currentObs = getTargetValue(target, target.kind === 'singular' ? '_observaciones' :
                        '_observaciones');
                    var currentStock = getTargetValue(target, '');
                    var currentStockName = row.find('select[name="' + (target.kind === 'singular' ? target.field : (
                        target.group === 'discos' ? 'discos1[]' : 'rams1[]')) + '"] option:selected').text().trim();

                    var currentQuantity = getTargetValue(
                        activeComponentTarget,
                        '_cantidad'
                    );

                    var currentReason = getTargetValue(
                        activeComponentTarget,
                        '_motivo'
                    );

                    $('#component-detail-name').val(currentName || '');
                    $('#component-detail-origin').val(currentOrigin || '');
                    $('#component-detail-quantity').val(currentQuantity || '1');
                    $('#component-detail-reason').val(currentReason || '');
                    $('#component-detail-observations').val(currentObs || '');

                    $('#component-detail-stock-body').data('selected-name', currentStockName || '');
                    $('#component-list-view').hide();
                    $('#component-detail-view').show();
                    $('#btn-step-next, #btn-step-submit, #btn-step-back').hide();
                    setDetailMode(mode);

                    if (restoreSinStock) {
                        // setDetailMode('stock') limpia el panel de reposición; se restaura.
                        $('#component-detail-stock-quantity').val(currentQuantity || '1');
                        $('#component-detail-stock-reason').val(currentReason || '');
                        $('#component-detail-stock-replenishment').show();
                    }
                }

                function closeComponentSelector() {
                    activeComponentTarget = null;
                    $('#component-detail-view').hide();
                    $('#component-list-view').show();
                    $('#btn-step-back').toggle(currentStep > 1);
                    $('#btn-step-next').toggle(currentStep < 4);
                    $('#btn-step-submit').toggle(currentStep === 4);
                    refreshComponentRows();
                }

                function refreshComponentRows() {
                    $('.hu-component-row').each(function() {
                        var $row = $(this);
                        var info = describeRowSelection($row);
                        var text = info.text || 'Sin seleccionar';
                        var $summary = $row.find('.hu-component-selected');
                        $summary.text(text).toggleClass('is-selected', text !== 'Sin seleccionar');

                        var $detail = $row.find('.hu-component-selected-detail');
                        if (!$detail.length) {
                            $detail = $(
                                '<div class="hu-component-selected-detail text-muted" style="font-size:.72rem;"></div>'
                            );
                            $summary.after($detail);
                        }
                        $detail.text(info.detail).toggle(!!info.detail);

                        var $button = $row.find('.component-open-label');
                        $button.text(text === 'Sin seleccionar' ? 'Seleccionar componente' :
                            'Cambiar componente');
                    });
                }

                function validateComponentTarget(target) {
                    var mode = getTargetValue(target, '_modo');

                    if (mode === 'stock') {
                        var selectedId = getTargetValue(target, '');

                        // Un componente opcional sin seleccionar es válido (se omite).
                        return !!selectedId || !targetIsRequired(target);
                    }

                    if (mode === 'sin-stock') {
                        var selectedId = getTargetValue(target, '');
                        var cantidad = parseInt(getTargetValue(target, '_cantidad'), 10);
                        var motivo = getTargetValue(target, '_motivo').trim();

                        return !!selectedId &&
                            Number.isInteger(cantidad) &&
                            cantidad >= 1 &&
                            !!motivo;
                    }

                    if (mode === 'registrar') {
                        var nombre = getTargetValue(target, '_nombre').trim();
                        var origen = getTargetValue(target, '_deposito_origen');
                        var cantidad = parseInt(getTargetValue(target, '_cantidad'), 10);
                        var motivo = getTargetValue(target, '_motivo').trim();

                        return !!nombre &&
                            !!origen &&
                            Number.isInteger(cantidad) &&
                            cantidad >= 1 &&
                            !!motivo;
                    }

                    return true;
                }

                function buildComponentSummary() {
                    var components = [];
                    $('.hu-component-row').each(function() {
                        var $row = $(this);
                        var info = describeRowSelection($row);
                        if (!info.text) return;
                        components.push({
                            label: targetLabel(rowTarget($row)),
                            text: info.text,
                            detail: info.detail,
                            mode: componentModeLabel(info.mode)
                        });
                    });
                    var $summary = $('#summary-components').empty();
                    if (!components.length) {
                        $summary.html(
                            '<div class="text-muted" style="font-size:.78rem;">No se seleccionaron componentes.</div>'
                        );
                        return;
                    }
                    components.forEach(function(component) {
                        $summary.append(
                            '<div class="hu-summary-component"><div><div class="hu-summary-label">' + $(
                                '<div>').text(component.label).html() +
                            '</div><div class="hu-summary-value">' + $('<div>').text(component.text)
                            .html() + '</div>' +
                            (component.detail ? '<div class="text-muted" style="font-size:.72rem;">' +
                                $('<div>').text(component.detail).html() + '</div>' : '') +
                            '</div><span class="hu-pill">' + $('<div>').text(component.mode)
                            .html() + '</span></div>');
                    });
                }

                function updateSummary() {
                    $('#summary-identificador').text($('#addIdentificador').val().trim() || '—');
                    $('#summary-nombre').text($('#addNombre').val().trim() || '—');
                    $('#summary-ip').text($('#addIp').val().trim() || 'Sin asignar');
                    var enUso = $('#location-use').is(':checked');
                    $('#summary-estado').text(enUso ? 'Está en uso' : 'No está en uso');
                    if (enUso) {
                        var areaText = $('#addArea option:selected').text().trim();
                        if ($('#addNroConsul').val().trim()) areaText += ' ' + $('#addNroConsul').val().trim();
                        $('#summary-ubicacion').text(areaText || 'Sin área');
                    } else {
                        $('#summary-ubicacion').text($('#addDeposito option:selected').text().trim() || 'Sin depósito');
                    }
                    buildComponentSummary();
                }

                function validateStep(step) {
                    if (step === 1) {
                        var valid = true;
                        ['#addIdentificador', '#addNombre'].forEach(function(selector) {
                            var $field = $(selector),
                                ok = $field.val().trim() !== '';
                            $field.toggleClass('is-invalid', !ok);
                            if (!ok && valid) $field.focus();
                            valid = valid && ok;
                        });
                        return valid;
                    }
                    if (step === 2) {
                        var enUso = $('#location-use').is(':checked');
                        if (enUso) {
                            var okArea = !!$('#addArea').val();
                            $('#addArea').toggleClass('is-invalid', !okArea);
                            if (!okArea) $('#addArea').focus();
                            return okArea;
                        }
                        var okDeposito = !!$('#addDeposito').val();
                        $('#addDeposito').toggleClass('is-invalid', !okDeposito);
                        if (!okDeposito) $('#addDeposito').focus();
                        return okDeposito;
                    }
                    if (step === 3) {
                        var validComponents = true;
                        $('.hu-component-row').each(function() {
                            var $row = $(this),
                                target = $row.data('component-kind') === 'singular' ? {
                                    kind: 'singular',
                                    key: $row.data('component-key'),
                                    field: $row.data('field')
                                } : {
                                    kind: 'multi',
                                    group: $row.data('group'),
                                    row: $row
                                };
                            if (!validateComponentTarget(target))
                                validComponents = false;
                        });
                        $('.hu-component-row[data-component-kind="singular"]').each(function() {
                            var $row = $(this),
                                target = {
                                    kind: 'singular',
                                    key: $row.data('component-key'),
                                    field: $row.data('field')
                                };
                            if (targetIsRequired(target) && !validateComponentTarget(target)) validComponents =
                                false;
                        });
                        if (!validComponents) {
                            var first = $('.hu-component-row').filter(function() {
                                var $r = $(this),
                                    t = $r.data('component-kind') === 'singular' ? {
                                        kind: 'singular',
                                        key: $r.data('component-key'),
                                        field: $r.data('field')
                                    } : {
                                        kind: 'multi',
                                        group: $r.data('group'),
                                        row: $r
                                    };
                                return (t.kind === 'singular' && targetIsRequired(t) && !
                                    validateComponentTarget(t));
                            }).first();
                            if (first.length) openComponentSelector(first.data('component-kind') === 'singular' ? {
                                kind: 'singular',
                                key: first.data('component-key'),
                                field: first.data('field')
                            } : {
                                kind: 'multi',
                                group: first.data('group'),
                                row: first
                            });
                        }
                        return validComponents;
                    }
                    return true;
                }

                function goToStep(step) {
                    if ($('#component-detail-view').is(':visible')) closeComponentSelector();
                    currentStep = step;
                    $('#add-step-1, #add-step-2, #add-step-3, #add-step-4').hide();
                    $('#add-step-' + step).show();
                    $('#btn-step-back').toggle(step > 1);
                    $('#btn-step-next').toggle(step < 4);
                    $('#btn-step-submit').toggle(step === 4);
                    updateWizardStepper(step);
                    if (step === 4) updateSummary();
                }

                $('#location-use, #location-storage').on('change', function() {
                    setLocationMode($(this).val());
                });
                $('#addArea').on('change', function() {
                    if ($(this).val() == 27) $('#addNroConsul_div').show();
                    else {
                        $('#addNroConsul_div').hide();
                        $('#addNroConsul').val('');
                    }
                });

                $(document).on('click', '.hu-component-open', function() {
                    var $row = $(this).closest('.hu-component-row');
                    openComponentSelector($row.data('component-kind') === 'singular' ? {
                        kind: 'singular',
                        key: $row.data('component-key'),
                        field: $row.data('field')
                    } : {
                        kind: 'multi',
                        group: $row.data('group'),
                        row: $row
                    });
                });
                $(document).on('click', '.hu-component-tab', function() {
                    setDetailMode($(this).data('detail-mode'));
                });
                $('#component-detail-search').on('input', renderComponentStockOptions);
                $(document).on('click', '#component-detail-stock-body .hu-stock-row', function() {
                    $(this).find('.hu-stock-radio').prop('checked', true).trigger('change');
                });
                $(document).on('change', '#component-detail-stock-body .hu-stock-radio', function() {
                    var selectedId = String($(this).val());
                    var catalogKey = targetCatalogKey(activeComponentTarget);

                    var item = (componentCatalogs[catalogKey] || []).find(function(item) {
                        return String(item.id) === selectedId;
                    });

                    var catalogKey = targetCatalogKey(activeComponentTarget);
                    var counts = countSelectedStock(catalogKey, activeComponentTarget);
                    var available = item ? (item.stock - (counts[item.id] || 0)) : 1;
                    var isSinStock = item && (Number(item.estado_id) === 7 || available <= 0);

                    $('#component-detail-stock-body .hu-stock-row').removeClass('is-selected');
                    $(this).closest('.hu-stock-row').addClass('is-selected');
                    $('#component-detail-stock-replenishment').toggle(!!isSinStock);
                    $('#component-detail-confirm').text(isSinStock ? 'Confirmar selección' :
                        'Seleccionar componente');

                    if (!isSinStock) {
                        $('#component-detail-stock-quantity').val('1');
                        $('#component-detail-stock-reason').val('');
                    }
                });

                $('#component-detail-confirm').on('click', function() {
                    if (!activeComponentTarget) return;

                    var target = activeComponentTarget;
                    var mode = activeComponentMode;

                    if (mode === 'stock') {
                        var value = $(
                            '#component-detail-stock-body input[name="component_detail_stock"]:checked'
                        ).val();

                        if (!value) {
                            $('#component-detail-empty')
                                .text('Seleccioná un componente para continuar.')
                                .show();
                            return;
                        }

                        var catalogKey = targetCatalogKey(target);
                        var item = (componentCatalogs[catalogKey] || []).find(function(item) {
                            return String(item.id) === String(value);
                        });

                        var counts2 = countSelectedStock(catalogKey, target);
                        var available2 = item ? (item.stock - (counts2[item.id] || 0)) : 1;
                        var isSinStockItem = item && (Number(item.estado_id) === 7 || available2 <= 0);
                        var cantidad, motivo;

                        if (isSinStockItem) {
                            // El componente está en Sin Stock:
                            // se debe ingresar stock antes de asignarlo a la PC.
                            cantidad = parseInt($('#component-detail-stock-quantity').val(), 10);
                            motivo = $('#component-detail-stock-reason').val().trim();

                            $('#component-detail-stock-quantity').toggleClass('is-invalid',
                                !Number.isInteger(cantidad) || cantidad < 1);
                            $('#component-detail-stock-reason').toggleClass('is-invalid', !motivo);

                            if (!Number.isInteger(cantidad) || cantidad < 1) {
                                $('#component-detail-stock-quantity').focus();
                                return;
                            }

                            if (!motivo) {
                                $('#component-detail-stock-reason').focus();
                                return;
                            }
                        }

                        // Recién acá, con todo validado, se escribe la selección.
                        setTargetValue(target, '', value);
                        setTargetValue(target, '_nombre', '');
                        setTargetValue(target, '_deposito_origen', '');
                        setTargetValue(target, '_observaciones', '');

                        if (isSinStockItem) {
                            setTargetValue(target, '_modo', 'sin-stock');
                            setTargetValue(target, '_cantidad', cantidad);
                            setTargetValue(target, '_motivo', motivo);
                        } else {
                            // Componente que ya tiene stock disponible.
                            setTargetValue(target, '_modo', 'stock');
                            setTargetValue(target, '_cantidad', '1');
                            setTargetValue(target, '_motivo', '');
                        }

                    } else if (mode === 'registrar') {
                        var nombre = $('#component-detail-name').val().trim();
                        var origen = $('#component-detail-origin').val();
                        var cantidad = parseInt($('#component-detail-quantity').val(), 10);
                        var motivo = $('#component-detail-reason').val().trim();

                        $('#component-detail-name').toggleClass('is-invalid', !nombre);
                        $('#component-detail-origin').toggleClass('is-invalid', !origen);
                        $('#component-detail-quantity').toggleClass(
                            'is-invalid',
                            !Number.isInteger(cantidad) || cantidad < 1
                        );
                        $('#component-detail-reason').toggleClass('is-invalid', !motivo);

                        if (!nombre) {
                            $('#component-detail-name').focus();
                            return;
                        }

                        if (!origen) {
                            $('#component-detail-origin').focus();
                            return;
                        }

                        if (!Number.isInteger(cantidad) || cantidad < 1) {
                            $('#component-detail-quantity').focus();
                            return;
                        }

                        if (!motivo) {
                            $('#component-detail-reason').focus();
                            return;
                        }

                        setTargetValue(target, '', '');
                        setTargetValue(target, '_modo', 'registrar');
                        setTargetValue(target, '_nombre', nombre);
                        setTargetValue(target, '_deposito_origen', origen);
                        setTargetValue(target, '_cantidad', cantidad);
                        setTargetValue(target, '_motivo', motivo);
                        setTargetValue(target, '_observaciones', '');

                    } else {
                        setTargetValue(target, '', '');
                        setTargetValue(target, '_modo', 'no-identificada');
                        setTargetValue(target, '_nombre', '');
                        setTargetValue(target, '_deposito_origen', '');
                        setTargetValue(
                            target,
                            '_observaciones',
                            $('#component-detail-observations').val().trim()
                        );
                    }

                    closeComponentSelector();
                });

                $('#component-detail-back, #component-detail-cancel').on('click', closeComponentSelector);

                $('#btn-step-next').on('click', function() {
                    if (!validateStep(currentStep)) return;
                    goToStep(currentStep + 1);
                });
                $('#btn-step-back').on('click', function() {
                    goToStep(currentStep - 1);
                });
                $(document).on('click', '[data-summary-back]', function() {
                    goToStep(parseInt($(this).data('summary-back'), 10));
                });
                var pcSubmitting = false;

                $('#addPcForm').on('submit', function(e) {
                    if (currentStep !== 4) {
                        e.preventDefault();
                        if (validateStep(currentStep)) goToStep(currentStep + 1);
                        return;
                    }

                    if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
                        e.preventDefault();
                        if (!validateStep(1)) goToStep(1);
                        else if (!validateStep(2)) goToStep(2);
                        else goToStep(3);
                        return;
                    }

                    if (pcSubmitting) {
                        e.preventDefault();
                        return;
                    }

                    pcSubmitting = true;
                    var $submit = $('#btn-step-submit');
                    $submit.prop('disabled', true).addClass('disabled');
                    $submit.html('<span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">hourglass_top</span> Armando PC...');
                });

                $('#addModal').on('show.bs.modal', function() {
                    pcSubmitting = false;
                    $('#btn-step-submit').prop('disabled', false).removeClass('disabled').html('<span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">check</span> Guardar PC');
                    setLocationMode('uso');
                    goToStep(1);
                });
                $('#addModal').on('hidden.bs.modal', function() {
                    $('#addPcForm')[0].reset();
                    $('#input-container-1 .hu-component-row-multi:not(:first)').remove();
                    $('#material-container-1 .hu-component-row-multi:not(:first)').remove();
                    // form.reset() NO restaura los <input type="hidden"> modificados por JS:
                    // se limpian a mano para no arrastrar modo/nombre/cantidad de la carga anterior.
                    $('.hu-component-row').each(function() {
                        resetComponentRow($(this));
                    });
                    $('#component-detail-view').hide();
                    $('#component-list-view').show();
                    activeComponentTarget = null;
                    refreshComponentRows();
                    setLocationMode('uso');
                    goToStep(1);
                });

                // Deja una fila del paso 3 en su estado inicial (sin selección).
                function resetComponentRow($row) {
                    $row.find('select').val('');
                    $row.find('input[type="hidden"]').val('');
                    $row.find('input[name$="_modo"], input[name$="_modo[]"]').val('stock');
                    $row.find('input[name$="_cantidad"], input[name$="_cantidad[]"]').val('1');
                    $row.find('.hu-component-selected-detail').remove();
                }

                // Clona la primera fila de un grupo (discos / RAM), la deja vacía y
                // la inserta justo antes del botón "Agregar otro/a".
                function addMultiRow(containerSelector) {
                    var $container = $(containerSelector);
                    var $first = $container.find('.hu-component-row-multi').first();
                    if (!$first.length) return;

                    var $clone = $first.clone();
                    resetComponentRow($clone);
                    $clone.find('.hu-component-selected').text('Sin seleccionar').removeClass('is-selected');
                    $clone.find('.component-open-label').text('Seleccionar componente');
                    $clone.find('.hu-multi-remove').show();

                    $container.find('.hu-add-multi').before($clone);

                    refreshComponentRows();

                    if (containerSelector === '#input-container-1') {
                        updateOptionsDisc();
                    } else if (containerSelector === '#material-container-1') {
                        updateOptionsRam();
                    }
                }

                $(document).on('click', '.add-disco-row', function() {
                    addMultiRow('#input-container-1');
                });
                $(document).on('click', '.add-ram-row', function() {
                    addMultiRow('#material-container-1');
                });
                $(document).on('click', '.remove-multi-row', function() {
                    var $row = $(this).closest('.hu-component-row-multi');
                    var group = $row.data('group');

                    $row.remove();

                    refreshComponentRows();

                    if (group === 'discos') {
                        updateOptionsDisc();
                    } else if (group === 'rams') {
                        updateOptionsRam();
                    }
                });
                refreshComponentRows();

                // ── Área con nro consultorio ────────────────────────────────────────
                $('#en-uso').on('change', function() {
                    if (!$(this).is(':checked')) {
                        $('#addNroConsul_div').css('display', 'none').attr('required', false);
                        $('#editNroConsul_div').css('display', 'none').attr('required', false);
                    }
                });
                $('#editEn-uso').on('change', function() {
                    if (!$(this).is(':checked')) {
                        $('#editNroConsul_div').css('display', 'none').attr('required', false);
                    }
                });
                $('#addArea').on('change', function() {
                    if ($(this).val() == 27) {
                        $('#addNroConsul_div').css('display', 'block').attr('required', true);
                    } else {
                        $('#addNroConsul_div').css('display', 'none').attr('required', false);
                    }
                });
                $('#editArea').on('change', function() {
                    if ($(this).val() == 27) {
                        $('#editNroConsul_div').css('display', 'block').css('required', true);
                    } else {
                        $('#editNroConsul_div').css('display', 'none').css('required', false);
                    }
                });

                // ── Búsqueda de cards ───────────────────────────────────────────────
                $('#search_pc').on('input', function() {
                    var searchTerm = $(this).val().toLowerCase();
                    $('.pc-card').each(function() {
                        var nombre = String($(this).data('nombre'));
                        var id = String($(this).data('id'));
                        $(this).toggle(nombre.includes(searchTerm) || id.includes(searchTerm));
                    });
                });

                // ── Click en card → abre info ───────────────────────────────────────
                $('.pc-card .card').on('click', function() {
                    $(this).find('.infoBtn').trigger('click');
                });
                $('.infoBtn, .maintenanceBtn, .historyBtn, .btn-danger').on('click', function(e) {
                    e.stopPropagation();
                });

                // ── Stock discos (add) ──────────────────────────────────────────────
                function updateOptionsDisc() {
                    let stock = {};
                    $('select[name="discos1[]"] option').each(function() {
                        let v = $(this).val();
                        let s = parseInt($(this).data('stock')) || 0;
                        stock[v] = s;
                    });
                    $('select[name="discos1[]"]').each(function() {
                        let sel = $(this).val();
                        if (sel) stock[sel] = (stock[sel] || 0) - 1;
                    });
                    let anyAvail = false;
                    $('select[name="discos1[]"]').each(function() {
                        let currentSelect = $(this);
                        let hasOpts = false;
                        let def = currentSelect.find('option[value=""]');
                        currentSelect.find('option').show();
                        currentSelect.find('option').each(function() {
                            let v = $(this).val();
                            if (v !== '' && (stock[v] || 0) > 0) {
                                hasOpts = true;
                                anyAvail = true;
                            } else if (v !== '') {
                                $(this).hide();
                            }
                        });
                        if (!hasOpts && def.length) def.text('No hay discos disponibles');
                    });
                    if (!anyAvail) {
                        $('select[name="discos1[]"] option[value=""]').text('No hay discos disponibles');
                    } else {
                        $('select[name="discos1[]"] option[value=""]').text('Selecciona un disco');
                    }
                }
                $(document).on('change', 'select[name="discos1[]"]', updateOptionsDisc);
                $(document).on('click',
                    '.remove-input-disc',
                    function() {
                        $(this).closest('.input-group').remove();
                        updateOptionsDisc();
                    });
                $(document).on('click', '#editPcModal .add-input-disc', function(e) {
                    e.preventDefault();
                    addInputDisc(this);
                    updateDiscos();
                });
                updateOptionsDisc();

                // ── Stock RAMs (add) ────────────────────────────────────────────────
                function updateOptionsRam() {
                    let stock = {};
                    $('select[name="rams1[]"] option').each(function() {
                        let v = $(this).val();
                        let s = parseInt($(this).data('stock')) || 0;
                        stock[v] = s;
                    });
                    $('select[name="rams1[]"]').each(function() {
                        let sel = $(this).val();
                        if (sel) stock[sel] = (stock[sel] || 0) - 1;
                    });
                    let anyAvail = false;
                    $('select[name="rams1[]"]').each(function() {
                        let currentSelect = $(this);
                        let hasOpts = false;
                        let def = currentSelect.find('option[value=""]');
                        currentSelect.find('option').show();
                        currentSelect.find('option').each(function() {
                            let v = $(this).val();
                            if (v !== '' && (stock[v] || 0) > 0) {
                                hasOpts = true;
                                anyAvail = true;
                            } else if (v !== '') {
                                $(this).hide();
                            }
                        });
                        if (!hasOpts && def.length) def.text('No hay RAMs disponibles');
                    });
                    if (!anyAvail) {
                        $('select[name="rams1[]"] option[value=""]').text('No hay RAMs disponibles');
                    } else {
                        $('select[name="rams1[]"] option[value=""]').text('Selecciona una RAM');
                    }
                }
                $(document).on('change', 'select[name="rams1[]"]', updateOptionsRam);
                $(document).on('click',
                    '.remove-input-ram',
                    function() {
                        $(this).closest('.input-group').remove();
                        updateOptionsRam();
                    });
                $(document).on('click', '#editPcModal .add-input-ram', function(e) {
                    e.preventDefault();
                    addInputRam(this);
                    updateRams();
                });
                updateOptionsRam();

                // ── Stock discos (edit) ─────────────────────────────────────────────
                function updateOptionsDiscModal2() {
                    let stock = {};
                    $('select[name="discos2[]"] option').each(function() {
                        let v = $(this).val();
                        let s = parseInt($(this).data('stock')) || 0;
                        stock[v] = s;
                    });
                    $('select[name="discos2[]"]').each(function() {
                        let sel = $(this).val();
                        if (sel) stock[sel] = (stock[sel] || 0) - 1;
                    });
                    $('select[name="discos2[]"]').each(function() {
                        let currentSelect = $(this);
                        let hasOpts = false;
                        let def = currentSelect.find('option[value=""]');
                        currentSelect.find('option').show();
                        currentSelect.find('option').each(function() {
                            let v = $(this).val();
                            if (v !== '' && (stock[v] || 0) > 0) {
                                hasOpts = true;
                            } else if (v !== '') {
                                $(this).hide();
                            }
                        });
                        if (!hasOpts && def.length) def.text('No hay discos disponibles');
                    });
                }
                $(document).on('change', 'select[name="discos2[]"]', updateOptionsDiscModal2);
                $(document).on('click',
                    '.remove-input-disc-modal2',
                    function() {
                        $(this).closest('.input-group').remove();
                        updateOptionsDiscModal2();
                    });
                $(document).on('click', '.add-input-disc-modal2', function() {
                    setTimeout(updateOptionsDiscModal2, 0);
                });

                // ── Stock RAMs (edit) ───────────────────────────────────────────────
                function updateOptionsRamModal2() {
                    let stock = {};
                    $('select[name="rams2[]"] option').each(function() {
                        let v = $(this).val();
                        let s = parseInt($(this).data('stock')) || 0;
                        stock[v] = s;
                    });
                    $('select[name="rams2[]"]').each(function() {
                        let sel = $(this).val();
                        if (sel) stock[sel] = (stock[sel] || 0) - 1;
                    });
                    $('select[name="rams2[]"]').each(function() {
                        let currentSelect = $(this);
                        let hasOpts = false;
                        let def = currentSelect.find('option[value=""]');
                        currentSelect.find('option').show();
                        currentSelect.find('option').each(function() {
                            let v = $(this).val();
                            if (v !== '' && (stock[v] || 0) > 0) {
                                hasOpts = true;
                            } else if (v !== '') {
                                $(this).hide();
                            }
                        });
                        if (!hasOpts && def.length) def.text('No hay RAMs disponibles');
                    });
                }
                $(document).on('change', 'select[name="rams2[]"]', updateOptionsRamModal2);
                $(document).on('click',
                    '.remove-input-ram-modal2',
                    function() {
                        $(this).closest('.input-group').remove();
                        updateOptionsRamModal2();
                    });
                $(document).on('click', '.add-input-ram-modal2', function() {
                    setTimeout(updateOptionsRamModal2, 0);
                });

                window.updateRams = function() {
                    updateOptionsRamModal2();
                };
                window.updateDiscos = function() {
                    updateOptionsDiscModal2();
                };

                // ── Retirar componente como roto ──────────────────────────────────
                $('#abrirRetirarRotoBtn').on('click', function() {
                    if (!componentesPcActuales.length) return;

                    var pcId = $(this).data('pc-id');
                    var pcIdentificador = $(this).data('pc-identificador');
                    var pcNombre = $(this).data('pc-nombre');
                    var $select = $('#retirarRotoComponente');

                    $select.empty().append(
                        '<option value="" selected disabled>Seleccioná el componente</option>');

                    componentesPcActuales.forEach(function(componente) {
                        var tipo = componente.tipo && componente.tipo.nombre ? componente.tipo.nombre :
                            'Componente';
                        $select.append(
                            $('<option>', {
                                value: componente.id,
                                text: componente.nombre + ' — ' + tipo
                            })
                        );
                    });

                    $('#retirarRotoPcId').val(pcId);
                    $('#retirarRotoPcIdentificador').val(pcIdentificador);
                    $('#retirarRotoPcNombre').val(pcNombre);
                    $('#retirarRotoPcLabel').text('PC: ' + pcIdentificador + ' — ' + pcNombre);
                    $('#retirarRotoMotivo').val('');

                    $('#editPcModal').one('hidden.bs.modal', function() {
                        $('#retirarRotoModal').modal('show');
                    }).modal('hide');
                });

                // ── Historia de la PC ───────────────────────────────────────────────
                var modalHandlerAttached = false;
                var nro_inv = '',
                    nombre = '';

                $('#historyPcModal').on('show.bs.modal', function(event) {
                    if (modalHandlerAttached) return;
                    modalHandlerAttached = true;

                    var button = $(event.relatedTarget);
                    var componenteId = button.data('id');
                    var componenteTipo = button.data('tipo');
                    nro_inv = button.data('nro_inv');
                    nombre = button.data('nombre');

                    var table = $('#table_historias_pc').DataTable();
                    table.clear().draw();

                    var historiaUrl = '{{ route('historia.get', ['tipo' => ':tipo', 'id' => ':id']) }}';
                    historiaUrl = historiaUrl.replace(':tipo', componenteTipo).replace(':id', componenteId);

                    $.ajax({
                        url: historiaUrl,
                        method: 'GET',
                        success: function(response) {
                            var data = [];
                            response.historia.forEach(function(h) {
                                var fecha = new Date(h.created_at).toLocaleDateString(
                                    'es-ES', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                data.push([h.tecnico, h.detalle, h.motivo || '', fecha]);
                            });
                            table.rows.add(data).draw();
                        },
                        error: function() {
                            alert('Error al cargar la historia.');
                        }
                    });
                });

                $('#historyPcModal').on('hide.bs.modal', function() {
                    modalHandlerAttached = false;
                });

                // ── Mantenimiento: mostrar detalle ──────────────────────────────────
                $('#editPcModal').off('change.maintenance', '#en-uso-mantenimineto')
                    .on('change.maintenance', '#en-uso-mantenimineto', function() {
                        const div = $('#editPcModal #div-detalle-mant > div');
                        const input = div.find('input');
                        div.toggle(this.checked);
                        input.prop('required', this.checked);
                    });

                // ── DataTable historia PC ───────────────────────────────────────────
                $('#table_historias_pc').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel',
                        className: 'btn btn-hu-outline btn-sm',
                        title: function() {
                            return 'Historia de ' + nro_inv + ' - ' + nombre;
                        }
                    }],
                    responsive: true,
                    lengthChange: true,
                    autoWidth: true,
                    language: {
                        emptyTable: 'No hay datos disponibles en la tabla.',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                        infoEmpty: 'Mostrando 0 a 0 de 0 entradas',
                        infoFiltered: '(filtrado de _MAX_ entradas totales)',
                        lengthMenu: '_MENU_',
                        loadingRecords: 'Cargando...',
                        processing: 'Procesando...',
                        search: 'Buscar:',
                        zeroRecords: 'No se encontraron registros coincidentes',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });

            });

            @if (session('success') === 'PC guardada correctamente.')
                $(function() {
                    var successModal = document.getElementById('pcSuccessModal');
                    if (!successModal) return;

                    bootstrap.Modal.getOrCreateInstance(successModal).show();

                    $('#btnAddAnotherPc').on('click', function() {
                        var addModal = document.getElementById('addModal');
                        if (!addModal) return;

                        var successInstance = bootstrap.Modal.getOrCreateInstance(successModal);
                        successModal.addEventListener('hidden.bs.modal', function openWizard() {
                            successModal.removeEventListener('hidden.bs.modal', openWizard);
                            bootstrap.Modal.getOrCreateInstance(addModal).show();
                        });
                        successInstance.hide();
                    });
                });
            @endif
        </script>
    @endpush

</x-app-layout>
