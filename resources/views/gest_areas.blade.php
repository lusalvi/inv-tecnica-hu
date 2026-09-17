<x-app-layout>
    @php
        $rolActual = data_get(Auth::user(), 'rol.nombre');
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-lg leading-tight" style="color: var(--hu-azul);">
            Áreas
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
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Áreas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    Aquí se agregan las áreas donde se encuentran los dispositivos.
                </div>
            </div>
        </div>
    </div>

    {{-- Modal agregar --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Nueva área</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store_area') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="addNombre" class="form-label fw-semibold"
                                style="font-size:.85rem;">Nombre</label>
                            <input type="text" class="form-control @error('addNombre') is-invalid @enderror"
                                id="addNombre" name="addNombre" required>
                        </div>
                        <button type="submit" class="btn btn-hu w-100">Agregar área</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal editar --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Editar área</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit_area') }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" id="editId" name="editId">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label fw-semibold"
                                style="font-size:.85rem;">Nombre</label>
                            <input type="text" class="form-control @error('editNombre') is-invalid @enderror"
                                id="editNombre" name="editNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMotivo" class="form-label fw-semibold" style="font-size:.85rem;">Motivo del
                                cambio</label>
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
            <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">¿Eliminar área?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('delete_area') }}" method="POST">
                        @csrf
                        <input type="hidden" id="deleteId" name="deleteId">
                        <div class="mb-3">
                            <label for="removeMotivo" class="form-label fw-semibold"
                                style="font-size:.85rem;">Motivo</label>
                            <input type="text" class="form-control @error('removeMotivo') is-invalid @enderror"
                                id="removeMotivo" name="removeMotivo" required>
                        </div>
                        <div class="p-2 rounded-2 mb-3"
                            style="background:#FEF2F2;border:1px solid #FECACA;font-size:.82rem;color:#991B1B;">
                            <span class="material-symbols-outlined"
                                style="font-size:15px;vertical-align:middle;">warning</span>
                            Todos los dispositivos asociados a esta área se desvincularán de la misma.
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

    {{-- Contenido --}}
    <div class="px-4 px-md-5 py-4" style="max-width:1400px;margin:0 auto;">

        @error('addNombre')
            <div class="alert-hu-error mb-3">{{ $message }}</div>
        @enderror
        @if (session('success'))
            <div class="alert-hu-success mb-3">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="fw-semibold"
                    style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--hu-azul);">
                    Listado de áreas
                </span>
                <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">add</span>
                    Nueva área
                </button>
            </div>

            @if ($areas->isEmpty())
                <div class="hu-empty-state mt-3">
                    <span class="material-symbols-outlined">location_off</span>
                    <p>No hay áreas cargadas todavía.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th style="width:120px;">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $area)
                                <tr>
                                    <td>{{ $area->nombre }}</td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-hu btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal" data-id="{{ $area->id }}"
                                                data-nombre="{{ $area->nombre }}" title="Editar">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:15px;">edit</span>
                                            </button>
                                            @if ($rolActual === 'Super administrador')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $area->id }}" title="Eliminar">
                                                    <span class="material-symbols-outlined"
                                                        style="font-size:15px;">delete</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h2 class="mt-4 mb-3 fw-semibold"
                style="font-size:1rem;color:var(--hu-azul);border-top:2px solid var(--hu-azul);padding-top:1rem;">
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
                                <td>{{ $historia->tecnico }}</td>
                                <td>{{ $historia->detalle }}</td>
                                <td>{{ $historia->motivo }}</td>
                                <td>{{ $historia->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#editModal').on('show.bs.modal', function(event) {
                    const btn = $(event.relatedTarget);
                    $(this).find('#editId').val(btn.data('id'));
                    $(this).find('#editNombre').val(btn.data('nombre'));
                });
                $('#deleteModal').on('show.bs.modal', function(event) {
                    $(this).find('#deleteId').val($(event.relatedTarget).data('id'));
                });
            });
        </script>
    @endpush

</x-app-layout>
