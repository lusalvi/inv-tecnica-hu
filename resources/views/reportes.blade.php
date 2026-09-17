<?php use App\Models\ComponenteModel; ?>

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

    <div class="px-4 px-md-5 py-4" style="max-width:1400px;margin:0 auto;">

        {{-- Panel de configuración del reporte --}}
        <div class="bg-white rounded-3 mb-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08); overflow:hidden;">

            {{-- Encabezado del panel --}}
            <div class="px-4 py-3"
                style="border-bottom:1px solid rgba(0,55,100,.08); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem;">
                <div style="display:flex; align-items:center; gap:.65rem;">
                    <span class="material-symbols-outlined" style="color:var(--hu-azul); font-size:1.2rem;">tune</span>
                    <div>
                        <h3 style="margin:0; font-size:.9rem; font-weight:700; color:var(--hu-azul);">Configuración del
                            informe</h3>
                        <p style="margin:0; font-size:.72rem; color:#8a9ab0; margin-top:.1rem;">Seleccioná el contenido
                            y ajustá los filtros</p>
                    </div>
                </div>
            </div>

            {{-- Controles principales --}}
            <div class="p-4">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem 1.75rem; max-width:760px;">

                    <div>
                        <label class="rep-label" for="addTipo">Contenido del informe</label>
                        <div class="rep-select-wrap">
                            <select class="rep-select" id="addTipo" name="addTipo" required>
                                <option value="null" disabled selected>Seleccioná...</option>
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
                            <span class="material-symbols-outlined rep-select-arrow"
                                aria-hidden="true">keyboard_arrow_down</span>
                        </div>
                    </div>

                    <div>
                        <label class="rep-label" for="addTitulo">Título del reporte</label>
                        <input type="text" class="rep-input" id="addTitulo" name="addTitulo"
                            placeholder="Ej: Informe mensual de stock">
                    </div>

                </div>
            </div>
        </div>

        {{-- Panel de resultados --}}
        <div class="bg-white rounded-3" style="box-shadow:0 2px 12px rgba(0,55,100,.08); overflow:hidden;">

            {{-- Encabezado del panel de resultados --}}
            <div class="px-4 py-3"
                style="border-bottom:1px solid rgba(0,55,100,.08); display:flex; align-items:center; gap:.65rem;">
                <span class="material-symbols-outlined"
                    style="color:var(--hu-azul); font-size:1.2rem;">table_chart</span>
                <div>
                    <h3 style="margin:0; font-size:.9rem; font-weight:700; color:var(--hu-azul);">Resultados</h3>
                    <p style="margin:0; font-size:.72rem; color:#8a9ab0; margin-top:.1rem;">Seleccioná un tipo de
                        informe para ver los datos</p>
                </div>
            </div>

            {{-- Área dinámica --}}
            <div id="div-table" class="p-4">

                {{-- Estado vacío inicial --}}
                <div id="empty-state"
                    style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:3rem 1rem; color:#b0bec8;">
                    <span class="material-symbols-outlined"
                        style="font-size:3rem; margin-bottom:.75rem; opacity:.5;">insert_chart</span>
                    <p style="font-size:.85rem; margin:0;">Seleccioná un tipo de informe para comenzar</p>
                </div>

                {{-- ── Templates ── --}}

                {{-- Estados --}}
                <template id="estados_template">
                    <div class="table-responsive">
                        <table id="table-estados" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estados as $estado)
                                    <tr>
                                        <td>{{ $estado->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Categorías --}}
                <template id="categorias_template">
                    <div class="table-responsive">
                        <table id="table-categorias" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tipos as $tipo)
                                    <tr>
                                        <td>{{ $tipo->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Depósitos --}}
                <template id="depositos_template">
                    <div class="table-responsive">
                        <table id="table-depositos" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($depositos as $deposito)
                                    <tr>
                                        <td>{{ $deposito->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Áreas --}}
                <template id="areas_template">
                    <div class="table-responsive">
                        <table id="table-areas" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($areas as $area)
                                    <tr>
                                        <td>{{ $area->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>

                {{-- Historia --}}
                <template id="historias_template">
                    <div class="rep-filters-bar">
                        <div>
                            <label class="rep-label">Técnico</label>
                            <div class="rep-select-wrap">
                                <select id="filtro-tecnicos-hs" class="rep-select-sm">
                                    <option value="">Todos</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined rep-select-arrow"
                                    aria-hidden="true">keyboard_arrow_down</span>
                            </div>
                        </div>
                        <div>
                            <label class="rep-label">Fecha</label>
                            <div class="rep-date-controls">
                                <div class="rep-range-toggle">
                                    <input type="checkbox" id="filter-range-hs">
                                    <label for="filter-range-hs">Rango de fechas</label>
                                </div>
                                <div id="date-filters-hs" class="rep-date-inputs">
                                    <input class="rep-input-sm" type="date" id="date-hs">
                                </div>
                                <div id="range-filters-hs" class="rep-date-inputs" style="display:none;">
                                    <input class="rep-input-sm" type="date" id="start-date-hs">
                                    <input class="rep-input-sm" type="date" id="end-date-hs">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rep-column-toggles">
                        <span class="rep-label" style="margin-bottom:0; align-self:center;">Columnas:</span>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="0"
                                checked><span>Técnico</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="1"
                                checked><span>Detalle</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="2"
                                checked><span>Motivo</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="3"
                                checked><span>Fecha</span></label>
                    </div>
                    <button type="button" class="exportHistorias btn btn-hu-outline rep-export-btn">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive mt-3">
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

                {{-- Stock --}}
                <template id="stock_template">
                    <div class="rep-filters-bar">
                        <div>
                            <label class="rep-label">Depósito</label>
                            <div class="rep-select-wrap">
                                <select id="filtro-deposito" class="rep-select-sm">
                                    <option value="">Todos</option>
                                    @foreach ($depositos as $deposito)
                                        <option value="{{ $deposito->nombre }}">{{ $deposito->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined rep-select-arrow"
                                    aria-hidden="true">keyboard_arrow_down</span>
                            </div>
                        </div>
                        <div>
                            <label class="rep-label">Estado</label>
                            <div class="rep-select-wrap">
                                <select id="filtro-estado" class="rep-select-sm">
                                    <option value="">Todos</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined rep-select-arrow"
                                    aria-hidden="true">keyboard_arrow_down</span>
                            </div>
                        </div>
                        <div>
                            <label class="rep-label">Categoría</label>
                            <div class="rep-select-wrap">
                                <select id="filtro-categoria" class="rep-select-sm">
                                    <option value="">Todas</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined rep-select-arrow"
                                    aria-hidden="true">keyboard_arrow_down</span>
                            </div>
                        </div>
                        <div>
                            <label class="rep-label">Stock</label>
                            <div class="rep-select-wrap">
                                <select id="filtro-stock" class="rep-select-sm">
                                    <option value="">Todos</option>
                                    <option value="poco-stock">Poco stock</option>
                                    <option value="sin-stock">Sin stock</option>
                                </select>
                                <span class="material-symbols-outlined rep-select-arrow"
                                    aria-hidden="true">keyboard_arrow_down</span>
                            </div>
                        </div>
                    </div>
                    <div class="rep-column-toggles">
                        <span class="rep-label" style="margin-bottom:0; align-self:center;">Columnas:</span>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="0"
                                checked><span>Categoría</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="1"
                                checked><span>Nombre</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="2"
                                checked><span>Depósito</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="3"
                                checked><span>Stock</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="4"
                                checked><span>Estado</span></label>
                    </div>
                    <button type="button" class="exportHistorias btn btn-hu-outline rep-export-btn">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive mt-3">
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

                {{-- Routers --}}
                <template id="routers_template">
                    <div class="rep-column-toggles">
                        <span class="rep-label" style="margin-bottom:0; align-self:center;">Columnas:</span>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="0"
                                checked><span>Nº inventario</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="1"
                                checked><span>Nombre</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="2"
                                checked><span>Marca y modelo</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="3"
                                checked><span>IP</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="4"
                                checked><span>Depósito</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="5"
                                checked><span>Área</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="6"
                                checked><span>Ubicación detallada</span></label>
                    </div>
                    <button type="button" class="exportHistorias btn btn-hu-outline rep-export-btn">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive mt-3">
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

                {{-- Teléfonos IP --}}
                <template id="telefonos_template">
                    <div class="rep-column-toggles">
                        <span class="rep-label" style="margin-bottom:0; align-self:center;">Columnas:</span>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="0"
                                checked><span>Nº inventario</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="1"
                                checked><span>Nombre</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="2"
                                checked><span>Marca y modelo</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="3"
                                checked><span>IP</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="4"
                                checked><span>Número</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="5"
                                checked><span>Depósito</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="6"
                                checked><span>Área</span></label>
                    </div>
                    <button type="button" class="exportHistorias btn btn-hu-outline rep-export-btn">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive mt-3">
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

                {{-- Impresoras --}}
                <template id="impresoras_template">
                    <div class="rep-column-toggles">
                        <span class="rep-label" style="margin-bottom:0; align-self:center;">Columnas:</span>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="0"
                                checked><span>Nº inventario</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="1"
                                checked><span>Nombre</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="2"
                                checked><span>Marca y modelo</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="3"
                                checked><span>IP</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="4"
                                checked><span>Tóner</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="5"
                                checked><span>Depósito</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="6"
                                checked><span>Área</span></label>
                    </div>
                    <button type="button" class="exportHistorias btn btn-hu-outline rep-export-btn">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive mt-3">
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

                {{-- PCs --}}
                <template id="pcs_template">
                    <div class="rep-column-toggles">
                        <span class="rep-label" style="margin-bottom:0; align-self:center;">Columnas:</span>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="0"
                                checked><span>Nº inventario</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="1"
                                checked><span>Nombre</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="2"
                                checked><span>IPv4</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="3"
                                checked><span>Depósito</span></label>
                        <label class="rep-toggle"><input class="column-toggle" type="checkbox" data-column="4"
                                checked><span>Área</span></label>
                    </div>
                    <button type="button" class="exportHistorias btn btn-hu-outline rep-export-btn">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; vertical-align:middle;">download</span>
                        Exportar Excel
                    </button>
                    <div class="table-responsive mt-3">
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

    <style>
        .rep-label {
            display: block;
            font-size: .7rem;
            font-weight: 700;
            color: var(--hu-azul);
            margin-bottom: .35rem;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .rep-select,
        .rep-input {
            width: 100%;
            border: 1px solid #d0dae6;
            border-radius: 8px;
            padding: .5rem .8rem;
            font-family: 'Montserrat', sans-serif;
            font-size: .875rem;
            color: var(--hu-texto);
            background: #fff;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            appearance: auto;
        }

        .rep-select:focus,
        .rep-input:focus {
            border-color: var(--hu-azul);
            box-shadow: 0 0 0 3px rgba(0, 55, 100, .08);
        }

        .rep-select-wrap {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .rep-select-wrap .rep-select,
        .rep-select-wrap .rep-select-sm {
            width: 100%;
            appearance: none;
            padding-right: 2rem;
        }

        .rep-select-arrow {
            position: absolute;
            top: 50%;
            right: .65rem;
            transform: translateY(-50%);
            color: var(--hu-azul);
            font-size: 1.15rem;
            pointer-events: none;
        }

        .rep-select-sm,
        .rep-input-sm {
            border: 1px solid #d0dae6;
            border-radius: 7px;
            padding: .35rem .65rem;
            font-family: 'Montserrat', sans-serif;
            font-size: .8rem;
            color: var(--hu-texto);
            background: #fff;
            outline: none;
            transition: border-color .2s;
        }

        .rep-select-sm:focus,
        .rep-input-sm:focus {
            border-color: var(--hu-azul);
            box-shadow: 0 0 0 3px rgba(0, 55, 100, .08);
        }

        .rep-date-controls {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .rep-range-toggle {
            display: flex;
            align-items: center;
            gap: .35rem;
            white-space: nowrap;
        }

        .rep-range-toggle input[type="checkbox"] {
            accent-color: var(--hu-azul);
            width: 14px;
            height: 14px;
        }

        .rep-range-toggle label {
            font-size: .72rem;
            color: var(--hu-texto);
            margin: 0;
            cursor: pointer;
        }

        .rep-date-inputs {
            display: flex;
            gap: .5rem;
        }

        .rep-filters-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem 1.5rem;
            padding: 1rem 1.25rem;
            background: #f6f9fc;
            border-radius: 10px;
            border: 1px solid rgba(0, 55, 100, .07);
            margin-bottom: 1rem;
        }

        .rep-column-toggles {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .5rem .75rem;
            margin-bottom: 1rem;
        }

        .rep-toggle {
            display: flex;
            align-items: center;
            gap: .35rem;
            font-size: .78rem;
            color: var(--hu-texto);
            cursor: pointer;
            background: #f0f4f8;
            border: 1px solid #d0dae6;
            border-radius: 6px;
            padding: .25rem .6rem;
            transition: background .15s, border-color .15s;
            margin: 0;
            user-select: none;
        }

        .rep-toggle:has(input:checked) {
            background: rgba(0, 55, 100, .08);
            border-color: rgba(0, 55, 100, .25);
            color: var(--hu-azul);
            font-weight: 600;
        }

        .rep-toggle input[type="checkbox"] {
            accent-color: var(--hu-azul);
            width: 13px;
            height: 13px;
            cursor: pointer;
        }

        .rep-export-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .8rem;
            padding: .4rem .9rem;
        }

        .rep-export-wrapper {
            margin-bottom: 1rem;
        }

        #empty-state {
            display: flex;
        }
    </style>

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
            $(document).ready(function() {
                let table, titulo;

                const estadosTemplate = $('#estados_template').html();
                const categoriasTemplate = $('#categorias_template').html();
                const depositosTemplate = $('#depositos_template').html();
                const areasTemplate = $('#areas_template').html();
                const historiasTemplate = $('#historias_template').html();
                const stockTemplate = $('#stock_template').html();
                const routersTemplate = $('#routers_template').html();
                const telefonosTemplate = $('#telefonos_template').html();
                const impresorasTemplate = $('#impresoras_template').html();
                const pcsTemplate = $('#pcs_template').html();

                function getTableId() {
                    switch ($('#addTipo').val()) {
                        case '10':
                            return 'estados';
                        case '9':
                            return 'categorias';
                        case '8':
                            return 'depositos';
                        case '7':
                            return 'areas';
                        case '6':
                            return 'historias';
                        case '5':
                            return 'componentes';
                        case '4':
                            return 'routers';
                        case '3':
                            return 'telefonos';
                        case '2':
                            return 'impresoras';
                        case '1':
                            return 'pcs';
                        default:
                            return '';
                    }
                }

                function initializeTable(templateToUse) {
                    if (table) {
                        table.destroy();
                    }
                    $('#div-table').html(templateToUse);
                    $('#addTitulo').val(titulo);

                    var simpleTables = [7, 8, 9, 10];
                    if (simpleTables.includes(parseInt($('#addTipo').val()))) {
                        table = $('#table-' + getTableId()).DataTable({
                            dom: '<"rep-export-wrapper"B>frtip',
                            buttons: [{
                                extend: 'excelHtml5',
                                text: '<span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;">download</span> Exportar Excel',
                                className: 'btn btn-hu-outline btn-sm rep-export-btn',
                                title: function() {
                                    return $('#addTitulo').val() || titulo;
                                }
                            }],
                            paging: false,
                            searching: false,
                            info: false,
                            autoWidth: true,
                            language: {
                                emptyTable: 'No hay datos disponibles en la tabla.'
                            }
                        });
                    } else {
                        table = $('#table-' + getTableId()).DataTable({
                            paging: false,
                            searching: false,
                            info: false,
                            autoWidth: true,
                            language: {
                                emptyTable: 'No hay datos disponibles en la tabla.'
                            }
                        });
                    }

                    $('.column-toggle').off('change').on('change', function() {
                        const column = table.column($(this).data('column'));
                        column.visible(!column.visible());
                        $('#table-' + getTableId()).css('min-width', '100%');
                    });
                }

                $('#addTipo').on('change', function() {
                    const map = {
                        '10': [estadosTemplate, 'Listado de estados'],
                        '9': [categoriasTemplate, 'Listado de categorías'],
                        '8': [depositosTemplate, 'Listado de depósitos'],
                        '7': [areasTemplate, 'Listado de áreas'],
                        '6': [historiasTemplate, 'Informe de movimientos'],
                        '5': [stockTemplate, 'Informe de componentes'],
                        '4': [routersTemplate, 'Informe de routers'],
                        '3': [telefonosTemplate, 'Informe de teléfonos IP'],
                        '2': [impresorasTemplate, 'Informe de impresoras'],
                        '1': [pcsTemplate, 'Informe de PCs'],
                    };
                    const entry = map[$(this).val()];
                    if (entry) {
                        titulo = entry[1];
                        initializeTable(entry[0]);
                    }
                });

                $(document).on('click', '.exportHistorias', function() {
                    var data = table.rows().data().toArray();
                    var columnNames = table.columns().header().toArray().map(h => $(h).text());
                    var selectedColumns = [];
                    $('.column-toggle:checked').each(function() {
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
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(response) {
                            var url = window.URL.createObjectURL(response);
                            var a = document.createElement('a');
                            a.href = url;
                            a.download = ($('#addTitulo').val() || 'reporte') + '.xlsx';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                        },
                        error: function() {
                            alert('Error al exportar el Excel.');
                        }
                    });
                });

                $(document).on('change', '#filter-range-hs', function() {
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
                    return date.toLocaleDateString(undefined, {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit'
                        }) +
                        ' ' + date.toLocaleTimeString();
                }

                $(document).on('change',
                    '#date-hs, #start-date-hs, #end-date-hs, #filter-range-hs, #filtro-tecnicos-hs',
                    function() {
                        $('.column-toggle').prop('checked', true);
                        $.ajax({
                            url: @json(route('filter_historias', [], false)),
                            method: 'GET',
                            data: {
                                tecnico: $('#filtro-tecnicos-hs').val(),
                                date: $('#date-hs').val(),
                                start_date: $('#start-date-hs').val(),
                                end_date: $('#end-date-hs').val(),
                            },
                            success: function(data) {
                                if (table) table.destroy();
                                $('#table-historias tbody').empty();
                                $.each(data, function(i, h) {
                                    $('#table-historias tbody').append(
                                        `<tr><td>${h.tecnico}</td><td>${h.detalle}</td><td>${h.motivo}</td><td>${formatDate(h.created_at)}</td></tr>`
                                    );
                                });
                                table = $('#table-historias').DataTable({
                                    paging: false,
                                    searching: false,
                                    info: false,
                                    autoWidth: true,
                                    language: {
                                        emptyTable: 'No hay datos disponibles en la tabla.'
                                    }
                                });
                            }
                        });
                    });

                $(document).on('change', '#filtro-deposito, #filtro-estado, #filtro-categoria, #filtro-stock',
                    function() {
                        $('.column-toggle').prop('checked', true);
                        $.ajax({
                            url: @json(route('filter_stock', [], false)),
                            method: 'GET',
                            data: {
                                deposito: $('#filtro-deposito').val(),
                                estado: $('#filtro-estado').val(),
                                categoria: $('#filtro-categoria').val(),
                                stock: $('#filtro-stock').val(),
                            },
                            success: function(data) {
                                if (table) table.destroy();
                                $('#table-componentes tbody').empty();
                                $.each(data, function(i, c) {
                                    $('#table-componentes tbody').append(
                                        `<tr><td>${c.categoria}</td><td>${c.nombre}</td><td>${c.deposito}</td><td>${c.stock}</td><td>${c.estado}</td></tr>`
                                    );
                                });
                                table = $('#table-componentes').DataTable({
                                    paging: false,
                                    searching: false,
                                    info: false,
                                    autoWidth: true,
                                    language: {
                                        emptyTable: 'No hay datos disponibles en la tabla.'
                                    }
                                });
                            }
                        });
                    });
            });
        </script>
    @endpush

</x-app-layout>
