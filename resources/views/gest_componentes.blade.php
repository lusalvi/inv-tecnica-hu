<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg leading-tight" style="color: var(--hu-azul);">
            {{ __('Componentes') }}
            <button type="button" class="btn btn-hu-outline-dorado btn-sm ms-2" data-bs-toggle="modal"
                data-bs-target="#infoModal" style="padding: 2px 8px; font-size: .78rem;">
                <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">info</span>
            </button>
        </h2>
    </x-slot>

    {{-- ============================================================
         MODALES DE INFORMACIÓN (ayuda contextual)
         ============================================================ --}}
    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Componentes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    Aquí se agregan los componentes para llevar un control del stock.
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoAddStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Agregar stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    El stock ingresado se sumará al stock existente.
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoRemoveStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Eliminar stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    El stock ingresado se restará al stock existente.
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoTransferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Transferir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    El componente se transferirá al depósito seleccionado, modificando los stocks.
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Nuevo componente
         ============================================================ --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Agregar nuevo componente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store_componentes') }}" method="POST" id="addComponenteForm">
                        @csrf
                        <div class="mb-3">
                            <label for="addNombre" class="form-label fw-semibold" style="font-size:.85rem;">Nombre</label>
                            <input type="text" class="form-control @error('addNombre') is-invalid @enderror"
                                id="addNombre" name="addNombre" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="addTipo" class="form-label fw-semibold" style="font-size:.85rem;">Categoría</label>
                                <select class="form-control @error('addTipo') is-invalid @enderror"
                                    id="addTipo" name="addTipo" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="addDeposito" class="form-label fw-semibold" style="font-size:.85rem;">Depósito</label>
                                <select class="form-control @error('addDeposito') is-invalid @enderror"
                                    id="addDeposito" name="addDeposito" required>
                                    <option value="">Seleccionar depósito</option>
                                    @foreach ($depositos as $deposito)
                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-0 pb-0 mt-3">
                            <button type="submit" class="btn btn-hu w-100">Agregar componente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Agregar stock
         ============================================================ --}}
    <div class="modal fade" id="addStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Agregar stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('add_stock_componentes') }}" method="POST" id="addStockForm">
                        @csrf
                        <div class="mb-3">
                            <label for="editNombreStock" class="form-label fw-semibold" style="font-size:.85rem;">Componente</label>
                            <select class="form-control @error('editNombreStock') is-invalid @enderror"
                                id="editNombreStock" name="editNombreStock" required>
                                <option value="">Selecciona un componente</option>
                                @foreach ($componentes as $componente)
                                    <option value="{{ $componente->id }}" data-addStock="{{ $componente->stock }}">
                                        {{ $componente->nombre . ' — ' . $componente->estado->nombre . ' — ' . ($componente->deposito->nombre ?? 'sin depósito') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="editAddStock" class="form-label fw-semibold" style="font-size:.85rem;">Cantidad a agregar</label>
                                <input type="number" class="form-control @error('editAddStock') is-invalid @enderror"
                                    id="editAddStock" name="editAddStock" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="editAddStockMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo</label>
                                <input type="text" class="form-control @error('editAddStockMotivo') is-invalid @enderror"
                                    id="editAddStockMotivo" name="editAddStockMotivo" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-0 pb-0 mt-3">
                            <button type="submit" class="btn btn-hu w-100">Agregar stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Eliminar stock
         ============================================================ --}}
    <div class="modal fade" id="removeStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Eliminar stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('remove_stock_componentes') }}" method="POST" id="removeStockForm">
                        @csrf
                        <div class="mb-3">
                            <label for="removeNombreStock" class="form-label fw-semibold" style="font-size:.85rem;">Componente</label>
                            <select class="form-control @error('removeNombreStock') is-invalid @enderror"
                                id="removeNombreStock" name="removeNombreStock" required>
                                <option value="">Selecciona un componente</option>
                                @foreach ($componentes as $componente)
                                    <option value="{{ $componente->id }}" data-removeStock="{{ $componente->stock }}">
                                        {{ $componente->nombre . ' — ' . $componente->estado->nombre . ' — ' . ($componente->deposito->nombre ?? 'sin depósito') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="removeStock" class="form-label fw-semibold" style="font-size:.85rem;">Cantidad a eliminar</label>
                                <input type="number" class="form-control @error('removeStock') is-invalid @enderror"
                                    id="removeStock" name="removeStock" min="0" required readonly>
                            </div>
                            <div class="col-6">
                                <label for="removeStockMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo</label>
                                <input type="text" class="form-control @error('removeStockMotivo') is-invalid @enderror"
                                    id="removeStockMotivo" name="removeStockMotivo" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-0 pb-0 mt-3">
                            <button type="submit" class="btn btn-hu w-100">Eliminar stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Editar componente
         ============================================================ --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Editar componente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit_componentes') }}" method="POST" id="editComponenteForm">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="editId" id="editId">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="editNombre" class="form-label fw-semibold" style="font-size:.85rem;">Nombre</label>
                                <input type="text" class="form-control @error('editNombre') is-invalid @enderror"
                                    id="editNombre" name="editNombre" required>
                            </div>
                            <div class="col-6">
                                <label for="editTipo" class="form-label fw-semibold" style="font-size:.85rem;">Categoría</label>
                                <select class="form-control @error('editTipo') is-invalid @enderror"
                                    id="editTipo" name="editTipo" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo del cambio</label>
                            <input type="text" class="form-control @error('editMotivo') is-invalid @enderror"
                                id="editMotivo" name="editMotivo" required>
                        </div>
                        <div class="modal-footer border-0 px-0 pb-0">
                            <button type="submit" class="btn btn-hu w-100">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Eliminar componente
         ============================================================ --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">¿Eliminar componente?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('delete_componentes') }}" method="POST" id="deleteComponenteForm">
                        @csrf
                        <input type="hidden" id="deleteId" name="deleteId">
                        <div class="mb-3">
                            <label for="removeMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo</label>
                            <input type="text" class="form-control @error('removeMotivo') is-invalid @enderror"
                                id="removeMotivo" name="removeMotivo" required>
                        </div>
                        <div class="alert-hu-error mb-3" style="font-size:.82rem;">
                            <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">warning</span>
                            Este componente se desvinculará de todos los dispositivos a los que está asociado.
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-hu-outline flex-fill" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger flex-fill">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Transferir a depósito
         ============================================================ --}}
    <div class="modal fade" id="TransferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Transferir a depósito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('transfer_componentes') }}" method="POST" id="transferForm">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="transferNombre" class="form-label fw-semibold" style="font-size:.85rem;">Componente</label>
                                <select class="form-control @error('transferNombre') is-invalid @enderror"
                                    id="transferNombre" name="transferNombre" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($componentes as $componente)
                                        <option value="{{ $componente->id }}" data-stock="{{ $componente->stock }}">
                                            {{ $componente->nombre . ' — ' . $componente->estado->nombre . ' — ' . ($componente->deposito->nombre ?? 'sin depósito') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="transferDeposito" class="form-label fw-semibold" style="font-size:.85rem;">Depósito destino</label>
                                <select class="form-control @error('transferDeposito') is-invalid @enderror"
                                    id="transferDeposito" name="transferDeposito" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($depositos as $deposito)
                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="transferStock" class="form-label fw-semibold" style="font-size:.85rem;">Stock a transferir</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('transferStock') is-invalid @enderror"
                                        id="transferStock" name="transferStock" min="0" required readonly>
                                    <button class="btn btn-hu-outline all-button" type="button" disabled>Todo</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="transferMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo</label>
                                <input type="text" class="form-control @error('transferMotivo') is-invalid @enderror"
                                    id="transferMotivo" name="transferMotivo" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-0 pb-0">
                            <button type="submit" class="btn btn-hu w-100">Transferir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Cambiar estado
         ============================================================ --}}
    <div class="modal fade" id="TransferStateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Cambiar estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('state_componentes') }}" method="POST" id="transferStateForm">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="transferStateNombre" class="form-label fw-semibold" style="font-size:.85rem;">Componente</label>
                                <select class="form-control @error('transferStateNombre') is-invalid @enderror"
                                    id="transferStateNombre" name="transferStateNombre" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($componentes as $componente)
                                        <option value="{{ $componente->id }}" data-statestock="{{ $componente->stock }}">
                                            {{ ($componente->nombre ?? '—') . ' — ' . ($componente->estado->nombre ?? '—') . ' — ' . ($componente->deposito->nombre ?? 'sin depósito') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="transferStateEstado" class="form-label fw-semibold" style="font-size:.85rem;">Nuevo estado</label>
                                <select class="form-control @error('transferStateEstado') is-invalid @enderror"
                                    id="transferStateEstado" name="transferStateEstado" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="transferStateStock" class="form-label fw-semibold" style="font-size:.85rem;">Stock a cambiar</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('transferStateStock') is-invalid @enderror"
                                        id="transferStateStock" name="transferStateStock" min="0" required readonly>
                                    <button class="btn btn-hu-outline all-state-button" type="button" disabled>Todo</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="transferStateMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo</label>
                                <input type="text" class="form-control @error('transferStateMotivo') is-invalid @enderror"
                                    id="transferStateMotivo" name="transferStateMotivo" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-0 pb-0">
                            <button type="submit" class="btn btn-hu w-100">Cambiar estado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         CONTENIDO PRINCIPAL
         ============================================================ --}}
    <div class="px-4 px-md-5 py-4" style="max-width:1400px; margin:0 auto;">

        {{-- Alertas de validación --}}
        @error('addNombre')
            <div class="alert-hu-error mb-3">{{ $message }}</div>
        @enderror

        {{-- Componente seleccionado (banner) --}}
        <div id="selected_info" class="mb-3 p-3 rounded-3 d-none align-items-center justify-content-between"
            style="background:#EAF3FF; border:1px solid #B8D4F0;">
            <span style="font-size:.85rem; color:var(--hu-azul); font-weight:600;">
                <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">check_circle</span>
                <span id="selected_comp"></span>
            </span>
            <button id="unselect_comp" class="btn btn-hu-outline btn-sm" style="padding:2px 10px;">
                <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">close</span>
            </button>
        </div>

        {{-- Tarjeta principal --}}
        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">

            {{-- Barra de acciones --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <button id="filterButton" class="btn btn-hu-outline btn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">filter_list</span>
                    Filtrar
                </button>
                <button id="deleteFilters" class="btn btn-hu-outline btn-sm" style="display:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">close</span>
                    Limpiar filtros
                </button>

                <div class="ms-auto d-flex flex-wrap gap-2">
                    <button class="btn btn-hu btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">add</span>
                        Nuevo componente
                    </button>
                    <button class="btn btn-hu btn-sm" data-bs-toggle="modal" data-bs-target="#addStockModal">
                        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">add_box</span>
                        Agregar stock
                    </button>
                    <button class="btn btn-hu btn-sm" data-bs-toggle="modal" data-bs-target="#removeStockModal">
                        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">indeterminate_check_box</span>
                        Eliminar stock
                    </button>
                    <button class="btn btn-hu btn-sm" data-bs-toggle="modal" data-bs-target="#TransferModal">
                        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">move_up</span>
                        Transferir
                    </button>
                    <button class="btn btn-hu btn-sm" data-bs-toggle="modal" data-bs-target="#TransferStateModal">
                        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">published_with_changes</span>
                        Cambiar estado
                    </button>
                </div>
            </div>

            {{-- Panel de filtros --}}
            <div id="filter-div" class="d-flex flex-wrap gap-3 mb-3 p-3 rounded-3" style="display:none !important; background:#F4F6F9;">
                <div>
                    <label for="filtro-deposito" class="form-label fw-semibold" style="font-size:.8rem;">Depósito</label>
                    <select id="filtro-deposito" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        @foreach ($depositos as $deposito)
                            <option value="{{ $deposito->nombre }}">{{ $deposito->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filtro-estado" class="form-label fw-semibold" style="font-size:.8rem;">Estado</label>
                    <select id="filtro-estado" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filtro-categoria" class="form-label fw-semibold" style="font-size:.8rem;">Categoría</label>
                    <select id="filtro-categoria" class="form-control form-control-sm">
                        <option value="">Todas</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filtro-stock" class="form-label fw-semibold" style="font-size:.8rem;">Stock</label>
                    <select id="filtro-stock" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="poco-stock">Poco stock</option>
                        <option value="sin-stock">Sin stock</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de componentes --}}
            <div class="table-responsive">
                <table id="table_componentes" class="table table-bordered table-striped w-100">
                    <thead style="display:none;">
                        <tr>
                            <th>Categoría</th>
                            <th>Nombre</th>
                            <th>Depósito</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($componentes as $componente)
                            <tr>
                                <td><b>{{ $componente->tipo->nombre ?? 'Sin categoría' }}</b></td>
                                <td><b>{{ $componente->nombre }}</b></td>
                                <td><b>Depósito: {{ $componente->deposito->nombre ?? 'No asignado' }}</b></td>
                                <td><b>Stock: {{ $componente->stock }}</b></td>
                                <td><b>Estado: {{ $componente->estado->nombre ?? 'No asignado' }}</b></td>
                                <td>
                                    @if (Auth::user()->rol->nombre == 'Administrador' || Auth::user()->rol->nombre == 'Super administrador' || Auth::user()->rol->nombre == 'Tecnico')
                                        <div class="d-flex justify-content-end gap-1">
                                            <button class="btn btn-hu btn-sm select-button"
                                                data-id="{{ $componente->id }}"
                                                data-nombre="{{ $componente->nombre }}"
                                                title="Seleccionar">
                                                <span class="material-symbols-outlined" style="font-size:15px;">check</span>
                                            </button>
                                            <button class="btn btn-hu btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="{{ $componente->id }}"
                                                data-nombre="{{ $componente->nombre }}"
                                                data-tipo="{{ $componente->tipo_id }}"
                                                title="Editar">
                                                <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                            </button>
                                            @if (Auth::user()->rol->nombre == 'Super administrador')
                                                <button class="btn btn-danger btn-sm"
                                                    data-id="{{ $componente->id }}"
                                                    data-nombre="{{ $componente->nombre }}"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    title="Eliminar">
                                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Historial de cambios --}}
            <h2 class="mt-4 mb-3 fw-semibold" style="font-size:1rem; color:var(--hu-azul); border-top:2px solid var(--hu-azul); padding-top:1rem;">
                Historial de cambios
            </h2>
            <div class="table-responsive">
                <table id="table_historias" class="table table-bordered table-striped w-100">
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
                                <td><b>{{ $historia->tecnico }}</b></td>
                                <td><b>{{ $historia->detalle }}</b></td>
                                <td><b>{{ $historia->motivo }}</b></td>
                                <td><b>{{ $historia->created_at }}</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>{{-- /tarjeta --}}
    </div>{{-- /container --}}


    @push('scripts')
    <script>
    $(document).ready(function () {

        let componente_seleccionado = null;

        // ── Seleccionar componente desde la tabla ──
        $('#table_componentes tbody').on('click', '.select-button', function () {
            componente_seleccionado = $(this).data('id');
            $('#selected_comp').text('Seleccionado: ' + $(this).data('nombre'));
            $('#selected_info').removeClass('d-none').addClass('d-flex');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        $('#unselect_comp').on('click', function () {
            componente_seleccionado = null;
            $('#selected_info').removeClass('d-flex').addClass('d-none');
        });

        // ── Pre-seleccionar componente al abrir modales ──
        $('#addStockModal').on('show.bs.modal', function () {
            $(this).find('#editNombreStock').val(componente_seleccionado).trigger('change').attr('readonly', !!componente_seleccionado);
        });
        $('#removeStockModal').on('show.bs.modal', function () {
            $(this).find('#removeNombreStock').val(componente_seleccionado).trigger('change').attr('readonly', !!componente_seleccionado);
        });
        $('#TransferModal').on('show.bs.modal', function () {
            $(this).find('#transferNombre').val(componente_seleccionado).trigger('change').attr('readonly', !!componente_seleccionado);
        });
        $('#TransferStateModal').on('show.bs.modal', function () {
            $(this).find('#transferStateNombre').val(componente_seleccionado).trigger('change').attr('readonly', !!componente_seleccionado);
        });

        // ── Edit modal: cargar datos ──
        $('#editModal').on('show.bs.modal', function (event) {
            const btn = $(event.relatedTarget);
            $(this).find('#editId').val(btn.data('id'));
            $(this).find('#editNombre').val(btn.data('nombre'));
            $(this).find('#editTipo').val(btn.data('tipo'));
        });

        // ── Delete modal: cargar id ──
        $('#deleteModal').on('show.bs.modal', function (event) {
            const btn = $(event.relatedTarget);
            $(this).find('#deleteId').val(btn.data('id'));
        });

        // ── Filtros ──
        $('#filterButton').on('click', function () {
            const $div = $('#filter-div');
            const $del = $('#deleteFilters');
            const visible = $div.css('display') !== 'none';
            $div.css('display', visible ? 'none' : 'flex');
            $del.css('display', visible ? 'none' : 'inline-flex');
        });
        $('#deleteFilters').on('click', function () {
            $('#filtro-deposito, #filtro-estado, #filtro-categoria, #filtro-stock').val('').trigger('change');
        });

        // ── Stock: eliminar (habilitar campo al elegir componente) ──
        $('#removeNombreStock').on('change', function () {
            const input = document.getElementById('removeStock');
            const stock = this.options[this.selectedIndex].getAttribute('data-removeStock');
            if (this.value) {
                input.removeAttribute('readonly');
                input.max = stock;
            } else {
                input.setAttribute('readonly', true);
                input.value = '';
            }
        });

        // ── Transferir: habilitar campo de cantidad ──
        $('#transferNombre').on('change', function () {
            const input  = document.getElementById('transferStock');
            const btn    = document.querySelector('.all-button');
            const stock  = this.options[this.selectedIndex].getAttribute('data-stock');
            if (this.value) {
                input.removeAttribute('readonly');
                btn.removeAttribute('disabled');
                input.max = stock;
            } else {
                input.setAttribute('readonly', true);
                btn.setAttribute('disabled', true);
                input.value = '';
            }
        });
        document.querySelector('.all-button').addEventListener('click', function () {
            if (!this.hasAttribute('disabled')) {
                const sel = document.getElementById('transferNombre');
                document.getElementById('transferStock').value = sel.options[sel.selectedIndex].getAttribute('data-stock');
            }
        });

        // ── Cambiar estado: habilitar campo de cantidad ──
        $('#transferStateNombre').on('change', function () {
            const input = document.getElementById('transferStateStock');
            const btn   = document.querySelector('.all-state-button');
            const stock = this.options[this.selectedIndex].getAttribute('data-statestock');
            if (this.value) {
                input.removeAttribute('readonly');
                btn.removeAttribute('disabled');
                input.max = stock;
            } else {
                input.setAttribute('readonly', true);
                btn.setAttribute('disabled', true);
                input.value = '';
            }
        });
        document.querySelector('.all-state-button').addEventListener('click', function () {
            if (!this.hasAttribute('disabled')) {
                const sel = document.getElementById('transferStateNombre');
                document.getElementById('transferStateStock').value = sel.options[sel.selectedIndex].getAttribute('data-statestock');
            }
        });

    });
    </script>
    @endpush

</x-app-layout>