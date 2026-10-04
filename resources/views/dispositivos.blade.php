<x-app-layout>
    <div class="px-4 px-md-5 py-4" style="max-width: 900px; margin: 0 auto;">

        <h2 class="inicio-section-title mb-4">Dispositivos</h2>

        <div class="d-flex flex-wrap gap-3">

            <a href="{{ route('gest_pc') }}" class="disp-card">
                <div class="disp-icon-wrap">
                    <span class="material-symbols-outlined disp-icon">computer</span>
                </div>
                <div class="disp-body">
                    <span class="disp-label">PCs</span>
                    <span class="disp-sub">Administrar equipos</span>
                </div>
                <span class="material-symbols-outlined disp-arrow">chevron_right</span>
            </a>

            <a href="{{ route('gest_impresoras') }}" class="disp-card">
                <div class="disp-icon-wrap">
                    <span class="material-symbols-outlined disp-icon">print</span>
                </div>
                <div class="disp-body">
                    <span class="disp-label">Impresoras</span>
                    <span class="disp-sub">Administrar impresoras</span>
                </div>
                <span class="material-symbols-outlined disp-arrow">chevron_right</span>
            </a>

            <a href="{{ route('gest_telefonos') }}" class="disp-card">
                <div class="disp-icon-wrap">
                    <span class="material-symbols-outlined disp-icon">call</span>
                </div>
                <div class="disp-body">
                    <span class="disp-label">Teléfonos IP</span>
                    <span class="disp-sub">Administrar teléfonos</span>
                </div>
                <span class="material-symbols-outlined disp-arrow">chevron_right</span>
            </a>

            <a href="{{ route('gest_routers') }}" class="disp-card">
                <div class="disp-icon-wrap">
                    <span class="material-symbols-outlined disp-icon">router</span>
                </div>
                <div class="disp-body">
                    <span class="disp-label">Routers</span>
                    <span class="disp-sub">Administrar routers</span>
                </div>
                <span class="material-symbols-outlined disp-arrow">chevron_right</span>
            </a>

        </div>
    </div>

    <style>
        .inicio-section-title {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--hu-azul);
            padding-bottom: .5rem;
            border-bottom: 2px solid var(--hu-azul);
            display: inline-block;
        }

        .disp-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            background: #fff;
            border: 1px solid #E5E9EF;
            border-radius: 12px;
            text-decoration: none;
            width: calc(50% - .75rem);
            min-width: 220px;
            transition: border-color .18s, box-shadow .18s, transform .18s;
        }

        .disp-card:hover {
            border-color: var(--hu-azul);
            box-shadow: 0 4px 16px rgba(0,55,100,.1);
            transform: translateY(-2px);
            text-decoration: none;
        }

        .disp-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: rgba(0,55,100,.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .disp-icon {
            font-size: 24px;
            color: var(--hu-azul);
        }

        .disp-body {
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
        }

        .disp-label {
            font-size: .95rem;
            font-weight: 600;
            color: var(--hu-azul);
        }

        .disp-sub {
            font-size: .78rem;
            color: #8A9BB0;
        }

        .disp-arrow {
            font-size: 20px;
            color: #CBD5E1;
            transition: color .18s;
        }

        .disp-card:hover .disp-arrow {
            color: var(--hu-azul);
        }

        @media (max-width: 576px) {
            .disp-card { width: 100%; }
        }
    </style>
</x-app-layout>
