<?php
use App\Models\ComponenteModel;
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg leading-tight" style="color: var(--hu-azul);">
            Reportes
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
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Reportes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    Generá informes filtrables y exportables a Excel sobre cualquier sección del inventario.
                </div>
            </div>
        </div>
    </div>

    {{-- Contenido --}}
    <div class="px-4 px-md-5 py-4" style="max-width:1400px;margin:0 auto;">
        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">

            {{-- Controles superiores --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-5">
                    <label for="addTipo" class="form-label fw-semibold" style="font-size:.85rem;">Contenido del informe</label>
                    <select class="form-select" id="addTipo" name="addTipo" required>
                        <option value="null" disabled selected>Seleccione...</option>
                        <optgroup label="Dispositivos">
                            <option value="1">PCs</option>
                            <option value="2">Impresoras</option>
                            <option value="3">Teléfonos IP</option>
                            <option value="4">Routers</option>
                        </optgroup>
                        <option value="5">Stock</option>
                        <option value="6">Historia</option>
                        <option value="7">Áreas</option>
                        <option value="8">Depósitos</option>
                        <option value="9">Categorías</option>
                        <option value="10">Estados</option>
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <label for="addTitulo" class="form-label fw-semibold" style="font-size:.85rem;">Título del reporte</label>
                    <input type="text" class="form-control" id="addTitulo" name="addTitulo">
                </div>
            </div>

            {{-- Área de tabla dinámica --}}
            <div id="div-table">

                {{-- Template: Estados --}}
                <template id="estados_template">
                    <table id="table-estados" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr><th>Nombre</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($estados as $estado)
                                <tr><td>{{ $estado->nombre }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </template>

                {{-- Template: Categorías --}}
                <template id="categorias_template">
                    <table id="table-categorias" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr><th>Nombre</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($tipos as $tipo)
                                <tr><td>{{ $tipo->nombre }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </template>

                {{-- Template: Depósitos --}}
                <template id="depositos_template">
                    <table id="table-depositos" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr><th>Nombre</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($depositos as $deposito)
                                <tr><td>{{ $deposito->nombre }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </template>

                {{-- Template: Áreas --}}
                <template id="areas_template">
                    <table id="table-areas" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr><th>Nombre</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $area)
                                <tr><td>{{ $area->nombre }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </template>

                {{-- Template: Historia --}}
                <template id="historias_template">
                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-auto">
                            <label for="filtro-tecnicos-hs" class="form-label fw-semibold" style="font-size:.82rem;">Técnico</label>
                            <select id="filtro-tecnicos-hs" class="form-select form-select-sm" style="min-width:180px;">
                                <option value="">Todos</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Fecha</label>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="filter-range-hs">
                                    <label class="form-check-label" for="filter-range-hs" style="font-size:.82rem;">Rango</label>
                                </div>
                            </div>
                            <div id="date-filters-hs">
                                <input class="form-control form-control-sm" type="date" id="date-hs">
                            </div>
                            <div id="range-filters-hs" style="display:none;" class="d-flex gap-2">
                                <input class="form-control form-control-sm" type="date" id="start-date-hs">
                                <input class="form-control form-control-sm" type="date" id="end-date-hs">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 flex-wrap mb-2">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-tecnico" data-column="0" checked>
                            <label class="form-check-label" for="column-tecnico">Técnico</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-detalle" data-column="1" checked>
                            <label class="form-check-label" for="column-detalle">Detalle</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-motivo" data-column="2" checked>
                            <label class="form-check-label" for="column-motivo">Motivo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-fecha" data-column="3" checked>
                            <label class="form-check-label" for="column-fecha">Fecha</label>
                        </div>
                    </div>
                    <button id="exportHistorias" class="btn btn-hu-outline btn-sm mb-3">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive">
                        <table id="table-historias" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Técnico</th>
                                    <th>Detalle</th>
                                    <th>Motivo</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historias as $historia)
                                    <tr>
                                        <td>{{ $historia->tecnico }}</td>
                                        <td>{{ $historia->detalle }}</td>
                                        <td>{{ $historia->motivo }}</td>
                                        <td>{{ $historia->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Template: Stock --}}
                <template id="stock_template">
                    <div class="row g-2 mb-3">
                        <div class="col-auto">
                            <label for="filtro-deposito" class="form-label fw-semibold" style="font-size:.82rem;">Depósito</label>
                            <select id="filtro-deposito" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach ($depositos as $deposito)
                                    <option value="{{ $deposito->nombre }}">{{ $deposito->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="filtro-estado" class="form-label fw-semibold" style="font-size:.82rem;">Estado</label>
                            <select id="filtro-estado" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="filtro-categoria" class="form-label fw-semibold" style="font-size:.82rem;">Categoría</label>
                            <select id="filtro-categoria" class="form-select form-select-sm">
                                <option value="">Todas</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="filtro-stock" class="form-label fw-semibold" style="font-size:.82rem;">Stock</label>
                            <select id="filtro-stock" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="poco-stock">Poco stock</option>
                                <option value="sin-stock">Sin stock</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-3 flex-wrap mb-2">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-categoria" data-column="0" checked>
                            <label class="form-check-label" for="column-categoria">Categoría</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nombre" data-column="1" checked>
                            <label class="form-check-label" for="column-nombre">Nombre</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-deposito" data-column="2" checked>
                            <label class="form-check-label" for="column-deposito">Depósito</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-stock" data-column="3" checked>
                            <label class="form-check-label" for="column-stock">Stock</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-estado" data-column="4" checked>
                            <label class="form-check-label" for="column-estado">Estado</label>
                        </div>
                    </div>
                    <button id="exportHistorias" class="btn btn-hu-outline btn-sm mb-3">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive">
                        <table id="table-componentes" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Nombre</th>
                                    <th>Depósito</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($componentes as $componente)
                                    <tr>
                                        <td>{{ $componente->tipo->nombre ?? 'Categoría no asignada' }}</td>
                                        <td>{{ $componente->nombre }}</td>
                                        <td>{{ $componente->deposito->nombre ?? 'No asignado' }}</td>
                                        <td>{{ $componente->stock }}</td>
                                        <td>{{ $componente->estado->nombre ?? 'No asignado' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Template: Routers --}}
                <template id="routers_template">
                    <div class="d-flex gap-3 flex-wrap mb-2">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nro_inv" data-column="0" checked>
                            <label class="form-check-label" for="column-nro_inv">Nº inventario</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nombre" data-column="1" checked>
                            <label class="form-check-label" for="column-nombre">Nombre</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-marca_modelo" data-column="2" checked>
                            <label class="form-check-label" for="column-marca_modelo">Marca y modelo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-ip" data-column="3" checked>
                            <label class="form-check-label" for="column-ip">IP</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-deposito" data-column="4" checked>
                            <label class="form-check-label" for="column-deposito">Depósito</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-area" data-column="5" checked>
                            <label class="form-check-label" for="column-area">Área</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-area_detalle" data-column="6" checked>
                            <label class="form-check-label" for="column-area_detalle">Ubicación detallada</label>
                        </div>
                    </div>
                    <button id="exportHistorias" class="btn btn-hu-outline btn-sm mb-3">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive">
                        <table id="table-routers" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nº inventario</th>
                                    <th>Nombre</th>
                                    <th>Marca y modelo</th>
                                    <th>IP</th>
                                    <th>Depósito</th>
                                    <th>Área</th>
                                    <th>Ubicación detallada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($routers as $router)
                                    <tr>
                                        <td>{{ $router->identificador }}</td>
                                        <td>{{ $router->nombre }}</td>
                                        <td>{{ $router->marca_modelo }}</td>
                                        <td>{{ $router->ip }}</td>
                                        <td>{{ $router->deposito->nombre ?? 'No asignado' }}</td>
                                        <td>{{ $router->area->nombre ?? 'No asignado' }}</td>
                                        <td>{{ $router->area_detalle }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Template: Teléfonos IP --}}
                <template id="telefonos_template">
                    <div class="d-flex gap-3 flex-wrap mb-2">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nro_inv" data-column="0" checked>
                            <label class="form-check-label" for="column-nro_inv">Nº inventario</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nombre" data-column="1" checked>
                            <label class="form-check-label" for="column-nombre">Nombre</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-marca_modelo" data-column="2" checked>
                            <label class="form-check-label" for="column-marca_modelo">Marca y modelo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-ip" data-column="3" checked>
                            <label class="form-check-label" for="column-ip">IP</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-numero" data-column="4" checked>
                            <label class="form-check-label" for="column-numero">Número</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-deposito" data-column="5" checked>
                            <label class="form-check-label" for="column-deposito">Depósito</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-area" data-column="6" checked>
                            <label class="form-check-label" for="column-area">Área</label>
                        </div>
                    </div>
                    <button id="exportHistorias" class="btn btn-hu-outline btn-sm mb-3">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive">
                        <table id="table-telefonos" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nº inventario</th>
                                    <th>Nombre</th>
                                    <th>Marca y modelo</th>
                                    <th>IP</th>
                                    <th>Número</th>
                                    <th>Depósito</th>
                                    <th>Área</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($telefonos as $telefono)
                                    <tr>
                                        <td>{{ $telefono->identificador }}</td>
                                        <td>{{ $telefono->nombre }}</td>
                                        <td>{{ $telefono->marca_modelo }}</td>
                                        <td>{{ $telefono->ip }}</td>
                                        <td>{{ $telefono->numero }}</td>
                                        <td>{{ $telefono->deposito->nombre ?? 'No asignado' }}</td>
                                        <td>{{ $telefono->area->nombre ?? 'No asignado' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Template: Impresoras --}}
                <template id="impresoras_template">
                    <div class="d-flex gap-3 flex-wrap mb-2">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nro_inv" data-column="0" checked>
                            <label class="form-check-label" for="column-nro_inv">Nº inventario</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nombre" data-column="1" checked>
                            <label class="form-check-label" for="column-nombre">Nombre</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-marca_modelo" data-column="2" checked>
                            <label class="form-check-label" for="column-marca_modelo">Marca y modelo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-ip" data-column="3" checked>
                            <label class="form-check-label" for="column-ip">IP</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-toner" data-column="4" checked>
                            <label class="form-check-label" for="column-toner">Tóner</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-deposito" data-column="5" checked>
                            <label class="form-check-label" for="column-deposito">Depósito</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-area" data-column="6" checked>
                            <label class="form-check-label" for="column-area">Área</label>
                        </div>
                    </div>
                    <button id="exportHistorias" class="btn btn-hu-outline btn-sm mb-3">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive">
                        <table id="table-impresoras" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nº inventario</th>
                                    <th>Nombre</th>
                                    <th>Marca y modelo</th>
                                    <th>IP</th>
                                    <th>Tóner</th>
                                    <th>Depósito</th>
                                    <th>Área</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($impresoras as $impresora)
                                    <tr>
                                        <td>{{ $impresora->identificador }}</td>
                                        <td>{{ $impresora->nombre }}</td>
                                        <td>{{ $impresora->marca_modelo }}</td>
                                        <td>{{ $impresora->ip }}</td>
                                        <td>{{ ComponenteModel::find($impresora->toner_id)->nombre }}</td>
                                        <td>{{ $impresora->deposito->nombre ?? 'No asignado' }}</td>
                                        <td>{{ $impresora->area->nombre ?? 'No asignado' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Template: PCs --}}
                <template id="pcs_template">
                    <div class="d-flex gap-3 flex-wrap mb-2">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nro_inv" data-column="0" checked>
                            <label class="form-check-label" for="column-nro_inv">Nº inventario</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-nombre" data-column="1" checked>
                            <label class="form-check-label" for="column-nombre">Nombre</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-ip" data-column="2" checked>
                            <label class="form-check-label" for="column-ip">IPv4</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-deposito" data-column="3" checked>
                            <label class="form-check-label" for="column-deposito">Depósito</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" id="column-area" data-column="4" checked>
                            <label class="form-check-label" for="column-area">Área</label>
                        </div>
                    </div>
                    <button id="exportHistorias" class="btn btn-hu-outline btn-sm mb-3">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive">
                        <table id="table-pcs" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nº inventario</th>
                                    <th>Nombre</th>
                                    <th>IPv4</th>
                                    <th>Depósito</th>
                                    <th>Área</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pcs as $pc)
                                    <tr>
                                        <td>{{ $pc->identificador }}</td>
                                        <td>{{ $pc->nombre }}</td>
                                        <td>{{ $pc->ip }}</td>
                                        <td>{{ $pc->deposito->nombre ?? 'No asignado' }}</td>
                                        <td>{{ $pc->area->nombre ?? 'No asignado' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

            </div>{{-- /#div-table --}}
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script>
    $(document).ready(function () {
        let table, titulo;

        const estadosTemplate    = $('#estados_template').html();
        const categoriasTemplate = $('#categorias_template').html();
        const depositosTemplate  = $('#depositos_template').html();
        const areasTemplate      = $('#areas_template').html();
        const historiasTemplate  = $('#historias_template').html();
        const stockTemplate      = $('#stock_template').html();
        const routersTemplate    = $('#routers_template').html();
        const telefonosTemplate  = $('#telefonos_template').html();
        const impresorasTemplate = $('#impresoras_template').html();
        const pcsTemplate        = $('#pcs_template').html();

        function getTableId() {
            switch ($('#addTipo').val()) {
                case '10': return 'estados';
                case '9':  return 'categorias';
                case '8':  return 'depositos';
                case '7':  return 'areas';
                case '6':  return 'historias';
                case '5':  return 'componentes';
                case '4':  return 'routers';
                case '3':  return 'telefonos';
                case '2':  return 'impresoras';
                case '1':  return 'pcs';
                default:   return '';
            }
        }

        function initializeTable(templateToUse) {
            if (table) {
                table.destroy();
                $('#div-table').empty();
            }
            $('#div-table').html(templateToUse);
            $('#addTitulo').val(titulo);

            var simpleTables = [7, 8, 9, 10];
            if (simpleTables.includes(parseInt($('#addTipo').val()))) {
                table = $('#table-' + getTableId()).DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel',
                        className: 'btn btn-hu-outline btn-sm',
                        title: function () { return $('#addTitulo').val() || titulo; }
                    }],
                    paging: false,
                    searching: false,
                    info: false,
                    autoWidth: true
                });
            } else {
                table = $('#table-' + getTableId()).DataTable({
                    paging: false,
                    searching: false,
                    info: false,
                    autoWidth: true
                });
            }

            $('.column-toggle').off('change').on('change', function () {
                const column = table.column($(this).data('column'));
                column.visible(!column.visible());
                $('#table-' + getTableId()).css('min-width', '100%');
            });
        }

        $('#addTipo').on('change', function () {
            const map = {
                '10': [estadosTemplate,    'Listado de estados'],
                '9':  [categoriasTemplate, 'Listado de categorías'],
                '8':  [depositosTemplate,  'Listado de depósitos'],
                '7':  [areasTemplate,      'Listado de áreas'],
                '6':  [historiasTemplate,  'Informe de movimientos'],
                '5':  [stockTemplate,      'Informe de componentes'],
                '4':  [routersTemplate,    'Informe de routers'],
                '3':  [telefonosTemplate,  'Informe de teléfonos IP'],
                '2':  [impresorasTemplate, 'Informe de impresoras'],
                '1':  [pcsTemplate,        'Informe de PCs'],
            };
            const entry = map[$(this).val()];
            if (entry) {
                titulo = entry[1];
                initializeTable(entry[0]);
            }
        });

        $(document).on('click', '#exportHistorias', function () {
            var data = table.rows().data().toArray();
            var columnNames = table.columns().header().toArray().map(h => $(h).text());
            var selectedColumns = [];
            $('.column-toggle:checked').each(function () {
                selectedColumns.push($(this).data('column'));
            });
            var cleanedData = data.map(row => row.map(cell => $('<div>').html(cell).text()));

            $.ajax({
                url: 'scripts/export_histories.php',
                method: 'POST',
                data: {
                    titulo: $('#addTitulo').val(),
                    columnNames: JSON.stringify(columnNames),
                    selectedColumns: JSON.stringify(selectedColumns),
                    data: JSON.stringify(cleanedData)
                },
                xhrFields: { responseType: 'blob' },
                success: function (response) {
                    var url = window.URL.createObjectURL(response);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = ($('#addTitulo').val() || 'reporte') + '.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                },
                error: function () { alert('Error al exportar el Excel.'); }
            });
        });

        // Filtro fecha historia
        $(document).on('change', '#filter-range-hs', function () {
            $('#date-hs, #start-date-hs, #end-date-hs').val(null);
            if ($(this).is(':checked')) {
                $('#date-filters-hs').hide();
                $('#range-filters-hs').css('display', 'flex');
            } else {
                $('#range-filters-hs').css('display', 'none');
                $('#date-filters-hs').show();
            }
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString(undefined, { year: 'numeric', month: '2-digit', day: '2-digit' })
                + ' ' + date.toLocaleTimeString();
        }

        // Filtros AJAX: historia
        $(document).on('change', '#date-hs, #start-date-hs, #end-date-hs, #filter-range-hs, #filtro-tecnicos-hs', function () {
            $('.column-toggle').prop('checked', true);
            $.ajax({
                url: '/inv-tecnica/public/filter-reportes',
                method: 'GET',
                data: {
                    tecnico:    $('#filtro-tecnicos-hs').val(),
                    date:       $('#date-hs').val(),
                    start_date: $('#start-date-hs').val(),
                    end_date:   $('#end-date-hs').val(),
                },
                success: function (data) {
                    if (table) table.destroy();
                    $('#table-historias tbody').empty();
                    $.each(data, function (i, h) {
                        $('#table-historias tbody').append(
                            `<tr><td>${h.tecnico}</td><td>${h.detalle}</td><td>${h.motivo}</td><td>${formatDate(h.created_at)}</td></tr>`
                        );
                    });
                    table = $('#table-historias').DataTable({ paging: false, searching: false, info: false, autoWidth: true });
                }
            });
        });

        // Filtros AJAX: stock
        $(document).on('change', '#filtro-deposito, #filtro-estado, #filtro-categoria, #filtro-stock', function () {
            $('.column-toggle').prop('checked', true);
            $.ajax({
                url: '/inv-tecnica/public/filter-stock',
                method: 'GET',
                data: {
                    deposito:  $('#filtro-deposito').val(),
                    estado:    $('#filtro-estado').val(),
                    categoria: $('#filtro-categoria').val(),
                    stock:     $('#filtro-stock').val(),
                },
                success: function (data) {
                    if (table) table.destroy();
                    $('#table-componentes tbody').empty();
                    $.each(data, function (i, c) {
                        $('#table-componentes tbody').append(
                            `<tr><td>${c.categoria}</td><td>${c.nombre}</td><td>${c.deposito}</td><td>${c.stock}</td><td>${c.estado}</td></tr>`
                        );
                    });
                    table = $('#table-componentes').DataTable({ paging: false, searching: false, info: false, autoWidth: true });
                }
            });
        });
    });
    </script>
    @endpush

</x-app-layout>