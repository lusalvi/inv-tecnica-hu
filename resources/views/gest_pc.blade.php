<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">
            {{ __('PCs') }}
            <button type="button" class="small-box-footer show" id="show" data-bs-toggle="modal"
                data-bs-target="#infoModal" style="margin-left: 0.5%">
                <i class="fa-solid fa-circle-info"></i>
            </button>
        </h2>

        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">PCs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Aqui debes agregar las computadoras armadas y asignarlas a un area o deposito.
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="infoAddPc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Armar PCs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Todos los componentes usados en el armado se quitaran y agregaran al stock automaticamente.
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="infoPcModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content bg-primaty" style="border-radius: 15px">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Información de la PC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="flex" style="display: flex; flex-direction: column; gap:10px">
                            <div class="flex" style="display: flex; flex-direction: column; gap:5px">
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>Nº Inventario: </b></h2>
                                    <p id="idenInfo"></p>
                                </div>
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>Nombre: </b></h2>
                                    <p id="nombreInfo"></p>
                                </div>
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>IPv4: </b></h2>
                                    <p id="ipInfo"></p>
                                </div>
                            </div>
                            <hr>
                            <div class="flex" style="display: flex; flex-direction: column; gap:5px">
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <b>
                                        <h2 id="titleAsig"></h2>
                                    </b>
                                    <p id="infoAsig"></p>
                                </div>
                            </div>
                            <hr>
                            <div class="flex" style="display: flex; flex-direction: column; gap:5px">
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>Placa Madre: </b></h2>
                                    <p id="motherInfo"></p>
                                </div>
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>Procesador: </b></h2>
                                    <p id="proceInfo"></p>
                                </div>
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>Discos: </b></h2>
                                    <p id="discosInfo"></p>
                                </div>
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>RAMs: </b></h2>
                                    <p id="ramsInfo"></p>
                                </div>
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>Fuente: </b></h2>
                                    <p id="fuenteInfo"></p>
                                </div>
                                <div class="flex" style="display: flex; flex-direction: row; gap:5px">
                                    <h2><b>Placa de video: </b></h2>
                                    <p id="placavidInfo"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Armar PC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="display: flex;">
                        <form action="{{ route('store_pc') }}" method="POST" id="addModal"
                            style="display: flex; flex-direction: column; gap: 20px; ">
                            @csrf
                            <!-- Mostrar errores de validación generales -->
                            <div class="form-check d-flex align-items-top">
                                <input class="form-check-input" type="checkbox" id="en-uso">
                                <label class="form-check-label" for="en-uso">
                                    En uso
                                </label>
                            </div>
                            <div style="display: flex; flex-direction: row;">
                                <div class="mb-3" style="margin-right: 2.5%">
                                    <label for="addIdentificador" class="form-label">Nº Inventario:</label>
                                    <input type="text"
                                        class="form-control @error('addIdentificador') is-invalid @enderror"
                                        id="addIdentificador" name="addIdentificador" placeholder="Nº Inventario"
                                        style="border: 1px solid gray; border-radius: 5px; max-width: 165px;" required>
                                </div>
                                <div class="mb-3" style="margin-right: 2.5%">
                                    <label for="addNombre" class="form-label">Nombre:</label>
                                    <input type="text"
                                        class="form-control @error('addNombre') is-invalid @enderror" id="addNombre"
                                        name="addNombre" placeholder="Nombre"
                                        style="border: 1px solid gray; border-radius: 5px; max-width: 165px;" required>
                                </div>
                                <div class="mb-3" style="margin-right: 2.5%">
                                    <label for="addIp" class="form-label">IPv4:</label>
                                    <input type="text" class="form-control @error('addIp') is-invalid @enderror"
                                        id="addIp" name="addIp" placeholder="Direccion IPv4"
                                        style="border: 1px solid gray; border-radius: 5px; max-width: 165px;" required>
                                </div>

                            </div>
                            <div style="display: flex; flex-direction: row; gap: 20px;">
                                <div id="content-container">
                                    <div class="mb-3" id="area-select" style="flex: 1;">
                                        <label for="addArea" class="form-label">Area:</label>
                                        <select class="form-control @error('addArea') is-invalid @enderror"
                                            id="addArea" name="addArea"
                                            style="border: 1px solid gray; border-radius: 5px; min-width:165px; max-width: 165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona un área</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mb-3" style="margin-top: 2.5%; display:none"
                                            id="addNroConsul_div">
                                            <label for="addNroConsul" class="form-label">Nº:</label>
                                            <input type="text"
                                                class="form-control @error('addNroConsul') is-invalid @enderror"
                                                id="addNroConsul" name="addNroConsul" placeholder="Nº de consultorio"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3" id="deposito-select" style="flex: 1;">
                                    <label for="addDeposito" class="form-label">Deposito:</label>
                                    <select class="form-control @error('addDeposito') is-invalid @enderror"
                                        id="addDeposito" name="addDeposito"
                                        style="border: 1px solid gray; border-radius: 5px; min-width:165px; max-width: 165px;"
                                        required>
                                        <option value="" disabled selected>Selecciona un depósito</option>
                                        @foreach ($depositos as $deposito)
                                            <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 20px;">
                                <div style="display: flex; flex-direction: column; gap: 20px;">
                                    <div style="display: flex; flex-direction: row; gap: 20px;">
                                        <div class="mb-3" style="flex: 1;">
                                            <label for="addMotherboard" class="form-label">Placa madre:</label>
                                            <select class="form-control @error('addMotherboard') is-invalid @enderror"
                                                id="addMotherboard" name="addMotherboard"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;"
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
                                        <div class="mb-3" style="flex: 1;">
                                            <label for="addProcesador" class="form-label">Procesador:</label>
                                            <select class="form-control @error('addProcesador') is-invalid @enderror"
                                                id="addProcesador" name="addProcesador"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;"
                                                required>
                                                <option value="" disabled selected>Selecciona un procesador
                                                </option>
                                                @foreach ($procesadores as $procesador)
                                                    <option value="{{ $procesador->id }}">
                                                        {{ $procesador->nombre . ' - ' . ($procesador->deposito->nombre ?? 'no asignado') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div style="display: flex; flex-direction: row; gap: 20px;">
                                        <div class="mb-3" style="flex: 1;">
                                            <label for="addPlacavid" class="form-label">Placa de video:</label>
                                            <select class="form-control @error('addPlacavid') is-invalid @enderror"
                                                id="addPlacavid" name="addPlacavid"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;">
                                                <option value="" disabled selected>Selecciona una placa de video
                                                </option>
                                                @foreach ($placasvid as $placavid)
                                                    <option value="{{ $placavid->id }}">
                                                        {{ $placavid->nombre . ' - ' . ($placavid->deposito->nombre ?? 'no asignado') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3" style="flex: 1;">
                                            <label for="addFuente" class="form-label">Fuente:</label>
                                            <select class="form-control @error('addFuente') is-invalid @enderror"
                                                id="addFuente" name="addFuente"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;"
                                                required>
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
                                    <label for="discos1">Discos:</label>
                                    <div id="input-container-1">
                                        <div class="form-group input-group mb-3">
                                            <select id="discos1-1" name="discos1[]" class="form-control"
                                                style="border: 1px solid gray; border-top-left-radius: 5px; border-bottom-left-radius: 5px; max-width: 165px;"
                                                required>
                                                <option value="" disabled selected>Selecciona un disco
                                                </option>
                                                @foreach ($discos as $disco)
                                                    <option value="{{ $disco->id }}"
                                                        data-stock="{{ $disco->stock }}">
                                                        @if ($disco->tipo->nombre == 'SDD')
                                                            {{ $disco->nombre . ' - SDD' . ' - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                        @endif

                                                        @if ($disco->tipo->nombre == 'HDD')
                                                            {{ $disco->nombre . ' - HDD' . ' - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary add-input-disc"
                                                    type="button">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="material1">RAMs:</label>
                                    <div id="material-container-1">
                                        <div class="form-group input-group mb-3">
                                            <select id="rams1-1" name="rams1[]" class="form-control"
                                                style="border: 1px solid gray; border-top-left-radius: 5px; border-bottom-left-radius: 5px; max-width: 165px;"
                                                required>
                                                <option value="" disabled selected>Selecciona una RAM
                                                </option>
                                                @foreach ($rams as $ram)
                                                    <option value="{{ $ram->id }}"
                                                        data-stock="{{ $ram->stock }}">
                                                        {{ $ram->nombre . ' - ' . ($ram->deposito->nombre ?? 'no asignado') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary add-input-ram"
                                                    type="button">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editPcModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar PC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="display: flex;">
                        <form action="{{ route('edit_pc') }}" method="POST" id="editPcModal"
                            style="display: flex; flex-direction: column; gap: 20px; ">
                            @method('PATCH')
                            @csrf
                            <!-- Mostrar errores de validación generales -->
                            <div class="form-check d-flex align-items-top">
                                <input type="hidden" name="editId" id="editId">
                                <input class="form-check-input" type="checkbox" id="editEn-uso" name="en-uso">
                                <label class="form-check-label" for="editEn-uso">
                                    En uso
                                </label>
                            </div>
                            <div style="display: flex; flex-direction: row;">
                                <div class="mb-3" style="margin-right: 2.5%">
                                    <label for="editIdentificador" class="form-label">Nº Inventario:</label>
                                    <input type="text"
                                        class="form-control @error('editIdentificador') is-invalid @enderror"
                                        id="editIdentificador" name="editIdentificador" placeholder="Nº Inventario"
                                        style="border: 1px solid gray; border-radius: 5px; max-width: 165px;" required>
                                </div>
                                <div class="mb-3" style="margin-right: 2.5%">
                                    <label for="editNombre" class="form-label">Nombre:</label>
                                    <input type="text"
                                        class="form-control @error('editNombre') is-invalid @enderror"
                                        id="editNombre" name="editNombre" placeholder="Nombre"
                                        style="border: 1px solid gray; border-radius: 5px; max-width: 165px;" required>
                                </div>
                                <div class="mb-3" style="margin-right: 2.5%">
                                    <label for="editIp" class="form-label">IPv4:</label>
                                    <input type="text" class="form-control @error('editIp') is-invalid @enderror"
                                        id="editIp" name="editIp" placeholder="Direccion IPv4"
                                        style="border: 1px solid gray; border-radius: 5px; max-width: 165px;" required>
                                </div>

                            </div>
                            <div style="display: flex; flex-direction: row; gap: 20px;">
                                <div id="content-container">
                                    <div class="mb-3" id="area-select" style="flex: 1;">
                                        <label for="editArea" class="form-label">Area:</label>
                                        <select class="form-control @error('editArea') is-invalid @enderror"
                                            id="editArea" name="editArea"
                                            style="border: 1px solid gray; border-radius: 5px; min-width:165px; max-width: 165px;"
                                            required>
                                            <option value="" disabled selected>Selecciona un área</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mb-3" style="margin-top: 2.5%; display:none"
                                            id="editNroConsul_div">
                                            <label for="editNroConsul" class="form-label">Nº:</label>
                                            <input type="text"
                                                class="form-control @error('editNroConsul') is-invalid @enderror"
                                                id="editNroConsul" name="editNroConsul"
                                                placeholder="Nº de consultorio"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3" id="deposito-select" style="flex: 1;">
                                    <label for="editDeposito" class="form-label">Deposito:</label>
                                    <select class="form-control @error('editDeposito') is-invalid @enderror"
                                        id="editDeposito" name="editDeposito"
                                        style="border: 1px solid gray; border-radius: 5px; min-width:165px; max-width: 165px;"
                                        required>
                                        <option value="" disabled selected>Selecciona un depósito</option>
                                        @foreach ($depositos as $deposito)
                                            <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="display: flex; flex-direction: row; gap: 20px;"> <!-- ROW PRINCIPAL -->
                                <div style="display: flex; flex-direction: column; gap: 20px;">
                                    <!-- COLUMN FLEXS ROWS -->
                                    <div style="display: flex; flex-direction: row; gap: 20px;">

                                        <div class="mb-3" style="flex: 1;">
                                            <label for="editMotherboard" class="form-label">Placa madre:</label>
                                            <select
                                                class="form-control @error('editMotherboard') is-invalid @enderror"
                                                id="editMotherboard" name="editMotherboard"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;"
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
                                        <div class="mb-3" style="flex: 1;">
                                            <label for="editProcesador" class="form-label">Procesador:</label>
                                            <select class="form-control @error('editProcesador') is-invalid @enderror"
                                                id="editProcesador" name="editProcesador"
                                                style="border: 1px solid gray; border-radius: 5px; max-width: 165px;"
                                                required>
                                                <option value="" disabled selected>Selecciona un procesador
                                                </option>
                                                @foreach ($procesadores as $procesador)
                                                    <option value="{{ $procesador->id }}">
                                                        {{ $procesador->nombre . ' - ' . ($procesador->deposito->nombre ?? 'no asignado') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div style="display: flex; flex-direction: row; gap: 20px;">
                                        <div style="display: flex; flex-direction: row; gap: 20px;">
                                            <div class="mb-3" style="flex: 1;">
                                                <label for="editPlacavid" class="form-label">Placa de video:</label>
                                                <select
                                                    class="form-control @error('editPlacavid') is-invalid @enderror"
                                                    id="editPlacavid" name="editPlacavid"
                                                    style="border: 1px solid gray; border-radius: 5px; max-width: 165px;">
                                                    <option value="" disabled selected>Selecciona una placa de
                                                        video</option>
                                                    @foreach ($placasvid as $placavid)
                                                        <option value="{{ $placavid->id }}">
                                                            {{ $placavid->nombre . ' - ' . ($placavid->deposito->nombre ?? 'no asignado') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3" style="flex: 1;">
                                                <label for="editFuente" class="form-label">Fuente:</label>
                                                <select class="form-control @error('editFuente') is-invalid @enderror"
                                                    id="editFuente" name="editFuente"
                                                    style="border: 1px solid gray; border-radius: 5px; max-width: 165px;"
                                                    required>
                                                    <option value="" disabled selected>Selecciona una fuente
                                                    </option>
                                                    @foreach ($fuentes as $fuente)
                                                        <option value="{{ $fuente->id }}">
                                                            {{ $fuente->nombre . ' - ' . ($fuente->deposito->nombre ?? 'no asignado') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; flex-direction: row; gap: 20px;">
                                    <div class="form-group">
                                        <label for="discos2">Discos:</label>
                                        <div id="input-container-2">
                                            <div class="form-group input-group mb-3 select">
                                                <select id="discos2-1" name="discos2[]" class="form-control"
                                                    style="border: 1px solid gray; border-top-left-radius: 5px; border-bottom-left-radius: 5px; max-width: 165px;">
                                                    <option value="" disabled selected>Selecciona un disco
                                                    </option>
                                                    @foreach ($discos as $disco)
                                                        <option value="{{ $disco->id }}"
                                                            data-stock="{{ $disco->stock }}">
                                                            @if ($disco->tipo->nombre == 'SDD')
                                                                {{ $disco->nombre . ' - SDD' . ' - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                            @endif

                                                            @if ($disco->tipo->nombre == 'HDD')
                                                                {{ $disco->nombre . ' - HDD' . ' - ' . ($disco->deposito->nombre ?? 'no asignado') }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary add-input-disc"
                                                        type="button">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="material2">RAMs:</label>
                                        <div id="material-container-2">
                                            <div class="form-group input-group mb-3 select">
                                                <select id="rams2-1" name="rams2[]" class="form-control"
                                                    style="border: 1px solid gray; border-top-left-radius: 5px; border-bottom-left-radius: 5px; max-width: 165px;">
                                                    <option value="" disabled selected>Selecciona una RAM
                                                    </option>
                                                    @foreach ($rams as $ram)
                                                        <option value="{{ $ram->id }}"
                                                            data-stock="{{ $ram->stock }}">
                                                            {{ $ram->nombre . ' - ' . ($ram->deposito->nombre ?? 'no asignado') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary add-input-ram"
                                                        type="button">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="form-check d-flex align-items-top">
                                <input class="form-check-input" type="checkbox" id="en-uso-mantenimineto">
                                <label class="form-check-label" for="en-uso-mantenimineto">
                                    Se realizo manteniminto
                                </label>
                            </div>
                            <div id="div-detalle-mant">
                                <div class="mb-3" style="flex: 1; display:none">
                                    <label for="editDetalle" class="form-label">Detalle:</label>
                                    <input type="text"
                                        class="form-control @error('editDetalle') is-invalid @enderror"
                                        id="editDetalle" name="editDetalle"
                                        style="border: 1px solid gray; border-radius: 5px;">
                                </div>
                            </div>
                            <div class="mb-3" style="flex: 1;">
                                <label for="editMotivo" class="form-label">Motivo:</label>
                                <input type="text" class="form-control @error('editMotivo') is-invalid @enderror"
                                    id="editMotivo" name="editMotivo"
                                    style="border: 1px solid gray; border-radius: 5px;" required>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark">Modificar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Seguro?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('delete_pc') }}" method="POST" id="addModal">
                            @csrf
                            <!-- Mostrar errores de validación generales -->

                            <div class="mb-3">
                                <input type="hidden" id="deleteId" name="deleteId">
                                <label for="removeMotivo" class="form-label">Motivo:</label>
                                <input type="text"
                                    class="form-control @error('removeMotivo') is-invalid @enderror"
                                    id="removeMotivo" name="removeMotivo"
                                    style="border: 1px solid gray; border-radius:5px" required>
                            </div>

                            <p
                                style="color: #d9534f; background-color: #f9e2e2; border: 1px solid #d43f3a; padding: 10px; border-radius: 5px;">
                                Todos los componentes usados en esta PC se devolveran al stock en el estado DISPONIBLE.
                            </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal"
                            aria-label="Close">No</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="historyPcModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Historia de la PC:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="container mt-4">
                        <table id="table_historias_pc" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tecnico</th>
                                    <th>Detalle</th>
                                    <th>Motivo</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="table_historias_tbody">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @error('addNombre')
                    <div class="alert-danger" style="text-align: center">
                        {{ $message }}
                    </div>
                @enderror
                @if (session('success'))
                    <div class="alert-success">
                        <p style="padding: 0.3%; text-align: center">{{ session('success') }}</p>
                    </div>
                @endif
                <div class="d-flex justify-content-left p-3 bg-light rounded shadow-sm">




                    <!--
                    <div class="small-box bg-danger" style="margin: 5px;">
                        <div class="inner">
                            <h3 class="text-lg">Eliminar</h3>

                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer"><i class="fas fa-trash-alt"></i></a>
                    </div>
                -->
                    <div class="container mt-4">
                        <div style="display: flex; justify-content: space-between;">
                            @if (Auth::user()->rol->nombre == 'Administrador' ||
                                    Auth::user()->rol->nombre == 'Super administrador' ||
                                    Auth::user()->rol->nombre == 'Tecnico')
                                <div>
                                    <button id="addButton" class="btn btn-dark mr-2" data-bs-toggle="modal"
                                        data-bs-target="#addModal">
                                        <i class="fas fa-plus"></i> Armar PC
                                    </button>
                                    <button type="button" class="small-box-footer show" id="show"
                                        data-bs-toggle="modal" data-bs-target="#infoAddPc">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                </div>
                            @endif
                            <div>
                                <input class="search_input" id="search_pc" type="text" placeholder="Buscar">
                            </div>
                        </div>
                        <div class="flex" id="div-pc"
                            style="margin-top:5%;margin-bottom:5%; flex-wrap:wrap; gap: 9px; justify-content:center">

                            @foreach ($pcs as $pc)
                                <div class="card pc-card" data-nombre="{{ strtolower($pc->nombre) }}"
                                    data-id="{{ strtolower($pc->identificador) }}"
                                    style="margin-top: -1.5%;max-width: 16%; display: flex; flex-direction: column; justify-content: space-between; height: auto;">
                                    <!-- Ajusta la altura según tus necesidades -->
                                    <span class="icon">
                                        <img src="https://simpleicon.com/wp-content/uploads/pc.png" alt="PC Icon"
                                            style="filter:invert(100%)">
                                    </span>
                                    <div style="display: flex; align-items:center;">
                                        <h4>{{ $pc->nombre }}</h4>
                                        @if ($pc->area_id != null)
                                            <span class="icon">
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQodw79SG-RYSnRDBNISWEFfF2qZJ9V80TELg&s"
                                                    alt="Status Icon"
                                                    style="width:10px; border-radius: 50%; align-items: center; margin-top: 15%">
                                            </span>
                                        @endif
                                        @if ($pc->area_id == null)
                                            <span class="icon">
                                                <img src="https://freesvg.org/img/1286146771.png" alt="Status Icon"
                                                    style="width:10px; border-radius: 50%; align-items: center; margin-top: 15%">
                                            </span>
                                        @endif
                                    </div>
                                    <p>{{ 'N° inv: ' . $pc->identificador }}</p>
                                    <p>{{ 'IPv4: ' . $pc->ip }}</p>
                                    @if ($pc->area_id != null || $pc->deposito_id != null)
                                        @if ($pc->area_id != null)
                                            <p>{{ 'Area: ' . ($pc->area->nombre ?? 'no asignado') }}</p>
                                        @endif
                                        @if ($pc->area_id == null)
                                            <p>{{ 'Deposito: ' . ($pc->deposito->nombre ?? 'no asignado') }}</p>
                                        @endif
                                    @endif

                                    <div style="margin-top: auto;">
                                        <div class="flex" style="gap: 1px; justify-content: center;">
                                            <button class="btn btn-dark icon infoBtn" data-bs-toggle="modal"
                                                data-bs-target="#infoPcModal" data-id="{{ $pc->id }}"
                                                data-identificador="{{ $pc->identificador }}"
                                                data-nombre="{{ $pc->nombre }}" data-ip="{{ $pc->ip }}"
                                                data-area="{{ $pc->area->nombre ?? 'Área no asignada' }}"
                                                data-deposito="{{ $pc->deposito->nombre ?? 'Depósito no asignado' }}"
                                                data-enuso="{{ $pc->area && $pc->area->nombre ? 'true' : 'false' }}"
                                                data-mother="{{ $pc->componentes->firstWhere('tipo_id', 5)->nombre ?? '' }}"
                                                data-proce="{{ $pc->componentes->firstWhere('tipo_id', 4)->nombre ?? '' }}"
                                                data-fuente="{{ $pc->componentes->firstWhere('tipo_id', 2)->nombre ?? '' }}"
                                                data-placavid="{{ $pc->componentes->firstWhere('tipo_id', 7)->nombre ?? 'Sin placa de video' }}"
                                                data-discos="{{ $pc->componentes->whereIn('tipo_id', [6, 3])->pluck('nombre')->implode(', ') }}"
                                                data-rams="{{ $pc->componentes->where('tipo_id', 1)->pluck('nombre')->implode(', ') }}">
                                                <i class="fas fa-circle-info"></i>
                                            </button>
                                            @if (Auth::user()->rol->nombre == 'Administrador' ||
                                                    Auth::user()->rol->nombre == 'Super administrador' ||
                                                    Auth::user()->rol->nombre == 'Tecnico')
                                                <button class="btn btn-dark icon maintenanceBtn"
                                                    data-bs-toggle="modal" data-bs-target="#editPcModal"
                                                    data-id="{{ $pc->id }}"
                                                    data-identificador="{{ $pc->identificador }}"
                                                    data-nombre="{{ $pc->nombre }}" data-ip="{{ $pc->ip }}"
                                                    data-area="{{ $pc->area_id }}"
                                                    data-deposito="{{ $pc->deposito_id }}"
                                                    data-enuso="{{ $pc->area_id ? 'true' : 'false' }}"
                                                    data-mother="{{ $pc->componentes->firstWhere('tipo_id', 5) }}"
                                                    data-proce="{{ $pc->componentes->firstWhere('tipo_id', 4) }}"
                                                    data-fuente="{{ $pc->componentes->firstWhere('tipo_id', 2) }}"
                                                    data-placavid="{{ $pc->componentes->firstWhere('tipo_id', 7) }}"
                                                    data-discosids="{{ $pc->componentes->whereIn('tipo_id', [6, 3])->pluck('id')->implode(', ') }}"
                                                    data-discosobj='@json($pc->componentes->whereIn('tipo_id', [6, 3]))'
                                                    data-ramsids="{{ $pc->componentes->where('tipo_id', 1)->pluck('id')->implode(', ') }}"
                                                    data-ramsobj="{{ $pc->componentes->where('tipo_id', 1) }}">
                                                    <i class="fa-solid fa-wrench"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-dark icon historyBtn" data-bs-toggle="modal"
                                                data-bs-target="#historyPcModal" data-id="{{ $pc->id }}"
                                                data-nro_inv="{{ $pc->identificador }}"
                                                data-nombre="{{ $pc->nombre }}" data-tipo="{{ 'PC' }}">
                                                <i class="fa-solid fa-book"></i>
                                            </button>
                                            @if (Auth::user()->rol->nombre == 'Super administrador')
                                                <button class="btn btn-dark icon" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal" data-id="{{ $pc->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@push('scripts')
<script>
    $(document).ready(function() {

        $('#editPcModal').on('show.bs.modal', function(event) {
            var modal = $(this);
            var container = modal.find('#input-container-2');
            var inputs = container.find('.select');
            if (inputs.length > 1) { inputs.not(':first').remove(); }

            var materialContainer = modal.find('#material-container-2');
            var materials = materialContainer.find('.select');
            if (materials.length > 1) { materials.not(':first').remove(); }

            var button = $(event.relatedTarget);
            var Id = button.data('id');
            var Identificador = button.data('identificador');
            var Nombre = button.data('nombre');
            var Stock = button.data('stock');
            var Deposito = button.data('deposito');
            var Tipo = button.data('tipo');
            var Ip = button.data('ip');
            var Area_id = button.data('area');
            var Deposito_id = button.data('deposito');
            var enUso = button.data('enuso');
            var Motherboard = button.data('mother');
            var Procesador = button.data('proce');
            var Fuente = button.data('fuente');
            var Placavid = button.data('placavid');

            var discosIds = button.data('discosids') ? button.data('discosids').toString().split(', ') : [];
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
            modal.find('#editStock').val(Stock);
            modal.find('#editTipo').val(Tipo);
            modal.find('#editDeposito').val(Deposito);
            modal.find('#editIp').val(Ip);
            modal.find('#editArea').val(Area_id);
            modal.find('#editDeposito').val(Deposito_id);

            var motherboardToRemove = Motherboard.nombre;
            var procesadorToRemove = Procesador.nombre;
            var fuenteToRemove = Fuente.nombre;
            var placavidToRemove = Placavid.nombre;

            modal.find('#editMotherboard option').each(function() {
                if ($(this).text() === motherboardToRemove + " - Actual") { $(this).remove(); }
            });
            modal.find('#editProcesador option').each(function() {
                if ($(this).text() === procesadorToRemove + " - Actual") { $(this).remove(); }
            });
            modal.find('#editFuente option').each(function() {
                if ($(this).text() === fuenteToRemove + " - Actual") { $(this).remove(); }
            });
            modal.find('#editPlacavid option').each(function() {
                if ($(this).text() === placavidToRemove + " - Actual") { $(this).remove(); }
            });

            if (Motherboard.stock == 0 || Motherboard.estado_id == 5) {
                modal.find('#editMotherboard').append('<option value="' + Motherboard.id + '" selected>' + Motherboard.nombre + " - Actual" + '</option>');
            } else { modal.find('#editMotherboard').val(Motherboard.id); }

            if (Procesador.stock == 0 || Procesador.estado_id == 5) {
                modal.find('#editProcesador').append('<option value="' + Procesador.id + '" selected>' + Procesador.nombre + " - Actual" + '</option>');
            } else { modal.find('#editProcesador').val(Procesador.id); }

            if (Fuente.stock == 0 || Fuente.estado_id == 5) {
                modal.find('#editFuente').append('<option value="' + Fuente.id + '" selected>' + Fuente.nombre + " - Actual" + '</option>');
            } else { modal.find('#editFuente').val(Fuente.id); }

            if (Placavid.stock == 0 || Placavid.estado_id == 5) {
                modal.find('#editPlacavid').append('<option value="' + Placavid.id + '" selected>' + Placavid.nombre + " - Actual" + '</option>');
            } else { modal.find('#editPlacavid').val(Placavid.id); }

            for (var i = 0; i < cant_discos - 1; i++) { addInputDisc(modal.find('.add-input-disc')); }
            for (var i = 0; i < cant_rams - 1; i++) { addInputRam(modal.find('.add-input-ram')); }

            container.find('.input-group').each(function(index) {
                var select = $(this).find('select');
                select.find('option.deleteable-option').remove();
                if (index < cant_discos) {
                    if (DiscosArray[index].stock == 0 || DiscosArray[index].estado_id == 5) {
                        select.append('<option value="' + DiscosArray[index].id + '" class="deleteable-option" selected>' + DiscosArray[index].nombre + " - " + DiscosArray[index].tipo.nombre + '</option>');
                    } else { select.val(discosIds[index]); }
                }
            });

            materialContainer.find('.input-group').each(function(index) {
                var select = $(this).find('select');
                select.find('option.deleteable-option2').remove();
                if (index < cant_rams) {
                    if (RamsArray[index].stock == 0 || RamsArray[index].estado_id == 5) {
                        select.append('<option value="' + RamsArray[index].id + '" class="deleteable-option2" selected>' + RamsArray[index].nombre + " " + RamsArray[index].tipo.nombre + '</option>');
                    } else { select.val(ramsIds[index]); }
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
                modal.find('#editDeposito').val(null);
                modal.find('#editArea').val(null);
                $('#addNroConsul_div').css('display', 'none').attr('required', false);
                $('#editNroConsul_div').css('display', 'none').attr('required', false);
            } else {
                modal.find('#editDeposito').prop('disabled', true);
                modal.find('#editArea').prop('disabled', false);
                modal.find('#editArea').val(null);
                modal.find('#editDeposito').val(null);
            }
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            var modal = $(this);
            modal.find('#deleteId').val($(event.relatedTarget).data('id'));
        });

        $('#infoPcModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            var enUso = button.data('enuso');
            modal.find('#idenInfo').text(button.data('identificador'));
            modal.find('#ipInfo').text(button.data('ip'));
            modal.find('#nombreInfo').text(button.data('nombre'));
            if (enUso == true) {
                modal.find('#titleAsig').text("Area:");
                modal.find('#infoAsig').text(button.data('area'));
            } else {
                modal.find('#titleAsig').text("Deposito:");
                modal.find('#infoAsig').text(button.data('deposito'));
            }
            modal.find('#motherInfo').text(button.data('mother'));
            modal.find('#proceInfo').text(button.data('proce'));
            modal.find('#fuenteInfo').text(button.data('fuente'));
            modal.find('#placavidInfo').text(button.data('placavid'));
            modal.find('#discosInfo').text(button.data('discos'));
            modal.find('#ramsInfo').text(button.data('rams'));
        });

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

    });
</script>
<script>
    $(document).ready(function() {
        $('#en-uso').on('change', function() {
            var isChecked = $(this).is(':checked');

            // Ajustar habilitación/deshabilitación en función del estado del checkbox
            if (!isChecked) {
                $('#addNroConsul_div').css('display', 'none');
                $('#addNroConsul_div').attr('required', false);
                $('#editNroConsul_div').css('display', 'none');
                $('#editNroConsul_div').attr('required', false);
            }
        });

        $('#editEn-uso').on('change', function() {
            var isChecked = $(this).is(':checked');

            // Ajustar habilitación/deshabilitación en función del estado del checkbox
            if (!isChecked) {
                $('#addNroConsul_div').css('display', 'none');
                $('#addNroConsul_div').attr('required', false);
                $('#editNroConsul_div').css('display', 'none');
                $('#editNroConsul_div').attr('required', false);
            }
        });

        $('#search_pc').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();

            // Filtrar las tarjetas de PC
            $('.card').each(function() {
                var nombre = String($(this).data('nombre'));
                var id = String($(this).data('id'));

                // Mostrar la tarjeta si el nombre o la IP contiene el término de búsqueda
                if (nombre.includes(searchTerm) || id.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#addArea').on('change', function() {
            if ($(this).val() == 27) {
                $('#addNroConsul_div').css('display', 'block');
                $('#addNroConsul_div').attr('required', true);

            } else {
                $('#addNroConsul_div').css('display', 'none');
                $('#addNroConsul_div').attr('required', false);
            }
        });

        $('#editArea').on('change', function() {
            if ($(this).val() == 27) {
                $('#editNroConsul_div').css('display', 'block');
                $('#editNroConsul_div').css('required', true);
            } else {
                $('#editNroConsul_div').css('display', 'none');
                $('#editNroConsul_div').css('required', false);
            }
        });

        // Función para actualizar las opciones en todos los selects de discos
        function updateOptionsDisc() {
            let stock = {};

            // Obtener el stock de cada opción en todos los selects de discos
            $('select[name="discos1[]"] option').each(function() {
                let option = $(this);
                let value = option.val();
                let stockValue = parseInt(option.data('stock')) || 0;
                stock[value] = stockValue;
            });

            // Disminuir el stock de la opción seleccionada en cada select de discos
            $('select[name="discos1[]"]').each(function() {
                let selectedValue = $(this).val();
                if (selectedValue) {
                    stock[selectedValue] = (stock[selectedValue] || 0) - 1;
                }
            });

            // Variable para verificar si al menos un select tiene opciones
            let anyOptionsAvailable = false;

            // Actualizar las opciones en todos los selects de discos
            $('select[name="discos1[]"]').each(function() {
                let currentSelect = $(this);
                let hasOptions = false;

                let defaultOption = currentSelect.find('option[value=""]');

                // Mostrar todas las opciones inicialmente
                currentSelect.find('option').show();

                currentSelect.find('option').each(function() {
                    let option = $(this);
                    let value = option.val();
                    let stockValue = stock[value] || 0;

                    // Verificar si hay opciones con stock
                    if (value !== "" && stockValue > 0) {
                        hasOptions = true;
                        anyOptionsAvailable = true; // Al menos un select tiene opciones
                    } else if (value !== "") {
                        option.hide();
                    }
                });

                // Si no hay opciones, actualizar el mensaje
                if (!hasOptions && defaultOption.length > 0) {
                    defaultOption.text('No hay discos disponibles');
                }
            });

            // Si no hay opciones disponibles en ningún select, mostrar el mensaje en todos
            if (!anyOptionsAvailable) {
                $('select[name="discos1[]"]').each(function() {
                    let defaultOption = $(this).find('option[value=""]');
                    if (defaultOption.length > 0) {
                        defaultOption.text('No hay discos disponibles');
                    }
                });
            } else {
                // Si hay opciones disponibles, asegurarse de que el mensaje de disponibilidad se borre
                $('select[name="discos1[]"]').each(function() {
                    let defaultOption = $(this).find('option[value=""]');
                    if (defaultOption.length > 0) {
                        defaultOption.text('Seleccione un disco');
                    }
                });
            }
        }

        // Manejar el cambio en cualquier select de discos
        $(document).on('change', 'select[name="discos1[]"]', function() {
            updateOptionsDisc();
        });

        // Manejar la eliminación de un select de discos (cuando se borra un campo)
        $(document).on('click', '.remove-input-disc', function() {
            $(this).closest('.input-group').remove();
            updateOptionsDisc();
        });

        // Manejar la adición de nuevos selects de discos
        $(document).on('click', '.add-input-disc', function() {
            // Esperar a que el nuevo select se agregue al DOM
            setTimeout(updateOptionsDisc, 0);
        });

        // Inicializar las opciones al cargar la página
        updateOptionsDisc();

        // Función para actualizar las opciones en todos los selects de RAMs
        function updateOptionsRam() {
            let stock = {};

            // Obtener el stock de cada opción en todos los selects de RAMs
            $('select[name="rams1[]"] option').each(function() {
                let option = $(this);
                let value = option.val();
                let stockValue = parseInt(option.data('stock')) || 0;
                stock[value] = stockValue;
            });

            // Disminuir el stock de la opción seleccionada en cada select de RAMs
            $('select[name="rams1[]"]').each(function() {
                let selectedValue = $(this).val();
                if (selectedValue) {
                    stock[selectedValue] = (stock[selectedValue] || 0) - 1;
                }
            });

            // Variable para verificar si al menos un select tiene opciones
            let anyOptionsAvailable = false;

            // Actualizar las opciones en todos los selects de RAMs
            $('select[name="rams1[]"]').each(function() {
                let currentSelect = $(this);
                let hasOptions = false;

                let defaultOption = currentSelect.find('option[value=""]');

                // Mostrar todas las opciones inicialmente
                currentSelect.find('option').show();

                currentSelect.find('option').each(function() {
                    let option = $(this);
                    let value = option.val();
                    let stockValue = stock[value] || 0;

                    // Verificar si hay opciones con stock
                    if (value !== "" && stockValue > 0) {
                        hasOptions = true;
                        anyOptionsAvailable = true; // Al menos un select tiene opciones
                    } else if (value !== "") {
                        option.hide();
                    }
                });

                // Si no hay opciones, actualizar el mensaje
                if (!hasOptions && defaultOption.length > 0) {
                    defaultOption.text('No hay RAMs disponibles');
                }
            });

            // Si no hay opciones disponibles en ningún select, mostrar el mensaje en todos
            if (!anyOptionsAvailable) {
                $('select[name="rams1[]"]').each(function() {
                    let defaultOption = $(this).find('option[value=""]');
                    if (defaultOption.length > 0) {
                        defaultOption.text('No hay RAMs disponibles');
                    }
                });
            } else {
                // Si hay opciones disponibles, asegurarse de que el mensaje de disponibilidad se borre
                $('select[name="rams1[]"]').each(function() {
                    let defaultOption = $(this).find('option[value=""]');
                    if (defaultOption.length > 0) {
                        defaultOption.text('Seleccione una RAM');
                    }
                });
            }
        }

        // Manejar el cambio en cualquier select de RAMs
        $(document).on('change', 'select[name="rams1[]"]', function() {
            updateOptionsRam();
        });

        // Manejar la eliminación de un select de RAMs (cuando se borra un campo)
        $(document).on('click', '.remove-input-ram', function() {
            $(this).closest('.input-group').remove();
            updateOptionsRam();
        });

        // Manejar la adición de nuevos selects de RAMs
        $(document).on('click', '.add-input-ram', function() {
            // Esperar a que el nuevo select se agregue al DOM
            setTimeout(updateOptionsRam, 0);
        });

        // Inicializar las opciones al cargar la página
        updateOptionsRam();



        function updateOptionsDiscModal2() {
            let stock = {};

            // Obtener el stock de cada opción en todos los selects de discos del modal 2
            $('select[name="discos2[]"] option').each(function() {
                let option = $(this);
                let value = option.val();
                let stockValue = parseInt(option.data('stock')) || 0;
                stock[value] = stockValue;
            });

            // Disminuir el stock de la opción seleccionada en cada select de discos del modal 2
            $('select[name="discos2[]"]').each(function() {
                let selectedValue = $(this).val();
                if (selectedValue) {
                    stock[selectedValue] = (stock[selectedValue] || 0) - 1;
                }
            });

            // Actualizar las opciones en todos los selects de discos del modal 2
            $('select[name="discos2[]"]').each(function() {
                let currentSelect = $(this);
                let hasOptions = false;

                let defaultOption = currentSelect.find('option[value=""]');

                // Mostrar todas las opciones inicialmente
                currentSelect.find('option').show();

                currentSelect.find('option').each(function() {
                    let option = $(this);
                    let value = option.val();
                    let stockValue = stock[value] || 0;

                    // Verificar si hay opciones con stock
                    if (value !== "" && stockValue > 0) {
                        hasOptions = true;
                    } else if (value !== "") {
                        option.hide();
                    }
                });

                if (!hasOptions && defaultOption.length > 0) {
                    defaultOption.text('No hay discos disponibles');
                }
            });
        }

        // Manejar el cambio en cualquier select de discos del modal 2
        $(document).on('change', 'select[name="discos2[]"]', function() {
            updateOptionsDiscModal2();
        });

        // Manejar la eliminación de un select de discos del modal 2 (cuando se borra un campo)
        $(document).on('click', '.remove-input-disc-modal2', function() {
            $(this).closest('.input-group').remove();
            updateOptionsDiscModal2();
        });

        // Manejar la adición de nuevos selects de discos del modal 2
        $(document).on('click', '.add-input-disc-modal2', function() {
            // Esperar a que el nuevo select se agregue al DOM
            setTimeout(updateOptionsDiscModal2, 0);
        });


        function updateOptionsRamModal2() {
            let stock = {};

            // Obtener el stock de cada opción en todos los selects de RAMs del modal 2
            $('select[name="rams2[]"] option').each(function() {
                let option = $(this);
                let value = option.val();
                let stockValue = parseInt(option.data('stock')) || 0;
                stock[value] = stockValue;
            });

            // Disminuir el stock de la opción seleccionada en cada select de RAMs del modal 2
            $('select[name="rams2[]"]').each(function() {
                let selectedValue = $(this).val();
                if (selectedValue) {
                    stock[selectedValue] = (stock[selectedValue] || 0) - 1;
                }
            });

            // Actualizar las opciones en todos los selects de RAMs del modal 2
            $('select[name="rams2[]"]').each(function() {
                let currentSelect = $(this);
                let hasOptions = false;

                let defaultOption = currentSelect.find('option[value=""]');

                // Mostrar todas las opciones inicialmente
                currentSelect.find('option').show();

                currentSelect.find('option').each(function() {
                    let option = $(this);
                    let value = option.val();
                    let stockValue = stock[value] || 0;

                    // Ocultar las opciones sin stock
                    if (value !== "" && stockValue > 0) {
                        hasOptions = true;
                    } else if (value !== "") {
                        option.hide();
                    }
                });

                if (!hasOptions && defaultOption.length > 0) {
                    defaultOption.text('No hay RAMs disponibles');
                }
            });
        }

        // Manejar el cambio en cualquier select de RAMs del modal 2
        $(document).on('change', 'select[name="rams2[]"]', function() {
            updateOptionsRamModal2();
        });

        // Manejar la eliminación de un select de RAMs del modal 2 (cuando se borra un campo)
        $(document).on('click', '.remove-input-ram-modal2', function() {
            $(this).closest('.input-group').remove();
            updateOptionsRamModal2();
        });

        // Manejar la adición de nuevos selects de RAMs del modal 2
        $(document).on('click', '.add-input-ram-modal2', function() {
            // Esperar a que el nuevo select se agregue al DOM
            setTimeout(updateOptionsRamModal2, 0);
        });


        window.updateRams = function() {
            updateOptionsRamModal2();
        }

        window.updateDiscos = function() {
            updateOptionsDiscModal2();
        }

        var modalHandlerAttached = false;
        var nro_inv = "";
        var nombre = "";

        $('#historyPcModal').on('show.bs.modal', function(event) {
            if (modalHandlerAttached) return;
            modalHandlerAttached = true;
            var button = $(event.relatedTarget); // Botón que abrió el modal
            var componenteId = button.data('id'); // Obtener el ID del componente
            var componenteTipo = button.data('tipo'); // Obtener el ID del componente

            nro_inv = button.data('nro_inv');
            nombre = button.data('nombre');

            // Limpiar cualquier dato previo en la tabla
            var table = $('#table_historias_pc').DataTable();
            table.clear().draw();

            var historiaUrl = '{{ route('historia.get', ['tipo' => ':tipo', 'id' => ':id']) }}';
            historiaUrl = historiaUrl.replace(':tipo', componenteTipo);
            historiaUrl = historiaUrl.replace(':id', componenteId);

            // Realizar la solicitud AJAX para obtener los registros de historia
            $.ajax({
                url: historiaUrl, // Cambia la URL a la ruta de tu controlador
                method: 'GET',
                success: function(response) {
                    var data = [];

                    response.historia.forEach(function(historia) {
                        var fechaFormateada = new Date(historia.created_at)
                            .toLocaleDateString('es-ES', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                        var motivo = historia.motivo || '';

                        data.push([
                            `<b>${historia.tecnico}</b>`,
                            `<b>${historia.detalle}</b>`,
                            `<b>${motivo}</b>`,
                            `<b>${fechaFormateada}</b>`
                        ]);
                    });

                    // Añadir los datos a la tabla y refrescarla
                    table.rows.add(data).draw();
                },
                error: function() {
                    alert('Error al cargar las historias.');
                }
            });
        });

        $('#historyPcModal').on('hide.bs.modal', function() {
            modalHandlerAttached = false;
        });


        const checkbox = document.getElementById('en-uso-mantenimineto');
        const detalleInput = document.getElementById('div-detalle-mant');

        checkbox.addEventListener('change', function() {
            const div = detalleInput.querySelector('div');
            const input = div.querySelector(
                'input'); // Suponiendo que es un input el que quieres hacer requerido

            if (checkbox.checked) {
                div.style.display = "block"; // Muestra el div
                input.setAttribute('required', 'required'); // Hace el campo requerido
            } else {
                div.style.display = "none"; // Oculta el div
                input.removeAttribute('required'); // Quita el atributo requerido

            }
        });

        // Manejador para el click en el div con la clase 'card'
        $('.card').on('click', function() {
            // Dispara el click en el botón infoBtn dentro del div
            $(this).find('.infoBtn').trigger('click');
        });

        // Manejador para el click en el botón infoBtn
        $('.infoBtn').on('click', function(event) {
            // Evita que el modal se abra inmediatamente
            event.preventDefault();

            // Cargar los datos en el modal
            const modal = $('#infoPcModal');
            modal.find('#idenInfo').text($(this).data('identificador'));
            modal.find('#nombreInfo').text($(this).data('nombre'));
            modal.find('#ipInfo').text($(this).data('ip'));
            if ($(this).data('enuso')) {
                modal.find('#titleAsig').text('Area:');
                modal.find('#infoAsig').text($(this).data('area'));
            } else {
                modal.find('#titleAsig').text('Deposito:');
                modal.find('#infoAsig').text($(this).data('deposito'));
            }
            modal.find('#motherInfo').text($(this).data('mother'));
            modal.find('#proceInfo').text($(this).data('proce'));
            modal.find('#discosInfo').text($(this).data('discos'));
            modal.find('#ramsInfo').text($(this).data('rams'));
            modal.find('#fuenteInfo').text($(this).data('fuente'));
            modal.find('#placavidInfo').text($(this).data('placavid'));

            // Mostrar el modal
            modal.modal('show');
        });

        // Manejador para el click en el botón de mantenimiento
        $('.maintenanceBtn').on('click', function(event) {
            event.stopPropagation();
            // Código específico para el botón de mantenimiento
        });

        // Manejador para el click en el botón de historial
        $('.historyBtn').on('click', function(event) {
            event.stopPropagation();
            // Código específico para el botón de historial
        });



        $("#table_historias_pc").DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                className: 'btn btn-dark custom-export-btn',
                title: function() {

                    return "Historia de " + nro_inv + " - " + nombre;

                }
            }],
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "language": {
                "decimal": "",
                "emptyTable": "No hay datos disponibles en la tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": " _MENU_ ",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": activar para ordenar la columna ascendente",
                    "sortDescending": ": activar para ordenar la columna descendente"
                }
            }
        });


    });
</script>
@endpush
</x-app-layout>
