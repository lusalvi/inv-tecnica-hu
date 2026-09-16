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

    {{-- Modal: Armar PC --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Armar PC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store_pc') }}" method="POST" id="addPcForm"
                        style="display:flex;flex-direction:column;gap:20px;">
                        @csrf

                        {{-- Checkboxes --}}
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="en-uso">
                                <label class="form-check-label" style="font-size:.85rem;" for="en-uso">En
                                    uso</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="componentes-no-identificables">
                                <label class="form-check-label" style="font-size:.85rem;"
                                    for="componentes-no-identificables">
                                    Componentes no identificables
                                </label>
                            </div>
                        </div>

                        {{-- Identificador / Nombre / IP --}}
                        <div class="row g-3">
                            <div class="col-auto">
                                <label for="addIdentificador" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Nº Inventario</label>
                                <input type="text"
                                    class="form-control @error('addIdentificador') is-invalid @enderror"
                                    id="addIdentificador" name="addIdentificador" placeholder="Nº Inventario"
                                    style="max-width:165px;" required>
                            </div>
                            <div class="col-auto">
                                <label for="addNombre" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Nombre</label>
                                <input type="text" class="form-control @error('addNombre') is-invalid @enderror"
                                    id="addNombre" name="addNombre" placeholder="Nombre" style="max-width:165px;"
                                    required>
                            </div>
                            <div class="col-auto">
                                <label for="addIp" class="form-label fw-semibold"
                                    style="font-size:.85rem;">IPv4</label>
                                <input type="text" class="form-control @error('addIp') is-invalid @enderror"
                                    id="addIp" name="addIp" placeholder="Dirección IPv4"
                                    style="max-width:165px;" required>
                            </div>
                        </div>

                        {{-- Área / Depósito --}}
                        <div class="row g-3">
                            <div class="col-auto" id="content-container">
                                <div id="area-select">
                                    <label for="addArea" class="form-label fw-semibold"
                                        style="font-size:.85rem;">Área</label>
                                    <select class="form-control @error('addArea') is-invalid @enderror" id="addArea"
                                        name="addArea" style="min-width:165px;max-width:165px;" required>
                                        <option value="" disabled selected>Selecciona un área</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2" id="addNroConsul_div" style="display:none;">
                                        <label for="addNroConsul" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Nº consultorio</label>
                                        <input type="text"
                                            class="form-control @error('addNroConsul') is-invalid @enderror"
                                            id="addNroConsul" name="addNroConsul" placeholder="Nº de consultorio"
                                            style="max-width:165px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto" id="deposito-select">
                                <label for="addDeposito" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Depósito</label>
                                <select class="form-control @error('addDeposito') is-invalid @enderror"
                                    id="addDeposito" name="addDeposito" style="min-width:165px;max-width:165px;"
                                    required>
                                    <option value="" disabled selected>Selecciona un depósito</option>
                                    @foreach ($depositos as $deposito)
                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Componentes --}}
                        <div id="div-componentes-add"
                            style="display:flex;flex-direction:row;gap:20px;flex-wrap:wrap;">
                            <div style="display:flex;flex-direction:column;gap:20px;">
                                <div class="row g-3">
                                    <div class="col-auto">
                                        <label for="addMotherboard" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Placa madre</label>
                                        <select class="form-control @error('addMotherboard') is-invalid @enderror"
                                            id="addMotherboard" name="addMotherboard" style="max-width:165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona una placa madre
                                            </option>
                                            @foreach ($motherboards as $motherboard)
                                                <option value="{{ $motherboard->id }}">
                                                    {{ $motherboard->nombre . ' - ' . ($motherboard->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="addProcesador" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Procesador</label>
                                        <select class="form-control @error('addProcesador') is-invalid @enderror"
                                            id="addProcesador" name="addProcesador" style="max-width:165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona un procesador</option>
                                            @foreach ($procesadores as $procesador)
                                                <option value="{{ $procesador->id }}">
                                                    {{ $procesador->nombre . ' - ' . ($procesador->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-auto">
                                        <label for="addPlacavid" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Placa de video</label>
                                        <select class="form-control @error('addPlacavid') is-invalid @enderror"
                                            id="addPlacavid" name="addPlacavid" style="max-width:165px;">
                                            <option value="" disabled selected>Selecciona una placa de video
                                            </option>
                                            @foreach ($placasvid as $placavid)
                                                <option value="{{ $placavid->id }}">
                                                    {{ $placavid->nombre . ' - ' . ($placavid->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="addFuente" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Fuente</label>
                                        <select class="form-control @error('addFuente') is-invalid @enderror"
                                            id="addFuente" name="addFuente" style="max-width:165px;" required>
                                            <option value="" disabled selected>Selecciona una fuente</option>
                                            @foreach ($fuentes as $fuente)
                                                <option value="{{ $fuente->id }}">
                                                    {{ $fuente->nombre . ' - ' . ($fuente->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Discos</label>
                                <div id="input-container-1">
                                    <div class="form-group input-group mb-3">
                                        <select id="discos1-1" name="discos1[]" class="form-control"
                                            style="border-top-left-radius:5px;border-bottom-left-radius:5px;max-width:165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona un disco</option>
                                            @foreach ($discos as $disco)
                                                <option value="{{ $disco->id }}"
                                                    data-stock="{{ $disco->stock }}">
                                                    @if ($disco->tipo->nombre == 'SDD')
                                                        {{ $disco->nombre . ' - SDD - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                    @endif
                                                    @if ($disco->tipo->nombre == 'HDD')
                                                        {{ $disco->nombre . ' - HDD - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-hu-outline add-input-disc"
                                                type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">RAMs</label>
                                <div id="material-container-1">
                                    <div class="form-group input-group mb-3">
                                        <select id="rams1-1" name="rams1[]" class="form-control"
                                            style="border-top-left-radius:5px;border-bottom-left-radius:5px;max-width:165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona una RAM</option>
                                            @foreach ($rams as $ram)
                                                <option value="{{ $ram->id }}"
                                                    data-stock="{{ $ram->stock }}">
                                                    {{ $ram->nombre . ' - ' . ($ram->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-hu-outline add-input-ram" type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0 px-0 pb-0">
                            <button type="submit" class="btn btn-hu">Agregar PC</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Editar PC --}}
    <div class="modal fade" id="editPcModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Modificar PC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit_pc') }}" method="POST" id="editPcForm"
                        style="display:flex;flex-direction:column;gap:20px;">
                        @method('PATCH')
                        @csrf

                        <div class="form-check">
                            <input type="hidden" name="editId" id="editId">
                            <input class="form-check-input" type="checkbox" id="editEn-uso" name="en-uso">
                            <label class="form-check-label" style="font-size:.85rem;" for="editEn-uso">En uso</label>
                        </div>

                        <div class="row g-3">
                            <div class="col-auto">
                                <label for="editIdentificador" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Nº Inventario</label>
                                <input type="text"
                                    class="form-control @error('editIdentificador') is-invalid @enderror"
                                    id="editIdentificador" name="editIdentificador" style="max-width:165px;"
                                    required>
                            </div>
                            <div class="col-auto">
                                <label for="editNombre" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Nombre</label>
                                <input type="text" class="form-control @error('editNombre') is-invalid @enderror"
                                    id="editNombre" name="editNombre" style="max-width:165px;" required>
                            </div>
                            <div class="col-auto">
                                <label for="editIp" class="form-label fw-semibold"
                                    style="font-size:.85rem;">IPv4</label>
                                <input type="text" class="form-control @error('editIp') is-invalid @enderror"
                                    id="editIp" name="editIp" style="max-width:165px;" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-auto" id="edit-content-container">
                                <div id="edit-area-select">
                                    <label for="editArea" class="form-label fw-semibold"
                                        style="font-size:.85rem;">Área</label>
                                    <select class="form-control @error('editArea') is-invalid @enderror"
                                        id="editArea" name="editArea" style="min-width:165px;max-width:165px;"
                                        required>
                                        <option value="" disabled selected>Selecciona un área</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2" id="editNroConsul_div" style="display:none;">
                                        <label for="editNroConsul" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Nº consultorio</label>
                                        <input type="text"
                                            class="form-control @error('editNroConsul') is-invalid @enderror"
                                            id="editNroConsul" name="editNroConsul" style="max-width:165px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="editDeposito" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Depósito</label>
                                <select class="form-control @error('editDeposito') is-invalid @enderror"
                                    id="editDeposito" name="editDeposito" style="min-width:165px;max-width:165px;"
                                    required>
                                    <option value="" disabled selected>Selecciona un depósito</option>
                                    @foreach ($depositos as $deposito)
                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:row;gap:20px;flex-wrap:wrap;">
                            <div style="display:flex;flex-direction:column;gap:20px;">
                                <div class="row g-3">
                                    <div class="col-auto">
                                        <label for="editMotherboard" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Placa madre</label>
                                        <select class="form-control @error('editMotherboard') is-invalid @enderror"
                                            id="editMotherboard" name="editMotherboard" style="max-width:165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona una placa madre
                                            </option>
                                            @foreach ($motherboards as $motherboard)
                                                <option value="{{ $motherboard->id }}">
                                                    {{ $motherboard->nombre . ' - ' . ($motherboard->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="editProcesador" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Procesador</label>
                                        <select class="form-control @error('editProcesador') is-invalid @enderror"
                                            id="editProcesador" name="editProcesador" style="max-width:165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona un procesador</option>
                                            @foreach ($procesadores as $procesador)
                                                <option value="{{ $procesador->id }}">
                                                    {{ $procesador->nombre . ' - ' . ($procesador->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-auto">
                                        <label for="editPlacavid" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Placa de video</label>
                                        <select class="form-control @error('editPlacavid') is-invalid @enderror"
                                            id="editPlacavid" name="editPlacavid" style="max-width:165px;">
                                            <option value="" disabled selected>Selecciona una placa de video
                                            </option>
                                            @foreach ($placasvid as $placavid)
                                                <option value="{{ $placavid->id }}">
                                                    {{ $placavid->nombre . ' - ' . ($placavid->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="editFuente" class="form-label fw-semibold"
                                            style="font-size:.85rem;">Fuente</label>
                                        <select class="form-control @error('editFuente') is-invalid @enderror"
                                            id="editFuente" name="editFuente" style="max-width:165px;" required>
                                            <option value="" disabled selected>Selecciona una fuente</option>
                                            @foreach ($fuentes as $fuente)
                                                <option value="{{ $fuente->id }}">
                                                    {{ $fuente->nombre . ' - ' . ($fuente->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Discos</label>
                                <div id="input-container-2">
                                    <div class="form-group input-group mb-3 select">
                                        <select id="discos2-1" name="discos2[]" class="form-control"
                                            style="border-top-left-radius:5px;border-bottom-left-radius:5px;max-width:165px;">
                                            <option value="" disabled selected>Selecciona un disco</option>
                                            @foreach ($discos as $disco)
                                                <option value="{{ $disco->id }}"
                                                    data-stock="{{ $disco->stock }}">
                                                    @if ($disco->tipo->nombre == 'SDD')
                                                        {{ $disco->nombre . ' - SDD - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                    @endif
                                                    @if ($disco->tipo->nombre == 'HDD')
                                                        {{ $disco->nombre . ' - HDD - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-hu-outline add-input-disc"
                                                type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">RAMs</label>
                                <div id="material-container-2">
                                    <div class="form-group input-group mb-3 select">
                                        <select id="rams2-1" name="rams2[]" class="form-control"
                                            style="border-top-left-radius:5px;border-bottom-left-radius:5px;max-width:165px;">
                                            <option value="" disabled selected>Selecciona una RAM</option>
                                            @foreach ($rams as $ram)
                                                <option value="{{ $ram->id }}"
                                                    data-stock="{{ $ram->stock }}">
                                                    {{ $ram->nombre . ' - ' . ($ram->deposito->nombre ?? 'no asignado') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-hu-outline add-input-ram" type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="en-uso-mantenimineto">
                            <label class="form-check-label" style="font-size:.85rem;" for="en-uso-mantenimineto">Se
                                realizó mantenimiento</label>
                        </div>
                        <div id="div-detalle-mant">
                            <div class="mb-3" style="display:none;">
                                <label for="editDetalle" class="form-label fw-semibold"
                                    style="font-size:.85rem;">Detalle</label>
                                <input type="text" class="form-control @error('editDetalle') is-invalid @enderror"
                                    id="editDetalle" name="editDetalle">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editMotivo" class="form-label fw-semibold"
                                style="font-size:.85rem;">Motivo</label>
                            <input type="text" class="form-control @error('editMotivo') is-invalid @enderror"
                                id="editMotivo" name="editMotivo" required>
                        </div>

                        <div class="modal-footer border-0 px-0 pb-0">
                            <button type="submit" class="btn btn-hu">Guardar cambios</button>
                        </div>
                    </form>
                </div>
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
    </div>

    {{-- ── Contenido principal ── --}}
    <div class="px-4 px-md-5 py-4" style="max-width:1400px;margin:0 auto;">
        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">

            {{-- Barra superior --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <div class="d-flex gap-2">
                    @if (in_array($rolActual, ['Administrador', 'Super administrador', 'Tecnico'], true))
                        <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addModal">
                            <span class="material-symbols-outlined"
                                style="font-size:15px;vertical-align:middle;">add</span>
                            Armar PC
                        </button>
                        <button type="button" class="btn btn-hu-outline-dorado btn-sm" data-bs-toggle="modal"
                            data-bs-target="#infoAddPc" style="padding:2px 8px;font-size:.78rem;">
                            <span class="material-symbols-outlined"
                                style="font-size:15px;vertical-align:middle;">info</span>
                        </button>
                    @endif
                </div>
                <div>
                    <input class="form-control form-control-sm" id="search_pc" type="text"
                        placeholder="Buscar PC..." style="min-width:200px;">
                </div>
            </div>

            {{-- Grid de cards --}}
            <div id="div-pc" class="d-flex flex-wrap gap-3 justify-content-start">
                @foreach ($pcs as $pc)
                    <div class="pc-card" data-nombre="{{ strtolower($pc->nombre) }}"
                        data-id="{{ strtolower($pc->identificador) }}"
                        style="width:200px;background:#fff;border:1px solid rgba(0,55,100,.12);border-radius:12px;padding:1rem;display:flex;flex-direction:column;gap:.4rem;transition:box-shadow .2s,transform .2s;cursor:pointer;"
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
                                    data-mother="{{ $pc->componentes->firstWhere('tipo_id', 5) }}"
                                    data-proce="{{ $pc->componentes->firstWhere('tipo_id', 4) }}"
                                    data-fuente="{{ $pc->componentes->firstWhere('tipo_id', 2) }}"
                                    data-placavid="{{ $pc->componentes->firstWhere('tipo_id', 7) }}"
                                    data-discosids="{{ $pc->componentes->whereIn('tipo_id', [6, 3])->pluck('id')->implode(', ') }}"
                                    data-discosobj='@json($pc->componentes->whereIn('tipo_id', [6, 3]))'
                                    data-ramsids="{{ $pc->componentes->where('tipo_id', 1)->pluck('id')->implode(', ') }}"
                                    data-ramsobj="{{ $pc->componentes->where('tipo_id', 1) }}">
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
                                <button type="button" class="btn btn-danger btn-sm flex-fill" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-id="{{ $pc->id }}">
                                    <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
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
                    var DiscosObj = button.data('discosobj');
                    var DiscosArray = Object.values(DiscosObj);
                    var cant_discos = discosIds.length;

                    var ramsIds = button.data('ramsids') ? button.data('ramsids').toString().split(', ') : [];
                    var RamsObj = button.data('ramsobj');
                    var RamsArray = Object.values(RamsObj);
                    var cant_rams = ramsIds.length;

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
                        addInputDisc(modal.find('.add-input-disc'));
                    }
                    for (var i = 0; i < cant_rams - 1; i++) {
                        addInputRam(modal.find('.add-input-ram'));
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

                // ── En uso (add modal) ──────────────────────────────────────────────
                const checkbox = document.getElementById('en-uso');
                if (checkbox) {
                    const areaSelect = document.getElementById('area-select');
                    const depositoSelect = document.getElementById('deposito-select');
                    checkbox.addEventListener('change', function() {
                        depositoSelect.querySelector('select').selectedIndex = 0;
                        areaSelect.querySelector('select').selectedIndex = 0;
                        if (checkbox.checked) {
                            areaSelect.querySelector('select').disabled = false;
                            depositoSelect.querySelector('select').disabled = true;
                        } else {
                            areaSelect.querySelector('select').disabled = true;
                            depositoSelect.querySelector('select').disabled = false;
                        }
                    });
                    if (checkbox.checked) {
                        areaSelect.querySelector('select').disabled = false;
                        depositoSelect.querySelector('select').disabled = true;
                    } else {
                        areaSelect.querySelector('select').disabled = true;
                        depositoSelect.querySelector('select').disabled = false;
                    }
                }

                // ── Componentes no identificables ───────────────────────────────────
                $('#componentes-no-identificables').on('change', function() {
                    var noIdent = $(this).is(':checked');
                    var $div = $('#div-componentes-add');
                    if (noIdent) {
                        $div.hide();
                        $div.find('select').prop('required', false).val('');
                    } else {
                        $div.show();
                        $('#addMotherboard, #addProcesador, #addFuente').prop('required', true);
                        $div.find('select[name="discos1[]"], select[name="rams1[]"]').prop('required', true);
                    }
                });

                $('#addModal').on('hidden.bs.modal', function() {
                    $('#componentes-no-identificables').prop('checked', false);
                    $('#div-componentes-add').show();
                    $('#addMotherboard, #addProcesador, #addFuente').prop('required', true);
                    $('select[name="discos1[]"], select[name="rams1[]"]').prop('required', true);
                });

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
                $(document).on('click', '.remove-input-disc', function() {
                    $(this).closest('.input-group').remove();
                    updateOptionsDisc();
                });
                $(document).on('click', '.add-input-disc', function() {
                    setTimeout(updateOptionsDisc, 0);
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
                $(document).on('click', '.remove-input-ram', function() {
                    $(this).closest('.input-group').remove();
                    updateOptionsRam();
                });
                $(document).on('click', '.add-input-ram', function() {
                    setTimeout(updateOptionsRam, 0);
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
                $(document).on('click', '.remove-input-disc-modal2', function() {
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
                $(document).on('click', '.remove-input-ram-modal2', function() {
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
                const checkboxMant = document.getElementById('en-uso-mantenimineto');
                const detalleInput = document.getElementById('div-detalle-mant');
                if (checkboxMant) {
                    checkboxMant.addEventListener('change', function() {
                        const div = detalleInput.querySelector('div');
                        const input = div.querySelector('input');
                        if (checkboxMant.checked) {
                            div.style.display = 'block';
                            input.setAttribute('required', 'required');
                        } else {
                            div.style.display = 'none';
                            input.removeAttribute('required');
                        }
                    });
                }

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
                        emptyTable: 'No hay datos disponibles',
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
        </script>
    @endpush

</x-app-layout>
