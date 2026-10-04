<x-app-layout>
    @php
        $rolActual = data_get(Auth::user(), 'rol.nombre');
    @endphp
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
                            <label for="addNombre" class="form-label fw-semibold"
                                style="font-size:.85rem;">Nombre</label>
                            <input type="text" class="form-control @error('addNombre') is-invalid @enderror"
                                id="addNombre" name="addNombre" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="addTipo" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Categoría</label>
                                <select class="form-control @error('addTipo') is-invalid @enderror" id="addTipo"
                                    name="addTipo" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="addDeposito" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Depósito</label>
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
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Agregar stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('add_stock_componentes') }}" method="POST" id="addStockForm">
                        @csrf
                        <div class="mb-3">
                            <label for="editNombreStock" class="form-label fw-semibold"
                                style="font-size:.85rem;">Componente</label>
                            <select class="form-control @error('editNombreStock') is-invalid @enderror"
                                id="editNombreStock" name="editNombreStock" required>
                                <option value="">Selecciona un componente</option>
                                @foreach ($componentes as $componente)
                                    @if (in_array((int) $componente->estado_id, [4, 7], true))
                                        <option value="{{ $componente->id }}"
                                            data-addStock="{{ $componente->stock }}">
                                            {{ $componente->nombre . ' — ' . $componente->estado->nombre . ' — ' . ($componente->deposito->nombre ?? 'Sin depósito') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="editAddStock" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Cantidad a agregar</label>
                                <input type="number" class="form-control @error('editAddStock') is-invalid @enderror"
                                    id="editAddStock" name="editAddStock" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="editAddStockMotivo" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Motivo</label>
                                <input type="text"
                                    class="form-control @error('editAddStockMotivo') is-invalid @enderror"
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
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Eliminar stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('remove_stock_componentes') }}" method="POST" id="removeStockForm">
                        @csrf
                        <div class="mb-3">
                            <label for="removeNombreStock" class="form-label fw-semibold"
                                style="font-size:.85rem;">Componente</label>
                            <select class="form-control @error('removeNombreStock') is-invalid @enderror"
                                id="removeNombreStock" name="removeNombreStock" required>
                                <option value="">Selecciona un componente</option>
                                @foreach ($componentes as $componente)
                                    @if ((int) $componente->estado_id === 4 && (int) $componente->stock > 0)
                                        <option value="{{ $componente->id }}"
                                            data-removeStock="{{ $componente->stock }}">
                                            {{ $componente->nombre . ' — ' . $componente->estado->nombre . ' — ' . ($componente->deposito->nombre ?? 'Sin depósito') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="removeStock" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Cantidad a eliminar</label>
                                <input type="number" class="form-control @error('removeStock') is-invalid @enderror"
                                    id="removeStock" name="removeStock" min="0" required readonly>
                            </div>
                            <div class="col-6">
                                <label for="removeStockMotivo" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Motivo</label>
                                <input type="text"
                                    class="form-control @error('removeStockMotivo') is-invalid @enderror"
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
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
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
                                <label for="editNombre" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Nombre</label>
                                <input type="text" class="form-control @error('editNombre') is-invalid @enderror"
                                    id="editNombre" name="editNombre" required>
                            </div>
                            <div class="col-6">
                                <label for="editTipo" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Categoría</label>
                                <select class="form-control @error('editTipo') is-invalid @enderror" id="editTipo"
                                    name="editTipo" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo
                                del cambio</label>
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
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">¿Eliminar componente?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('delete_componentes') }}" method="POST" id="deleteComponenteForm">
                        @csrf
                        <input type="hidden" id="deleteId" name="deleteId">
                        <div class="mb-3">
                            <label for="removeMotivo" class="form-label fw-semibold"
                                style="font-size:.85rem;">Motivo</label>
                            <input type="text" class="form-control @error('removeMotivo') is-invalid @enderror"
                                id="removeMotivo" name="removeMotivo" required>
                        </div>
                        <div class="alert-hu-error mb-3" style="font-size:.82rem;">
                            <span class="material-symbols-outlined"
                                style="font-size:15px;vertical-align:middle;">warning</span>
                            Este componente se desvinculará de todos los dispositivos a los que está asociado.
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

    {{-- ============================================================
         MODAL: Transferir a depósito
         ============================================================ --}}
    <div class="modal fade" id="TransferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Transferir a depósito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('transfer_componentes') }}" method="POST" id="transferForm">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="transferNombre" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Componente</label>
                                <select class="form-control @error('transferNombre') is-invalid @enderror"
                                    id="transferNombre" name="transferNombre" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($componentes as $componente)
                                        @if (in_array((int) $componente->estado_id, [2, 4], true) && (int) $componente->stock > 0)
                                            <option value="{{ $componente->id }}"
                                                data-stock="{{ $componente->stock }}"
                                                data-estado="{{ $componente->estado_id }}">
                                                {{ $componente->nombre . ' — ' . $componente->estado->nombre . ' — ' . ($componente->deposito->nombre ?? 'Sin depósito') }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="transferDeposito" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Depósito destino</label>
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
                                <label for="transferStock" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Stock a transferir</label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('transferStock') is-invalid @enderror"
                                        id="transferStock" name="transferStock" min="0" required readonly>
                                    <button class="btn btn-hu-outline all-button" type="button"
                                        disabled>Todo</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="transferMotivo" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Motivo</label>
                                <input type="text"
                                    class="form-control @error('transferMotivo') is-invalid @enderror"
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
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Cambiar estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('state_componentes') }}" method="POST" id="transferStateForm">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="transferStateNombre" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Componente</label>
                                <select class="form-control @error('transferStateNombre') is-invalid @enderror"
                                    id="transferStateNombre" name="transferStateNombre" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($componentes as $componente)
                                        @if (in_array((int) $componente->estado_id, [2, 4], true) && $componente->stock > 0)
                                            <option value="{{ $componente->id }}"
                                                data-statestock="{{ $componente->stock }}"
                                                data-estado="{{ $componente->estado_id }}">
                                                {{ ($componente->nombre ?? '—') . ' — ' . ($componente->estado->nombre ?? '—') . ' — ' . ($componente->deposito->nombre ?? 'Sin depósito') }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Cambio de
                                    estado</label>

                                <div id="transferStateCambio"
                                    class="form-control d-flex align-items-center justify-content-center gap-2"
                                    style="background:#f8f9fa; min-height:38px; color:#6c757d;">
                                    <span id="transferStateOrigen">Seleccionar</span>
                                    <span id="transferStateFlecha" class="material-symbols-outlined d-none"
                                        style="font-size:18px;">arrow_forward</span>
                                    <span id="transferStateDestino"></span>
                                </div>

                                <input type="hidden" id="transferStateEstado" name="transferStateEstado"
                                    value="">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="transferStateStock" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Stock a cambiar</label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('transferStateStock') is-invalid @enderror"
                                        id="transferStateStock" name="transferStateStock" min="1" required
                                        readonly>
                                    <button class="btn btn-hu-outline all-state-button" type="button"
                                        disabled>Todo</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="transferStateMotivo" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Motivo</label>
                                <input type="text"
                                    class="form-control @error('transferStateMotivo') is-invalid @enderror"
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
                <span class="material-symbols-outlined"
                    style="font-size:16px;vertical-align:middle;">check_circle</span>
                <span id="selected_comp"></span>
            </span>
            <button type="button" id="unselect_comp" class="btn btn-hu-outline btn-sm" style="padding:2px 10px;">
                <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">close</span>
            </button>
        </div>

        {{-- Tarjeta principal --}}
        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">

            {{-- Barra de acciones --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="fw-semibold me-2"
                    style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--hu-azul);">
                    Listado de componentes
                </span>
                <button type="button" id="filterButton" class="btn btn-hu-outline btn-sm">
                    <span class="material-symbols-outlined"
                        style="font-size:16px;vertical-align:middle;">filter_list</span>
                    Filtrar
                </button>
                <button type="button" id="deleteFilters"
                    class="btn btn-hu-outline btn-sm d-none align-items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size:16px;line-height:1;">close</span>
                    Limpiar filtros
                </button>

                <div class="ms-auto d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addModal">
                        <span class="material-symbols-outlined"
                            style="font-size:16px;vertical-align:middle;">add</span>
                        Nuevo componente
                    </button>
                    <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addStockModal">
                        <span class="material-symbols-outlined"
                            style="font-size:16px;vertical-align:middle;">add_box</span>
                        Agregar stock
                    </button>
                    <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                        data-bs-target="#removeStockModal">
                        <span class="material-symbols-outlined"
                            style="font-size:16px;vertical-align:middle;">indeterminate_check_box</span>
                        Eliminar stock
                    </button>
                    <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                        data-bs-target="#TransferModal">
                        <span class="material-symbols-outlined"
                            style="font-size:16px;vertical-align:middle;">move_up</span>
                        Transferir
                    </button>
                    <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                        data-bs-target="#TransferStateModal">
                        <span class="material-symbols-outlined"
                            style="font-size:16px;vertical-align:middle;">published_with_changes</span>
                        Cambiar estado
                    </button>
                </div>
            </div>

            {{-- Panel de filtros --}}
            <div id="filter-div" class="d-none flex-wrap gap-3 mb-3 p-3 rounded-3" style="background:#F4F6F9;">
                <div>
                    <label for="filtro-deposito" class="form-label fw-semibold"
                        style="font-size:.8rem;">Depósito</label>
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
                    <label for="filtro-categoria" class="form-label fw-semibold"
                        style="font-size:.8rem;">Categoría</label>
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

            {{-- ============================================================
     TABLA AGRUPADA DE COMPONENTES
     ============================================================ --}}
            <style>
                .componentes-table-wrapper {
                    overflow-x: auto;
                    border: 1px solid #e4eaf0;
                    border-radius: 12px;
                }

                #table_componentes {
                    margin: 0 !important;
                    border: 0 !important;
                    border-collapse: separate;
                    border-spacing: 0;
                    color: #263b53;
                    min-width: 980px;
                }

                #table_componentes thead th {
                    background: #f7f9fb;
                    border: 0;
                    border-bottom: 1px solid #e4eaf0;
                    color: #68788b;
                    font-size: .72rem;
                    font-weight: 700;
                    letter-spacing: .055em;
                    text-transform: uppercase;
                    padding: 14px 18px;
                    white-space: nowrap;
                    vertical-align: middle;
                }

                #table_componentes tbody td {
                    background: #fff;
                    border: 0;
                    border-bottom: 1px solid #edf1f5;
                    padding: 17px 18px;
                    vertical-align: middle;
                }

                #table_componentes tbody tr:last-child td {
                    border-bottom: 0;
                }

                #table_componentes tbody tr {
                    transition: background-color .15s ease;
                }

                #table_componentes tbody tr:hover td {
                    background: #fafcff;
                }

                /* Tipo */
                .componente-tipo {
                    color: #5f7084;
                    font-size: .82rem;
                    font-weight: 600;
                }

                /* Nombre */
                .componente-nombre {
                    color: var(--hu-azul);
                    font-size: .9rem;
                    font-weight: 600;
                    line-height: 1.35;
                }

                /* Depósito */
                .componente-deposito {
                    color: #526579;
                    font-size: .84rem;
                    line-height: 1.35;
                }

                .componente-deposito-label {
                    color: #8a98a8;
                    font-size: .72rem;
                    display: block;
                    margin-bottom: 2px;
                }

                /* Resumen de estados */
                .estados-resumen {
                    display: flex;
                    align-items: stretch;
                    width: fit-content;
                    min-width: 270px;
                }

                .estado-metrica {
                    min-width: 82px;
                    padding: 0 18px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }

                .estado-metrica:first-child {
                    padding-left: 0;
                }

                .estado-metrica+.estado-metrica {
                    border-left: 1px solid #dfe5eb;
                }

                .estado-numero {
                    font-size: 1.05rem;
                    font-weight: 700;
                    line-height: 1.15;
                    margin-bottom: 3px;
                }

                .estado-numero.disponible {
                    color: #198754;
                }

                .estado-numero.en-uso {
                    color: #1769aa;
                }

                .estado-numero.roto {
                    color: #dc3545;
                }

                .estado-numero.sin-stock {
                    color: #7d8b99;
                }

                .estado-label {
                    color: #7b8998;
                    font-size: .7rem;
                    line-height: 1.15;
                    white-space: nowrap;
                }

                /* Total */
                .componente-total {
                    text-align: center;
                    min-width: 65px;
                }

                .componente-total-numero {
                    color: var(--hu-azul);
                    font-size: 1rem;
                    font-weight: 700;
                }

                /* Acciones */
                .componente-acciones {
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    gap: 6px;
                    white-space: nowrap;
                }

                .componente-accion {
                    width: 38px;
                    height: 38px;
                    padding: 0 !important;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 9px !important;
                    background: #fff;
                    border: 1px solid #dce4ec !important;
                    color: #526579;
                    transition:
                        background-color .15s ease,
                        border-color .15s ease,
                        color .15s ease,
                        transform .15s ease;
                }

                .componente-accion:hover {
                    background: #f4f8fc;
                    border-color: #b9cadb !important;
                    color: var(--hu-azul);
                    transform: translateY(-1px);
                }

                .componente-accion-primary {
                    background: #edf4fb;
                    border-color: #d9e7f4 !important;
                    color: var(--hu-azul);
                }

                .componente-accion-danger {
                    color: #c83b45;
                }

                .componente-accion-danger:hover {
                    background: #fff5f5;
                    border-color: #f0c7ca !important;
                    color: #b4232d;
                }

                .componente-accion .material-symbols-outlined {
                    font-size: 18px;
                }

                /* Menú contextual de estados */
                .componente-menu-estado {
                    min-width: 210px;
                    padding: 6px;
                    border: 1px solid #e2e8ef;
                    border-radius: 10px;
                    box-shadow: 0 8px 24px rgba(0, 55, 100, .12);
                }

                .componente-menu-titulo {
                    padding: 7px 10px 8px;
                    color: #7b8998;
                    font-size: .7rem;
                    font-weight: 700;
                    letter-spacing: .05em;
                    text-transform: uppercase;
                }

                .componente-menu-estado .dropdown-item {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 9px 10px;
                    border-radius: 7px;
                    color: #34495e;
                    font-size: .82rem;
                }

                .componente-menu-estado .dropdown-item:hover {
                    background: #f3f7fb;
                    color: var(--hu-azul);
                }

                .componente-menu-estado .dropdown-item .material-symbols-outlined {
                    font-size: 18px;
                    color: #68788b;
                }

                .componente-menu-texto {
                    display: flex;
                    flex-direction: column;
                    min-width: 0;
                    line-height: 1.25;
                }

                .componente-menu-texto>span {
                    font-weight: 600;
                }

                .componente-menu-texto small {
                    margin-top: 2px;
                    color: #8a98a8;
                    font-size: .7rem;
                }

                @media (max-width: 1100px) {
                    #table_componentes {
                        min-width: 900px;
                    }

                    .estado-metrica {
                        min-width: 72px;
                        padding: 0 13px;
                    }
                }
            </style>

            <div class="componentes-table-wrapper">
                <table id="table_componentes" class="table w-100">
                    <thead>
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Depósito</th>
                            <th scope="col">Estados</th>
                            <th scope="col" class="text-center">Total</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($componentesAgrupados as $grupo)
                            <tr data-deposito="{{ strtolower($grupo['deposito_nombre']) }}"
                                data-categoria="{{ strtolower($grupo['tipo_nombre']) }}"
                                data-disponible="{{ $grupo['disponible'] }}"
                                data-estados="{{ strtolower(
                                    implode(
                                        '|',
                                        array_filter([
                                            $grupo['disponible'] > 0 ? 'disponible' : 'sin stock',
                                            $grupo['en_uso'] > 0 ? 'en uso' : null,
                                            $grupo['roto'] > 0 ? 'roto' : null,
                                        ]),
                                    ),
                                ) }}">

                                {{-- Tipo --}}
                                <td>
                                    <span class="componente-tipo">
                                        {{ $grupo['tipo_nombre'] }}
                                    </span>
                                </td>

                                {{-- Nombre --}}
                                <td>
                                    <span class="componente-nombre">
                                        {{ $grupo['nombre'] }}
                                    </span>
                                </td>

                                {{-- Depósito --}}
                                <td>
                                    <span class="componente-deposito-label">Ubicación</span>
                                    <span class="componente-deposito">
                                        {{ $grupo['deposito_nombre'] }}
                                    </span>
                                </td>

                                {{-- Estados --}}
                                <td>
                                    <div class="estados-resumen">

                                        @if ($grupo['disponible'] > 0)
                                            <div class="estado-metrica">
                                                <span class="estado-numero disponible">
                                                    {{ $grupo['disponible'] }}
                                                </span>
                                                <span class="estado-label">
                                                    Disponibles
                                                </span>
                                            </div>
                                        @else
                                            <div class="estado-metrica">
                                                <span class="estado-numero sin-stock">
                                                    0
                                                </span>
                                                <span class="estado-label">
                                                    Sin stock
                                                </span>
                                            </div>
                                        @endif

                                        @if ($grupo['en_uso'] > 0)
                                            <div class="estado-metrica">
                                                <span class="estado-numero en-uso">
                                                    {{ $grupo['en_uso'] }}
                                                </span>
                                                <span class="estado-label">
                                                    En uso
                                                </span>
                                            </div>
                                        @endif

                                        @if ($grupo['roto'] > 0)
                                            <div class="estado-metrica">
                                                <span class="estado-numero roto">
                                                    {{ $grupo['roto'] }}
                                                </span>
                                                <span class="estado-label">
                                                    Rotos
                                                </span>
                                            </div>
                                        @endif

                                    </div>
                                </td>

                                {{-- Total --}}
                                <td>
                                    <div class="componente-total">
                                        <span class="componente-total-numero">
                                            {{ $grupo['total'] }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Acciones --}}
                                <td>
                                    @if (in_array($rolActual, ['Administrador', 'Super administrador', 'Tecnico'], true))
                                        <div class="componente-acciones">

                                            {{-- Operar sobre un estado específico --}}
                                            @if ($grupo['acciones']['disponible'] || $grupo['acciones']['roto'])
                                                <div class="dropdown">
                                                    <button type="button" class="btn componente-accion"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="false" title="Operar sobre">
                                                        <span class="material-symbols-outlined">more_vert</span>
                                                    </button>

                                                    <div
                                                        class="dropdown-menu dropdown-menu-end componente-menu-estado">

                                                        <div class="componente-menu-titulo">
                                                            Operar sobre
                                                        </div>

                                                        @if ($grupo['acciones']['disponible'])
                                                            <button type="button" class="dropdown-item select-button"
                                                                data-id="{{ $grupo['acciones']['disponible'] }}"
                                                                data-nombre="{{ $grupo['nombre'] }} — Disponible">
                                                                <span
                                                                    class="material-symbols-outlined">inventory_2</span>
                                                                <span class="componente-menu-texto">
                                                                    <span>Disponible</span>
                                                                    <small>{{ $grupo['disponible'] }}
                                                                        {{ $grupo['disponible'] == 1 ? 'unidad' : 'unidades' }}</small>
                                                                </span>
                                                            </button>
                                                        @endif

                                                        @if ($grupo['acciones']['roto'])
                                                            <button type="button" class="dropdown-item select-button"
                                                                data-id="{{ $grupo['acciones']['roto'] }}"
                                                                data-nombre="{{ $grupo['nombre'] }} — Roto">
                                                                <span
                                                                    class="material-symbols-outlined">broken_image</span>
                                                                <span class="componente-menu-texto">
                                                                    <span>Roto</span>
                                                                    <small>{{ $grupo['roto'] }}
                                                                        {{ $grupo['roto'] == 1 ? 'unidad' : 'unidades' }}</small>
                                                                </span>
                                                            </button>
                                                        @endif

                                                    </div>
                                                </div>
                                            @endif

                                            @php
                                                $idEdicion = $grupo['filas']->first()->id;
                                            @endphp

                                            <button type="button"
                                                class="btn componente-accion componente-accion-primary"
                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-id="{{ $idEdicion }}" data-nombre="{{ $grupo['nombre'] }}"
                                                data-tipo="{{ $grupo['tipo_id'] }}" title="Editar">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>

                                            @if ($rolActual === 'Super administrador')
                                                <button type="button"
                                                    class="btn componente-accion componente-accion-danger"
                                                    data-id="{{ $idEdicion }}"
                                                    data-nombre="{{ $grupo['nombre'] }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal" title="Eliminar">
                                                    <span class="material-symbols-outlined">delete</span>
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
            <div class="hu-table-section"></div>
            <h2 class="hu-table-section-title">
                <span class="material-symbols-outlined" style="font-size:18px;">history</span>
                Historial de cambios
            </h2>
            <div class="table-responsive">
                <table id="table_historias" class="table hu-modern-table w-100">
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
                                <td>{{ $historia->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /tarjeta --}}
    </div>{{-- /container --}}


    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    @endpush
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    @push('vendor-scripts')
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {

                let componente_seleccionado = null;

                // ── Tablas: stock e historial ─────────────────────────────────────
                const componentesTable = $('#table_componentes').DataTable({
                    order: [],
                    autoWidth: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 5
                    }]
                });

                $('#table_historias').DataTable({
                    order: [
                        [3, 'desc']
                    ],
                    autoWidth: false
                });

                // Los filtros de stock se integran con DataTables para que también
                // respeten la paginación y no queden filas ocultas fuera de página.
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (!settings.nTable || settings.nTable.id !== 'table_componentes') return true;
                    const row = settings.aoData[dataIndex]?.nTr;
                    if (!row) return true;

                    const $row = $(row);
                    const deposito = String($('#filtro-deposito').val() || '').toLowerCase();
                    const estado = String($('#filtro-estado').val() || '').toLowerCase();
                    const categoria = String($('#filtro-categoria').val() || '').toLowerCase();
                    const stockFilter = $('#filtro-stock').val();

                    const rowDeposito = String($row.data('deposito') || '').toLowerCase();
                    const rowEstado = String($row.data('estados') || '').toLowerCase();
                    const rowCategoria = String($row.data('categoria') || '').toLowerCase();
                    const rowStock = Number($row.data('disponible') || 0);

                    const matchesEstado = !estado || rowEstado.split('|').includes(estado);
                    const matchesStock = !stockFilter ||
                        (stockFilter === 'poco-stock' && rowStock > 0 && rowStock < 10) ||
                        (stockFilter === 'sin-stock' && rowStock === 0);

                    return (!deposito || rowDeposito === deposito) &&
                        matchesEstado &&
                        (!categoria || rowCategoria === categoria) &&
                        matchesStock;
                });

                // ── Seleccionar componente desde la tabla ──
                $('#table_componentes tbody').on('click', '.select-button', function() {
                    componente_seleccionado = $(this).data('id');
                    $('#selected_comp').text('Seleccionado: ' + $(this).data('nombre'));
                    $('#selected_info').removeClass('d-none').addClass('d-flex');
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });

                $('#unselect_comp').on('click', function() {
                    componente_seleccionado = null;
                    $('#selected_info').removeClass('d-flex').addClass('d-none');
                });

                // ── Pre-seleccionar componente al abrir modales ──
                $('#addStockModal').on('show.bs.modal', function() {
                    $(this).find('#editNombreStock').val(componente_seleccionado).trigger('change').attr(
                        'readonly', !!componente_seleccionado);
                });
                $('#removeStockModal').on('show.bs.modal', function() {
                    $(this).find('#removeNombreStock').val(componente_seleccionado).trigger('change').attr(
                        'readonly', !!componente_seleccionado);
                });
                $('#TransferModal').on('show.bs.modal', function() {
                    $(this).find('#transferNombre').val(componente_seleccionado).trigger('change').attr(
                        'readonly', !!componente_seleccionado);
                });
                $('#TransferStateModal').on('show.bs.modal', function() {
                    $(this).find('#transferStateNombre').val(componente_seleccionado).trigger('change').attr(
                        'readonly', !!componente_seleccionado);
                });

                // ── Edit modal: cargar datos ──
                $('#editModal').on('show.bs.modal', function(event) {
                    const btn = $(event.relatedTarget);
                    $(this).find('#editId').val(btn.data('id'));
                    $(this).find('#editNombre').val(btn.data('nombre'));
                    $(this).find('#editTipo').val(btn.data('tipo'));
                });

                // ── Delete modal: cargar id ──
                $('#deleteModal').on('show.bs.modal', function(event) {
                    const btn = $(event.relatedTarget);
                    $(this).find('#deleteId').val(btn.data('id'));
                });

                // ── Filtros ──
                $('#filterButton').on('click', function() {
                    const $div = $('#filter-div');
                    const visible = !$div.hasClass('d-none');
                    $div.toggleClass('d-none', visible).toggleClass('d-flex', !visible);
                });

                function updateClearFiltersButton() {
                    const hasActiveFilters = $('#filtro-deposito, #filtro-estado, #filtro-categoria, #filtro-stock')
                        .filter(function() {
                            return $(this).val() !== '';
                        }).length > 0;

                    $('#deleteFilters').toggleClass('d-none', !hasActiveFilters)
                        .toggleClass('d-inline-flex', hasActiveFilters);
                }

                function applyFilters() {
                    componentesTable.draw();
                }

                $('#filtro-deposito, #filtro-estado, #filtro-categoria, #filtro-stock').on('change', function() {
                    applyFilters();
                    updateClearFiltersButton();
                });

                $('#deleteFilters').on('click', function() {
                    $('#filtro-deposito, #filtro-estado, #filtro-categoria, #filtro-stock').val('').trigger(
                        'change');
                });

                // ── Stock: eliminar (habilitar campo al elegir componente) ──
                $('#removeNombreStock').on('change', function() {
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
                $('#transferNombre').on('change', function() {
                    const input = document.getElementById('transferStock');
                    const btn = document.querySelector('.all-button');
                    const stock = this.options[this.selectedIndex].getAttribute('data-stock');
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
                document.querySelector('.all-button').addEventListener('click', function() {
                    if (!this.hasAttribute('disabled')) {
                        const sel = document.getElementById('transferNombre');
                        document.getElementById('transferStock').value = sel.options[sel.selectedIndex]
                            .getAttribute('data-stock');
                    }
                });

                // ── Cambiar estado: habilitar campo de cantidad ──
                $('#transferStateNombre').on('change', function() {
                    const input = document.getElementById('transferStateStock');
                    const btn = document.querySelector('.all-state-button');

                    const estadoHidden = document.getElementById('transferStateEstado');
                    const estadoOrigen = document.getElementById('transferStateOrigen');
                    const estadoDestino = document.getElementById('transferStateDestino');
                    const flecha = document.getElementById('transferStateFlecha');

                    const selectedOption = this.options[this.selectedIndex];
                    const stock = selectedOption?.getAttribute('data-statestock');
                    const estadoActual = Number(selectedOption?.getAttribute('data-estado'));

                    // Reiniciar la información de la transición.
                    estadoHidden.value = '';
                    estadoOrigen.textContent = 'Seleccionar';
                    estadoDestino.textContent = '';
                    flecha.classList.add('d-none');

                    if (estadoActual === 4) {
                        // Disponible → Roto
                        estadoHidden.value = '2';
                        estadoOrigen.textContent = 'Disponible';
                        estadoDestino.textContent = 'Roto';
                        flecha.classList.remove('d-none');
                    } else if (estadoActual === 2) {
                        // Roto → Disponible
                        estadoHidden.value = '4';
                        estadoOrigen.textContent = 'Roto';
                        estadoDestino.textContent = 'Disponible';
                        flecha.classList.remove('d-none');
                    }

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
                document.querySelector('.all-state-button').addEventListener('click', function() {
                    if (!this.hasAttribute('disabled')) {
                        const sel = document.getElementById('transferStateNombre');
                        document.getElementById('transferStateStock').value = sel.options[sel.selectedIndex]
                            .getAttribute('data-statestock');
                    }
                });

            });
        </script>
    @endpush

</x-app-layout>
