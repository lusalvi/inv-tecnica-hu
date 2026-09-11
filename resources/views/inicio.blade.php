<?php use App\Models\PcModel; ?>
<?php use App\Models\ImpresoraModel; ?>
<?php use App\Models\TelefonoModel; ?>
<?php use App\Models\RouterModel; ?>
<?php use App\Models\ComponenteModel; ?>

<x-app-layout>

    {{-- ============================================================
         MODAL: Cambio de contraseña pendiente
         ============================================================ --}}
    @if (Auth::user()->pass_changed == false)
        <div class="modal fade" id="infoPassModal" tabindex="-1" aria-labelledby="passModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 8px 32px rgba(0,55,100,.15);">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined" style="color: var(--hu-dorado); font-size: 22px;">lock</span>
                            <h5 class="modal-title fw-semibold" id="passModalLabel" style="color: var(--hu-azul);">
                                Cambio de contraseña pendiente
                            </h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <p class="text-muted" style="font-size: 0.9rem;">
                            Por seguridad, se recomienda cambiar la contraseña generada por el administrador.
                        </p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-hu w-100 mt-1">
                            <span class="material-symbols-outlined me-1" style="font-size:18px; vertical-align: middle;">key</span>
                            Cambiar contraseña
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================
         MODAL: Información de PC
         ============================================================ --}}
    <div class="modal fade" id="infoPcModal" tabindex="-1" aria-labelledby="infoPcModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="color: var(--hu-azul); font-size: 22px;">computer</span>
                        <h5 class="modal-title fw-semibold" id="infoPcModalLabel" style="color: var(--hu-azul);">Información de la PC</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    {{-- Datos principales --}}
                    <div class="mb-3 p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row"><span class="info-label">Nº Inventario</span><span id="pc-idenInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Nombre</span><span id="pc-nombreInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">IPv4</span><span id="pc-ipInfo" class="info-value font-monospace" style="font-size:.85rem;"></span></div>
                    </div>
                    {{-- Ubicación --}}
                    <div class="mb-3 p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row">
                            <span class="info-label" id="pc-titleAsig"></span>
                            <span id="pc-infoAsig" class="info-value"></span>
                        </div>
                    </div>
                    {{-- Componentes --}}
                    <div class="p-3 rounded-3" style="background: #F4F6F9;">
                        <p class="text-uppercase fw-semibold mb-2" style="font-size:.7rem; letter-spacing:.06em; color: var(--hu-azul);">Componentes</p>
                        <div class="info-row"><span class="info-label">Placa madre</span><span id="pc-motherInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Procesador</span><span id="pc-proceInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Discos</span><span id="pc-discosInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">RAMs</span><span id="pc-ramsInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Fuente</span><span id="pc-fuenteInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Placa de video</span><span id="pc-placavidInfo" class="info-value"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Información de Impresora
         ============================================================ --}}
    <div class="modal fade" id="infoImpModal" tabindex="-1" aria-labelledby="infoImpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="color: var(--hu-azul); font-size: 22px;">print</span>
                        <h5 class="modal-title fw-semibold" id="infoImpModalLabel" style="color: var(--hu-azul);">Información de la Impresora</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row"><span class="info-label">Nº Inventario</span><span id="imp-idenInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Nombre</span><span id="imp-nombreInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Marca y Modelo</span><span id="imp-marcaInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">IP</span><span id="imp-ipInfo" class="info-value font-monospace" style="font-size:.85rem;"></span></div>
                        <div class="info-row"><span class="info-label">Tóner</span><span id="imp-tonerInfo" class="info-value"></span></div>
                    </div>
                    <div class="p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row">
                            <span class="info-label" id="imp-titleAsig"></span>
                            <span id="imp-infoAsig" class="info-value"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Información de Teléfono
         ============================================================ --}}
    <div class="modal fade" id="infoTelModal" tabindex="-1" aria-labelledby="infoTelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="color: var(--hu-azul); font-size: 22px;">call</span>
                        <h5 class="modal-title fw-semibold" id="infoTelModalLabel" style="color: var(--hu-azul);">Información del Teléfono</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row"><span class="info-label">Nº Inventario</span><span id="tel-idenInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Nombre</span><span id="tel-nombreInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Marca y Modelo</span><span id="tel-marcaInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Número</span><span id="tel-numeroInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">IP</span><span id="tel-ipInfo" class="info-value font-monospace" style="font-size:.85rem;"></span></div>
                    </div>
                    <div class="p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row">
                            <span class="info-label" id="tel-titleAsig"></span>
                            <span id="tel-infoAsig" class="info-value"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODAL: Información de Router
         ============================================================ --}}
    <div class="modal fade" id="infoRouterModal" tabindex="-1" aria-labelledby="infoRouterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 8px 32px rgba(0,55,100,.15);">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="color: var(--hu-azul); font-size: 22px;">router</span>
                        <h5 class="modal-title fw-semibold" id="infoRouterModalLabel" style="color: var(--hu-azul);">Información del Router</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row"><span class="info-label">Nº Inventario</span><span id="rou-idenInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Nombre</span><span id="rou-nombreInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">Marca y Modelo</span><span id="rou-marcaInfo" class="info-value"></span></div>
                        <div class="info-row"><span class="info-label">IP</span><span id="rou-ipInfo" class="info-value font-monospace" style="font-size:.85rem;"></span></div>
                    </div>
                    <div class="p-3 rounded-3" style="background: #F4F6F9;">
                        <div class="info-row">
                            <span class="info-label" id="rou-titleAsig"></span>
                            <span id="rou-infoAsig" class="info-value"></span>
                        </div>
                        <div class="info-row" id="rou-detalleRow" style="display:none;">
                            <span class="info-label">Detalle ubicación</span>
                            <span id="rou-areaDetalle" class="info-value"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         CONTENIDO PRINCIPAL
         ============================================================ --}}
    <div class="px-4 px-md-5 py-4" style="max-width: 1400px; margin: 0 auto;">

        {{-- SECCIÓN 1: Cards de acceso rápido --}}
        <section class="mb-5">
            <h2 class="inicio-section-title">Gestión de dispositivos</h2>
            <div class="d-flex flex-wrap gap-3">

                <a href="{{ route('gest_pc') }}" class="inicio-nav-card">
                    <span class="material-symbols-outlined inicio-nav-icon">computer</span>
                    <span class="inicio-nav-label">PCs</span>
                    <span class="inicio-nav-sub">Administrar equipos</span>
                </a>

                <a href="{{ route('gest_impresoras') }}" class="inicio-nav-card">
                    <span class="material-symbols-outlined inicio-nav-icon">print</span>
                    <span class="inicio-nav-label">Impresoras</span>
                    <span class="inicio-nav-sub">Administrar impresoras</span>
                </a>

                <a href="{{ route('gest_telefonos') }}" class="inicio-nav-card">
                    <span class="material-symbols-outlined inicio-nav-icon">call</span>
                    <span class="inicio-nav-label">Teléfonos IP</span>
                    <span class="inicio-nav-sub">Administrar teléfonos</span>
                </a>

                <a href="{{ route('gest_routers') }}" class="inicio-nav-card">
                    <span class="material-symbols-outlined inicio-nav-icon">router</span>
                    <span class="inicio-nav-label">Routers</span>
                    <span class="inicio-nav-sub">Administrar routers</span>
                </a>

            </div>
        </section>

        {{-- SECCIÓN 2: Últimos modificados + Últimos movimientos --}}
        <div class="row g-4">

            {{-- Últimos dispositivos modificados --}}
            <div class="col-12 col-lg-7">
                <h2 class="inicio-section-title">Últimos dispositivos modificados</h2>

                @if ($lastDevicesUpdated->isEmpty())
                    <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                        <span class="material-symbols-outlined mb-2" style="font-size: 40px; opacity:.4;">devices</span>
                        <span style="font-size:.9rem;">Sin actividad reciente</span>
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($lastDevicesUpdated as $lastDeviceUpdated)

                            {{-- === PC === --}}
                            @if ($lastDeviceUpdated->tipo_dispositivo == 'PC')
                                @php $pc = PcModel::with(['area','deposito','componentes.tipo'])->find($lastDeviceUpdated->componente_id); @endphp
                                @if ($pc)
                                    <div class="inicio-device-card"
                                        data-tipo="PC"
                                        data-id="{{ $pc->id }}"
                                        data-identificador="{{ $pc->identificador }}"
                                        data-nombre="{{ $pc->nombre }}"
                                        data-ip="{{ $pc->ip }}"
                                        data-area="{{ $pc->area->nombre ?? 'Área no asignada' }}"
                                        data-deposito="{{ $pc->deposito->nombre ?? 'Depósito no asignado' }}"
                                        data-enuso="{{ $pc->area_id ? 'true' : 'false' }}"
                                        data-mother="{{ $pc->componentes->firstWhere('tipo_id', 5)->nombre ?? '—' }}"
                                        data-proce="{{ $pc->componentes->firstWhere('tipo_id', 4)->nombre ?? '—' }}"
                                        data-fuente="{{ $pc->componentes->firstWhere('tipo_id', 2)->nombre ?? '—' }}"
                                        data-placavid="{{ $pc->componentes->firstWhere('tipo_id', 7)->nombre ?? 'Sin placa de video' }}"
                                        data-discos="{{ $pc->componentes->whereIn('tipo_id', [6,3])->pluck('nombre')->implode(', ') ?: '—' }}"
                                        data-rams="{{ $pc->componentes->where('tipo_id', 1)->pluck('nombre')->implode(', ') ?: '—' }}"
                                        role="button" tabindex="0">
                                        <div class="inicio-device-icon-wrap">
                                            <span class="material-symbols-outlined inicio-device-icon">computer</span>
                                        </div>
                                        <div class="inicio-device-body">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span class="inicio-device-name">{{ $pc->nombre }}</span>
                                                @if ($pc->area_id)
                                                    <span class="inicio-badge-area" title="En área">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">meeting_room</span>
                                                        {{ Str::limit($pc->area->nombre, 14) }}
                                                    </span>
                                                @else
                                                    <span class="inicio-badge-deposito" title="En depósito">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">warehouse</span>
                                                        {{ Str::limit($pc->deposito->nombre ?? 'Sin asignar', 14) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="inicio-device-meta">Inv. {{ $pc->identificador }}</span>
                                            @if($pc->ip)
                                                <span class="inicio-device-meta font-monospace">{{ $pc->ip }}</span>
                                            @endif
                                        </div>
                                        <div class="inicio-device-footer">
                                            <span class="inicio-device-type">
                                                <span class="material-symbols-outlined" style="font-size:13px; vertical-align:middle;">info</span>
                                                Ver detalle
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- === IMPRESORA === --}}
                            @if ($lastDeviceUpdated->tipo_dispositivo == 'Impresora')
                                @php $imp = ImpresoraModel::with(['area','deposito'])->find($lastDeviceUpdated->componente_id); @endphp
                                @if ($imp)
                                    <div class="inicio-device-card"
                                        data-tipo="Impresora"
                                        data-id="{{ $imp->id }}"
                                        data-identificador="{{ $imp->identificador }}"
                                        data-nombre="{{ $imp->nombre }}"
                                        data-ip="{{ $imp->ip }}"
                                        data-marca="{{ $imp->marca_modelo ?? '—' }}"
                                        data-area="{{ $imp->area->nombre ?? 'Área no asignada' }}"
                                        data-deposito="{{ $imp->deposito->nombre ?? 'Depósito no asignado' }}"
                                        data-enuso="{{ $imp->area_id ? 'true' : 'false' }}"
                                        data-toner="{{ ComponenteModel::find($imp->toner_id)->nombre ?? 'Tóner no asignado' }}"
                                        role="button" tabindex="0">
                                        <div class="inicio-device-icon-wrap">
                                            <span class="material-symbols-outlined inicio-device-icon">print</span>
                                        </div>
                                        <div class="inicio-device-body">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span class="inicio-device-name">{{ $imp->nombre }}</span>
                                                @if ($imp->area_id)
                                                    <span class="inicio-badge-area" title="En área">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">meeting_room</span>
                                                        {{ Str::limit($imp->area->nombre, 14) }}
                                                    </span>
                                                @else
                                                    <span class="inicio-badge-deposito" title="En depósito">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">warehouse</span>
                                                        {{ Str::limit($imp->deposito->nombre ?? 'Sin asignar', 14) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="inicio-device-meta">Inv. {{ $imp->identificador }}</span>
                                            @if($imp->ip)
                                                <span class="inicio-device-meta font-monospace">{{ $imp->ip }}</span>
                                            @endif
                                        </div>
                                        <div class="inicio-device-footer">
                                            <span class="inicio-device-type">
                                                <span class="material-symbols-outlined" style="font-size:13px; vertical-align:middle;">info</span>
                                                Ver detalle
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- === TELÉFONO === --}}
                            @if ($lastDeviceUpdated->tipo_dispositivo == 'Telefono')
                                @php $tel = TelefonoModel::with(['area','deposito'])->find($lastDeviceUpdated->componente_id); @endphp
                                @if ($tel)
                                    <div class="inicio-device-card"
                                        data-tipo="Telefono"
                                        data-id="{{ $tel->id }}"
                                        data-identificador="{{ $tel->identificador }}"
                                        data-nombre="{{ $tel->nombre }}"
                                        data-ip="{{ $tel->ip }}"
                                        data-marca="{{ $tel->marca_modelo ?? '—' }}"
                                        data-area="{{ $tel->area->nombre ?? 'Área no asignada' }}"
                                        data-deposito="{{ $tel->deposito->nombre ?? 'Depósito no asignado' }}"
                                        data-enuso="{{ $tel->area_id ? 'true' : 'false' }}"
                                        data-numero="{{ $tel->numero ?? '—' }}"
                                        role="button" tabindex="0">
                                        <div class="inicio-device-icon-wrap">
                                            <span class="material-symbols-outlined inicio-device-icon">call</span>
                                        </div>
                                        <div class="inicio-device-body">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span class="inicio-device-name">{{ $tel->nombre }}</span>
                                                @if ($tel->area_id)
                                                    <span class="inicio-badge-area" title="En área">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">meeting_room</span>
                                                        {{ Str::limit($tel->area->nombre, 14) }}
                                                    </span>
                                                @else
                                                    <span class="inicio-badge-deposito" title="En depósito">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">warehouse</span>
                                                        {{ Str::limit($tel->deposito->nombre ?? 'Sin asignar', 14) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="inicio-device-meta">Inv. {{ $tel->identificador }}</span>
                                            @if($tel->ip)
                                                <span class="inicio-device-meta font-monospace">{{ $tel->ip }}</span>
                                            @endif
                                        </div>
                                        <div class="inicio-device-footer">
                                            <span class="inicio-device-type">
                                                <span class="material-symbols-outlined" style="font-size:13px; vertical-align:middle;">info</span>
                                                Ver detalle
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- === ROUTER === --}}
                            @if ($lastDeviceUpdated->tipo_dispositivo == 'Router')
                                @php $rou = RouterModel::with(['area','deposito'])->find($lastDeviceUpdated->componente_id); @endphp
                                @if ($rou)
                                    <div class="inicio-device-card"
                                        data-tipo="Router"
                                        data-id="{{ $rou->id }}"
                                        data-identificador="{{ $rou->identificador }}"
                                        data-nombre="{{ $rou->nombre }}"
                                        data-ip="{{ $rou->ip }}"
                                        data-marca="{{ $rou->marca_modelo ?? '—' }}"
                                        data-area="{{ $rou->area->nombre ?? 'Área no asignada' }}"
                                        data-deposito="{{ $rou->deposito->nombre ?? 'Depósito no asignado' }}"
                                        data-enuso="{{ $rou->area_id ? 'true' : 'false' }}"
                                        data-area_detalle="{{ $rou->area_detalle ?? '' }}"
                                        role="button" tabindex="0">
                                        <div class="inicio-device-icon-wrap">
                                            <span class="material-symbols-outlined inicio-device-icon">router</span>
                                        </div>
                                        <div class="inicio-device-body">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span class="inicio-device-name">{{ $rou->nombre }}</span>
                                                @if ($rou->area_id)
                                                    <span class="inicio-badge-area" title="En área">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">meeting_room</span>
                                                        {{ Str::limit($rou->area->nombre, 14) }}
                                                    </span>
                                                @else
                                                    <span class="inicio-badge-deposito" title="En depósito">
                                                        <span class="material-symbols-outlined" style="font-size:13px;">warehouse</span>
                                                        {{ Str::limit($rou->deposito->nombre ?? 'Sin asignar', 14) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="inicio-device-meta">Inv. {{ $rou->identificador }}</span>
                                            @if($rou->ip)
                                                <span class="inicio-device-meta font-monospace">{{ $rou->ip }}</span>
                                            @endif
                                        </div>
                                        <div class="inicio-device-footer">
                                            <span class="inicio-device-type">
                                                <span class="material-symbols-outlined" style="font-size:13px; vertical-align:middle;">info</span>
                                                Ver detalle
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Últimos movimientos --}}
            <div class="col-12 col-lg-5">
                <h2 class="inicio-section-title">Últimos movimientos</h2>

                @if ($historias->isEmpty())
                    <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                        <span class="material-symbols-outlined mb-2" style="font-size: 40px; opacity:.4;">history</span>
                        <span style="font-size:.9rem;">Sin movimientos registrados</span>
                    </div>
                @else
                    <div class="inicio-historia-list">
                        @foreach ($historias as $historia)
                            <div class="inicio-historia-item">
                                <div class="inicio-historia-dot"></div>
                                <div class="inicio-historia-content">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="inicio-historia-tecnico">{{ $historia->tecnico }}</span>
                                        <span class="inicio-historia-fecha">
                                            {{ \Carbon\Carbon::parse($historia->created_at)->format('d/m H:i') }}
                                        </span>
                                    </div>
                                    <span class="inicio-historia-detalle">{{ $historia->detalle }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>{{-- /row --}}
    </div>{{-- /container --}}


    {{-- ============================================================
         ESTILOS ENCAPSULADOS
         ============================================================ --}}
    <style>
        /* --- Títulos de sección --- */
        .inicio-section-title {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--hu-azul);
            margin-bottom: 1rem;
            padding-bottom: .5rem;
            border-bottom: 2px solid var(--hu-azul);
            display: inline-block;
        }

        /* --- Cards de navegación (acceso rápido) --- */
        .inicio-nav-card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
            padding: 20px 24px;
            background: #fff;
            border: 1px solid #E5E9EF;
            border-radius: 12px;
            text-decoration: none;
            min-width: 150px;
            transition: border-color .18s, box-shadow .18s, transform .18s;
        }
        .inicio-nav-card:hover {
            border-color: var(--hu-azul);
            box-shadow: 0 4px 16px rgba(0,55,100,.12);
            transform: translateY(-2px);
            text-decoration: none;
        }
        .inicio-nav-icon {
            font-size: 28px;
            color: var(--hu-azul);
            margin-bottom: 4px;
        }
        .inicio-nav-label {
            font-size: .95rem;
            font-weight: 600;
            color: var(--hu-azul);
        }
        .inicio-nav-sub {
            font-size: .78rem;
            color: #8A9BB0;
        }

        /* --- Cards de dispositivos recientes --- */
        .inicio-device-card {
            background: #fff;
            border: 1px solid #E5E9EF;
            border-radius: 12px;
            width: calc(50% - .75rem);
            min-width: 200px;
            max-width: 280px;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: border-color .18s, box-shadow .18s, transform .18s;
            overflow: hidden;
        }
        .inicio-device-card:hover {
            border-color: var(--hu-azul);
            box-shadow: 0 4px 16px rgba(0,55,100,.12);
            transform: translateY(-2px);
        }
        .inicio-device-icon-wrap {
            background: var(--hu-azul);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px 0;
        }
        .inicio-device-icon {
            font-size: 28px;
            color: #fff;
        }
        .inicio-device-body {
            padding: 12px 14px 8px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .inicio-device-name {
            font-size: .88rem;
            font-weight: 600;
            color: #1F2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 130px;
        }
        .inicio-device-meta {
            font-size: .75rem;
            color: #8A9BB0;
            display: block;
        }
        .inicio-device-footer {
            padding: 8px 14px 10px;
            border-top: 1px solid #F0F3F7;
        }
        .inicio-device-type {
            font-size: .72rem;
            color: var(--hu-azul);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* --- Badges de ubicación --- */
        .inicio-badge-area,
        .inicio-badge-deposito {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: .68rem;
            font-weight: 500;
            padding: 2px 7px;
            border-radius: 99px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .inicio-badge-area {
            background: #EAF3FF;
            color: var(--hu-azul);
        }
        .inicio-badge-deposito {
            background: #FFF3E0;
            color: #B45309;
        }

        /* --- Lista de movimientos --- */
        .inicio-historia-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            background: #fff;
            border: 1px solid #E5E9EF;
            border-radius: 12px;
            overflow: hidden;
        }
        .inicio-historia-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 16px;
            border-bottom: 1px solid #F0F3F7;
            transition: background .15s;
        }
        .inicio-historia-item:last-child { border-bottom: none; }
        .inicio-historia-item:hover { background: #F8FAFC; }
        .inicio-historia-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--hu-dorado);
            flex-shrink: 0;
            margin-top: 5px;
        }
        .inicio-historia-content { flex: 1; min-width: 0; }
        .inicio-historia-tecnico {
            font-size: .82rem;
            font-weight: 600;
            color: var(--hu-azul);
        }
        .inicio-historia-fecha {
            font-size: .72rem;
            color: #8A9BB0;
            white-space: nowrap;
        }
        .inicio-historia-detalle {
            font-size: .8rem;
            color: #4B5563;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* --- Modales: filas de info --- */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 8px;
            padding: 4p 0x;
            border-bottom: 1px solid #EAEFF5;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            font-size: .78rem;
            color: #8A9BB0;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .info-value {
            font-size: .82rem;
            font-weight: 500;
            color: #1F2937;
            text-align: right;
        }

        /* --- Responsive --- */
        @media (max-width: 576px) {
            .inicio-device-card { width: 100%; max-width: none; }
            .inicio-nav-card { min-width: calc(50% - .5rem); }
        }
    </style>


    {{-- ============================================================
         SCRIPTS
         ============================================================ --}}
    @push('scripts')
    <script>
    $(document).ready(function () {

        {{-- Abrir modal de cambio de contraseña si corresponde --}}
        @if (Auth::user()->pass_changed == false)
            var passModal = new bootstrap.Modal(document.getElementById('infoPassModal'));
            passModal.show();
        @endif

        {{-- Click en cards de dispositivos --}}
        $(document).on('click keypress', '.inicio-device-card', function (e) {
            if (e.type === 'keypress' && e.which !== 13) return;

            const $card = $(this);
            const tipo  = $card.data('tipo');

            if (tipo === 'PC') {
                const modal = $('#infoPcModal');
                modal.find('#pc-idenInfo').text($card.data('identificador'));
                modal.find('#pc-nombreInfo').text($card.data('nombre'));
                modal.find('#pc-ipInfo').text($card.data('ip') || '—');
                modal.find('#pc-titleAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? 'Área' : 'Depósito');
                modal.find('#pc-infoAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? $card.data('area') : $card.data('deposito'));
                modal.find('#pc-motherInfo').text($card.data('mother'));
                modal.find('#pc-proceInfo').text($card.data('proce'));
                modal.find('#pc-discosInfo').text($card.data('discos'));
                modal.find('#pc-ramsInfo').text($card.data('rams'));
                modal.find('#pc-fuenteInfo').text($card.data('fuente'));
                modal.find('#pc-placavidInfo').text($card.data('placavid'));
                new bootstrap.Modal(modal[0]).show();
            }

            if (tipo === 'Impresora') {
                const modal = $('#infoImpModal');
                modal.find('#imp-idenInfo').text($card.data('identificador'));
                modal.find('#imp-nombreInfo').text($card.data('nombre'));
                modal.find('#imp-marcaInfo').text($card.data('marca'));
                modal.find('#imp-ipInfo').text($card.data('ip') || '—');
                modal.find('#imp-tonerInfo').text($card.data('toner'));
                modal.find('#imp-titleAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? 'Área' : 'Depósito');
                modal.find('#imp-infoAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? $card.data('area') : $card.data('deposito'));
                new bootstrap.Modal(modal[0]).show();
            }

            if (tipo === 'Telefono') {
                const modal = $('#infoTelModal');
                modal.find('#tel-idenInfo').text($card.data('identificador'));
                modal.find('#tel-nombreInfo').text($card.data('nombre'));
                modal.find('#tel-marcaInfo').text($card.data('marca'));
                modal.find('#tel-numeroInfo').text($card.data('numero'));
                modal.find('#tel-ipInfo').text($card.data('ip') || '—');
                modal.find('#tel-titleAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? 'Área' : 'Depósito');
                modal.find('#tel-infoAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? $card.data('area') : $card.data('deposito'));
                new bootstrap.Modal(modal[0]).show();
            }

            if (tipo === 'Router') {
                const modal = $('#infoRouterModal');
                modal.find('#rou-idenInfo').text($card.data('identificador'));
                modal.find('#rou-nombreInfo').text($card.data('nombre'));
                modal.find('#rou-marcaInfo').text($card.data('marca'));
                modal.find('#rou-ipInfo').text($card.data('ip') || '—');
                modal.find('#rou-titleAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? 'Área' : 'Depósito');
                modal.find('#rou-infoAsig').text($card.data('enuso') === true || $card.data('enuso') === 'true' ? $card.data('area') : $card.data('deposito'));

                const detalle = $card.data('area_detalle');
                if (detalle) {
                    modal.find('#rou-detalleRow').show();
                    modal.find('#rou-areaDetalle').text(detalle);
                } else {
                    modal.find('#rou-detalleRow').hide();
                }

                new bootstrap.Modal(modal[0]).show();
            }
        });

    });
    </script>
    @endpush

</x-app-layout>