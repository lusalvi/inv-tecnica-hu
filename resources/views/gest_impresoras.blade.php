<?php use App\Models\ComponenteModel; ?>

<x-app-layout>
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

    {{-- Modal cargar impresora --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Cargar impresora</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store_impresoras') }}" method="POST">
                        @csrf

                        <div class="p-3 rounded-2 mb-4 d-flex align-items-center gap-2"
                            style="background:#EFF4FB;border:1px solid rgba(0,55,100,.15);">
                            <input class="form-check-input m-0" type="checkbox" id="add-en-uso" style="cursor:pointer;">
                            <label class="form-check-label fw-semibold mb-0" for="add-en-uso"
                                style="font-size:.88rem;cursor:pointer;">
                                En uso (asignada a un área)
                            </label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Nº Inventario</label>
                                <input type="text"
                                    class="form-control @error('addIdentificador') is-invalid @enderror"
                                    id="addIdentificador" name="addIdentificador" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Nombre</label>
                                <input type="text" class="form-control @error('addNombre') is-invalid @enderror"
                                    id="addNombre" name="addNombre" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Marca y Modelo</label>
                                <input type="text" class="form-control @error('addMarca') is-invalid @enderror"
                                    id="addMarca" name="addMarca" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">IP</label>
                                <input type="text" class="form-control @error('addIp') is-invalid @enderror"
                                    id="addIp" name="addIp" placeholder="192.168.x.x" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Área</label>
                                <select class="form-control @error('addArea') is-invalid @enderror" id="addArea"
                                    name="addArea" disabled>
                                    <option value="" disabled selected>Seleccioná un área</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                    @endforeach
                                </select>
                                <div id="addNroConsul_div" class="mt-2" style="display:none;">
                                    <label class="form-label fw-semibold" style="font-size:.85rem;">Nº
                                        consultorio</label>
                                    <input type="text"
                                        class="form-control @error('addNroConsul') is-invalid @enderror"
                                        id="addNroConsul" name="addNroConsul" placeholder="Nº">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Depósito</label>
                                <select class="form-control @error('addDeposito') is-invalid @enderror"
                                    id="addDeposito" name="addDeposito">
                                    <option value="" disabled selected>Seleccioná un depósito</option>
                                    @foreach ($depositos as $deposito)
                                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Tóner</label>
                            <select class="form-control @error('addToner') is-invalid @enderror" id="addToner"
                                name="addToner" required>
                                <option value="" disabled selected>Seleccioná un tóner</option>
                                @foreach ($toners as $toner)
                                    <option value="{{ $toner->id }}">
                                        {{ $toner->nombre . ' — ' . ($toner->deposito->nombre ?? 'sin depósito') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-hu w-100 mt-2">Cargar impresora</button>
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
                    @if (Auth::user()->rol->nombre == 'Administrador' ||
                            Auth::user()->rol->nombre == 'Super administrador' ||
                            Auth::user()->rol->nombre == 'Tecnico')
                        <button class="btn btn-hu btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"
                            style="min-width: 180px; white-space: nowrap;">
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
                            <button class="btn btn-hu btn-sm flex-fill infoBtn" data-bs-toggle="modal"
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

                            @if (Auth::user()->rol->nombre == 'Administrador' ||
                                    Auth::user()->rol->nombre == 'Super administrador' ||
                                    Auth::user()->rol->nombre == 'Tecnico')
                                <button class="btn btn-hu btn-sm flex-fill maintenanceBtn" data-bs-toggle="modal"
                                    data-bs-target="#editImpModal" data-id="{{ $impresora->id }}"
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

                            <button class="btn btn-hu btn-sm flex-fill historyBtn" data-bs-toggle="modal"
                                data-bs-target="#historyImpModal" data-id="{{ $impresora->id }}"
                                data-nro_inv="{{ $impresora->identificador }}"
                                data-nombre="{{ $impresora->nombre }}" data-tipo="Impresora" title="Historial">
                                <span class="material-symbols-outlined" style="font-size:14px;">history</span>
                            </button>

                            @if (Auth::user()->rol->nombre == 'Super administrador')
                                <button class="btn btn-danger btn-sm flex-fill" data-bs-toggle="modal"
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
                function syncAddEnUso() {
                    const checked = $('#add-en-uso').is(':checked');
                    $('#addArea').prop('disabled', !checked);
                    $('#addDeposito').prop('disabled', checked);
                    if (!checked) {
                        $('#addArea').val('');
                        $('#addNroConsul_div').hide().find('input').removeAttr('required');
                    } else {
                        $('#addDeposito').val('');
                    }
                }
                $('#add-en-uso').on('change', syncAddEnUso);
                syncAddEnUso();

                $('#addArea').on('change', function() {
                    if ($(this).val() == 27) {
                        $('#addNroConsul_div').show().find('input').attr('required', 'required');
                    } else {
                        $('#addNroConsul_div').hide().find('input').removeAttr('required');
                    }
                });

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

                    // Toner — si está agotado o en uso, lo inyectamos como opción seleccionada
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
                                return [`<b>${h.tecnico}</b>`, `<b>${h.detalle}</b>`,
                                    `<b>${h.motivo||''}</b>`, `<b>${fecha}</b>`
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
                        emptyTable: 'No hay datos disponibles',
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
