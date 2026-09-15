@php($navigationBaseUrl = request()->getBaseUrl())

<nav x-data="{ open: false }" style="background-color: #fff; border-bottom: 2px solid var(--hu-azul);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center" style="height: 64px;">

            {{-- Logo --}}
            <div class="flex items-center gap-6">
                <a href="{{ $navigationBaseUrl . route('inicio', [], false) }}" class="shrink-0 flex items-center">
                    <x-hu_logo style="height: 38px; width: auto;" />
                </a>

                {{-- Links de navegación (desktop) --}}
                <div class="hidden sm:flex items-center gap-1">

                    {{-- Inicio --}}
                    <a href="{{ $navigationBaseUrl . route('inicio', [], false) }}"
                       class="nav-item {{ request()->routeIs('inicio') ? 'nav-item--active' : '' }}">
                        Inicio
                    </a>

                    {{-- Gestión (dropdown Alpine) --}}
                    <div x-data="{ openGestion: false }" class="relative">
                        <button @click="openGestion = !openGestion" @click.outside="openGestion = false"
                                class="nav-item flex items-center gap-1">
                            Gestión
                            <span class="material-symbols-outlined" style="font-size:1rem; transition: transform 0.2s;"
                                  :style="openGestion ? 'transform: rotate(180deg)' : ''">expand_more</span>
                        </button>
                        <div x-show="openGestion" x-transition x-cloak
                             class="nav-dropdown">
                            <a href="{{ $navigationBaseUrl . route('dispositivos', [], false) }}" class="nav-dropdown-item">
                                <span class="material-symbols-outlined nav-dropdown-icon">devices</span>
                                Dispositivos
                            </a>
                            <a href="{{ $navigationBaseUrl . route('gest_componentes', [], false) }}" class="nav-dropdown-item">
                                <span class="material-symbols-outlined nav-dropdown-icon">inventory_2</span>
                                Stock
                            </a>
                            @if (Auth::user()->rol->nombre === 'Administrador' || Auth::user()->rol->nombre === 'Super administrador')
                                <div class="nav-dropdown-divider"></div>
                                <a href="{{ $navigationBaseUrl . route('gest_areas', [], false) }}" class="nav-dropdown-item">
                                    <span class="material-symbols-outlined nav-dropdown-icon">location_on</span>
                                    Áreas
                                </a>
                                <a href="{{ $navigationBaseUrl . route('gest_depositos', [], false) }}" class="nav-dropdown-item">
                                    <span class="material-symbols-outlined nav-dropdown-icon">warehouse</span>
                                    Depósitos
                                </a>
                                <a href="{{ $navigationBaseUrl . route('gest_tipo_componente', [], false) }}" class="nav-dropdown-item">
                                    <span class="material-symbols-outlined nav-dropdown-icon">category</span>
                                    Categorías
                                </a>
                                <a href="{{ $navigationBaseUrl . route('gest_state', [], false) }}" class="nav-dropdown-item">
                                    <span class="material-symbols-outlined nav-dropdown-icon">flag</span>
                                    Estados
                                </a>
                            @endif
                        </div>
                    </div>

                    <a href="{{ $navigationBaseUrl . route('historia', [], false) }}"
                       class="nav-item {{ request()->routeIs('historia') ? 'nav-item--active' : '' }}">
                        Historia
                    </a>

                    <a href="{{ $navigationBaseUrl . route('reportes', [], false) }}"
                       class="nav-item {{ request()->routeIs('reportes') ? 'nav-item--active' : '' }}">
                        Reportes
                    </a>

                </div>
            </div>

            {{-- Usuario (dropdown) --}}
            <div class="hidden sm:flex items-center">
                <div x-data="{ openUser: false }" class="relative">
                    <button @click="openUser = !openUser" @click.outside="openUser = false"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold"
                            style="color: var(--hu-azul); transition: background 0.15s ease;"
                            onmouseover="this.style.backgroundColor='#f0f4f8'"
                            onmouseout="this.style.backgroundColor='transparent'">
                        <span class="material-symbols-outlined" style="font-size:1.2rem;">account_circle</span>
                        <span>{{ Auth::user()->name }}</span>
                        <span style="font-size:0.75rem; color: var(--hu-dorado); font-weight:700;">
                            {{ Auth::user()->rol->nombre }}
                        </span>
                        <span class="material-symbols-outlined" style="font-size:1rem;">expand_more</span>
                    </button>
                    <div x-show="openUser" x-transition x-cloak
                         class="nav-dropdown" style="right:0; left:auto;">
                        <a href="{{ $navigationBaseUrl . route('profile.edit', [], false) }}" class="nav-dropdown-item">
                            <span class="material-symbols-outlined nav-dropdown-icon">manage_accounts</span>
                            Perfil
                        </a>
                        <div class="nav-dropdown-divider"></div>
                        <form method="POST" action="{{ $navigationBaseUrl . route('logout', [], false) }}">
                            @csrf
                            <button type="submit" class="nav-dropdown-item w-100 text-start border-0 bg-transparent"
                                    style="color: #c62828;">
                                <span class="material-symbols-outlined nav-dropdown-icon" style="color:#c62828;">logout</span>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Hamburger (mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md"
                        style="color: var(--hu-azul);">
                    <span class="material-symbols-outlined">{{ 'menu' }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden"
         style="border-top: 1px solid rgba(0,55,100,0.1);">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ $navigationBaseUrl . route('inicio', [], false) }}" class="nav-mobile-item">Inicio</a>
            <a href="{{ $navigationBaseUrl . route('dispositivos', [], false) }}" class="nav-mobile-item">Dispositivos</a>
            <a href="{{ $navigationBaseUrl . route('gest_componentes', [], false) }}" class="nav-mobile-item">Stock</a>
            <a href="{{ $navigationBaseUrl . route('historia', [], false) }}" class="nav-mobile-item">Historia</a>
            <a href="{{ $navigationBaseUrl . route('reportes', [], false) }}" class="nav-mobile-item">Reportes</a>
            @if (Auth::user()->rol->nombre === 'Administrador' || Auth::user()->rol->nombre === 'Super administrador')
                <div style="border-top: 1px solid rgba(0,55,100,0.1); margin: 8px 0; padding-top:8px;">
                    <p style="font-size:0.7rem; font-weight:700; color: var(--hu-dorado); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">
                        Administración
                    </p>
                    <a href="{{ $navigationBaseUrl . route('gest_areas', [], false) }}" class="nav-mobile-item">Áreas</a>
                    <a href="{{ $navigationBaseUrl . route('gest_depositos', [], false) }}" class="nav-mobile-item">Depósitos</a>
                    <a href="{{ $navigationBaseUrl . route('gest_tipo_componente', [], false) }}" class="nav-mobile-item">Categorías</a>
                    <a href="{{ $navigationBaseUrl . route('gest_state', [], false) }}" class="nav-mobile-item">Estados</a>
                </div>
            @endif
        </div>
        <div style="border-top: 1px solid rgba(0,55,100,0.1);" class="px-4 pt-3 pb-3">
            <div style="font-weight:700; font-size:0.9rem; color: var(--hu-azul);">{{ Auth::user()->name }}</div>
            <div style="font-size:0.75rem; color: var(--hu-dorado); font-weight:600;">{{ Auth::user()->rol->nombre }}</div>
            <div class="mt-2 space-y-1">
                <a href="{{ $navigationBaseUrl . route('profile.edit', [], false) }}" class="nav-mobile-item">Perfil</a>
                <form method="POST" action="{{ $navigationBaseUrl . route('logout', [], false) }}">
                    @csrf
                    <button type="submit" class="nav-mobile-item w-100 text-start border-0 bg-transparent"
                            style="color:#c62828; padding-left:0;">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-item {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--hu-texto);
        text-decoration: none;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .nav-item:hover {
        background-color: #f0f4f8;
        color: var(--hu-azul);
        text-decoration: none;
    }

    .nav-item--active {
        color: var(--hu-azul);
        background-color: rgba(0, 55, 100, 0.08);
    }

    .nav-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        background: #fff;
        border: 1px solid rgba(0, 55, 100, 0.12);
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0, 55, 100, 0.12);
        min-width: 200px;
        padding: 6px;
        z-index: 50;
    }

    .nav-dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 0.75rem;
        border-radius: 7px;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--hu-texto);
        text-decoration: none;
        transition: background-color 0.15s ease, color 0.15s ease;
        cursor: pointer;
    }

    .nav-dropdown-item:hover {
        background-color: #f0f4f8;
        color: var(--hu-azul);
        text-decoration: none;
    }

    .nav-dropdown-icon {
        font-size: 1rem;
        color: var(--hu-azul);
    }

    .nav-dropdown-divider {
        border-top: 1px solid rgba(0, 55, 100, 0.08);
        margin: 4px 0;
    }

    .nav-mobile-item {
        display: block;
        padding: 0.5rem 0;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--hu-texto);
        text-decoration: none;
        border-radius: 6px;
        transition: color 0.15s ease;
    }

    .nav-mobile-item:hover {
        color: var(--hu-azul);
        text-decoration: none;
    }
</style>