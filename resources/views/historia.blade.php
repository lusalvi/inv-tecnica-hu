<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg leading-tight" style="color: var(--hu-azul);">
            Historia
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
                    <h5 class="modal-title fw-semibold" style="color:var(--hu-azul);">Historia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-muted" style="font-size:.9rem;">
                    Registro de todos los movimientos realizados sobre los dispositivos del inventario.
                </div>
            </div>
        </div>
    </div>

    {{-- Contenido --}}
    <div class="px-4 px-md-5 py-4" style="max-width:1400px;margin:0 auto;">
        <div class="bg-white rounded-3 p-4" style="box-shadow:0 2px 12px rgba(0,55,100,.08);">

            {{-- Barra de filtros --}}
            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                <button type="button" id="filterButton" class="btn btn-hu-outline btn-sm"
                    style="border:1px solid var(--hu-azul);">
                    <span class="material-symbols-outlined"
                        style="font-size:15px;vertical-align:middle;">filter_list</span>
                    Filtrar
                </button>
                <button type="button" id="deleteFilters"
                    class="btn btn-hu-outline btn-sm d-none align-items-center gap-1">
                    <span class="material-symbols-outlined"
                        style="font-size:16px;line-height:1;">close</span>
                    Limpiar filtros
                </button>
                <div class="input-group input-group-sm ms-auto"
                    style="max-width:220px;border:1px solid #ced4da;border-radius:.375rem;">
                    <span class="input-group-text" style="background:#fff;border:0;">
                        <span class="material-symbols-outlined"
                            style="font-size:15px;color:var(--hu-azul);">search</span>
                    </span>
                    <input type="text" id="history-search" class="form-control" placeholder="Buscar..."
                        style="border:0;box-shadow:none;">
                </div>
            </div>

            <div id="filter-div" class="d-none mb-3 p-3 rounded-3" style="background:#F4F6F9;">
                <div class="row g-3 align-items-end">
                    {{-- Filtro técnico --}}
                    <div class="col-auto">
                        <label for="filtro-tecnicos" class="form-label fw-semibold"
                            style="font-size:.82rem;">Técnico</label>
                        <select id="filtro-tecnicos" class="form-select form-select-sm" style="min-width:180px;">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro fecha --}}
                    <div class="col-auto">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Fecha</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="filter-range">
                                <label class="form-check-label" for="filter-range"
                                    style="font-size:.82rem;">Rango</label>
                            </div>
                            <div id="date-filters">
                                <input class="form-control form-control-sm" type="date" id="date">
                            </div>
                            <div id="range-filters" class="d-none gap-2">
                                <input class="form-control form-control-sm" type="date" id="start-date">
                                <input class="form-control form-control-sm" type="date" id="end-date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table id="table_historias_local" class="table table-bordered table-striped w-100">
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

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1.0/daterangepicker.css">
    @endpush

    @push('vendor-scripts')
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1.0/daterangepicker.js"></script>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Toggle rango / fecha única
                $('#filter-range').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#date-filters').addClass('d-none');
                        $('#range-filters').removeClass('d-none').addClass('d-flex');
                    } else {
                        $('#range-filters').removeClass('d-flex').addClass('d-none');
                        $('#date-filters').removeClass('d-none');
                    }
                });

                // DataTable
                var his_table = $('#table_historias_local').DataTable({
                    order: [
                        [3, 'desc']
                    ],
                    dom: 'lrtip',
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

                function normalizeDate(dateString) {
                    return moment(dateString, 'YYYY-MM-DD').startOf('day');
                }

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var filterType = $('#filter-range').is(':checked') ? 'range' : 'single';
                    var date = normalizeDate(data[3]);

                    var tecnicoFilter = $('#filtro-tecnicos').val();
                    if (tecnicoFilter && data[0] !== tecnicoFilter) return false;

                    if (filterType === 'single') {
                        var singleDate = normalizeDate($('#date').val());
                        if (!singleDate.isValid()) return true;
                        return date.isSame(singleDate, 'day');
                    } else {
                        var startDate = normalizeDate($('#start-date').val());
                        var endDate = normalizeDate($('#end-date').val());
                        if ((!startDate.isValid() && !endDate.isValid()) ||
                            (!startDate.isValid() && date.isSameOrBefore(endDate)) ||
                            (startDate.isSameOrBefore(date) && !endDate.isValid()) ||
                            (startDate.isSameOrBefore(date) && date.isSameOrBefore(endDate))) {
                            return true;
                        }
                        return false;
                    }
                });

                $('#date, #start-date, #end-date, #filter-range, #filtro-tecnicos').on('change', function() {
                    his_table.draw();
                    updateClearFiltersButton();
                });

                $('#filterButton').on('click', function() {
                    var $filterDiv = $('#filter-div');
                    var visible = !$filterDiv.hasClass('d-none');
                    $filterDiv.toggleClass('d-none', visible);
                });

                function updateClearFiltersButton() {
                    var hasActiveFilters = $('#filtro-tecnicos').val() ||
                        $('#date').val() ||
                        $('#start-date').val() ||
                        $('#end-date').val();

                    $('#deleteFilters').toggleClass('d-none', !hasActiveFilters)
                        .toggleClass('d-inline-flex', !!hasActiveFilters);
                }

                $('#deleteFilters').on('click', function() {
                    $('#filtro-tecnicos, #date, #start-date, #end-date').val('');
                    $('#filter-range').prop('checked', false).trigger('change');
                    his_table.draw();
                    updateClearFiltersButton();
                });

                $('#history-search').on('input', function() {
                    his_table.search(this.value).draw();
                });
            });
        </script>
    @endpush

</x-app-layout>
