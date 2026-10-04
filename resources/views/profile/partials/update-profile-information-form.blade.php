<div style="background:#fff; border-radius:14px; border:1px solid rgba(0,55,100,.1); overflow:hidden;">

    {{-- Encabezado de sección --}}
    <div style="padding:1.4rem 1.75rem; border-bottom:1px solid rgba(0,55,100,.08); display:flex; align-items:center; gap:.75rem;">
        <span class="material-symbols-outlined" style="color:var(--hu-azul); font-size:1.3rem;">manage_accounts</span>
        <div>
            <h3 style="margin:0; font-size:.95rem; font-weight:700; color:var(--hu-azul);">Información del perfil</h3>
            <p style="margin:0; font-size:.75rem; color:#8a9ab0; margin-top:.15rem;">Actualizá tu usuario o nombre de cuenta.</p>
        </div>
    </div>

    {{-- Formulario --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" style="padding:1.75rem;">
        @csrf
        @method('patch')

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem 1.75rem;">

            {{-- Nombre (readonly) --}}
            <div>
                <label class="hu-profile-label">Nombre y Apellido</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $user->name) }}"
                       class="hu-profile-input hu-profile-input--readonly @error('name') hu-profile-input--error @enderror"
                       readonly autocomplete="name">
                <p class="hu-profile-hint">
                    <span class="material-symbols-outlined" style="font-size:.75rem; vertical-align:middle;">info</span>
                    Para cambiar el nombre, contactá al administrador.
                </p>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            {{-- Rol (readonly) --}}
            <div>
                <label class="hu-profile-label">Rol</label>
                <input type="text" name="rol" id="rol"
                       value="{{ old('rol', $user->rol?->label()) }}"
                       class="hu-profile-input hu-profile-input--readonly"
                       readonly>
                <p class="hu-profile-hint">
                    <span class="material-symbols-outlined" style="font-size:.75rem; vertical-align:middle;">info</span>
                    Para cambiar el rol, contactá al administrador.
                </p>
            </div>

            {{-- Usuario (editable) --}}
            <div style="grid-column: 1 / -1;">
                <label class="hu-profile-label" for="email">Usuario</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}"
                       class="hu-profile-input @error('email') hu-profile-input--error @enderror"
                       required autocomplete="username"
                       placeholder="usuario@ejemplo.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

        </div>

        <div style="margin-top:1.5rem; display:flex; align-items:center; gap:1rem;">
            <button type="submit" class="btn-hu" style="width:auto; padding:.5rem 1.5rem;">
                <span class="material-symbols-outlined" style="font-size:1rem; vertical-align:middle; margin-right:.3rem;">save</span>
                Guardar cambios
            </button>

            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }" x-show="show" x-transition
                      x-init="setTimeout(() => show = false, 2500)"
                      style="font-size:.78rem; color:#2e7d52; font-weight:600; display:flex; align-items:center; gap:.3rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;">check_circle</span>
                    Cambios guardados
                </span>
            @endif
        </div>

    </form>
</div>

<style>
    .hu-profile-label {
        display: block;
        font-size: .72rem;
        font-weight: 700;
        color: var(--hu-azul);
        margin-bottom: .4rem;
        letter-spacing: .03em;
        text-transform: uppercase;
    }
    .hu-profile-input {
        width: 100%;
        border: 1px solid #d0dae6;
        border-radius: 8px;
        padding: .5rem .8rem;
        font-family: 'Montserrat', sans-serif;
        font-size: .875rem;
        color: var(--hu-texto);
        background: #fff;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .hu-profile-input:focus {
        border-color: var(--hu-azul);
        box-shadow: 0 0 0 3px rgba(0,55,100,.1);
    }
    .hu-profile-input--readonly {
        background: #f6f8fb;
        color: #8a9ab0;
        cursor: default;
    }
    .hu-profile-input--error {
        border-color: #dc3545;
    }
    .hu-profile-hint {
        margin: .35rem 0 0;
        font-size: .7rem;
        color: #a0aebb;
        line-height: 1.4;
    }
</style>