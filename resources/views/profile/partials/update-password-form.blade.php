<div style="background:#fff; border-radius:14px; border:1px solid rgba(0,55,100,.1); overflow:hidden;">

    {{-- Encabezado de sección --}}
    <div style="padding:1.4rem 1.75rem; border-bottom:1px solid rgba(0,55,100,.08); display:flex; align-items:center; gap:.75rem;">
        <span class="material-symbols-outlined" style="color:var(--hu-azul); font-size:1.3rem;">lock</span>
        <div>
            <h3 style="margin:0; font-size:.95rem; font-weight:700; color:var(--hu-azul);">Actualizar contraseña</h3>
            <p style="margin:0; font-size:.75rem; color:#8a9ab0; margin-top:.15rem;">Usá una contraseña larga y única para mantener tu cuenta segura.</p>
        </div>
    </div>

    {{-- Formulario --}}
    <form method="post" action="{{ route('password.update') }}" style="padding:1.75rem;">
        @csrf
        @method('put')

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem 1.75rem;">

            {{-- Contraseña actual (ocupa fila completa) --}}
            <div style="grid-column: 1 / -1;">
                <label class="hu-profile-label" for="update_password_current_password">Contraseña actual</label>
                <input type="password" id="update_password_current_password" name="current_password"
                       class="hu-profile-input @error('current_password', 'updatePassword') hu-profile-input--error @enderror"
                       autocomplete="current-password"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
            </div>

            {{-- Nueva contraseña --}}
            <div>
                <label class="hu-profile-label" for="update_password_password">Nueva contraseña</label>
                <input type="password" id="update_password_password" name="password"
                       class="hu-profile-input @error('password', 'updatePassword') hu-profile-input--error @enderror"
                       autocomplete="new-password"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>

            {{-- Confirmar contraseña --}}
            <div>
                <label class="hu-profile-label" for="update_password_password_confirmation">Confirmar contraseña</label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                       class="hu-profile-input @error('password_confirmation', 'updatePassword') hu-profile-input--error @enderror"
                       autocomplete="new-password"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>

        </div>

        <div style="margin-top:1.5rem; display:flex; align-items:center; gap:1rem;">
            <button type="submit" class="btn-hu" style="width:auto; padding:.5rem 1.5rem;">
                <span class="material-symbols-outlined" style="font-size:1rem; vertical-align:middle; margin-right:.3rem;">key</span>
                Cambiar contraseña
            </button>

            @if (session('status') === 'password-updated')
                <span x-data="{ show: true }" x-show="show" x-transition
                      x-init="setTimeout(() => show = false, 2500)"
                      style="font-size:.78rem; color:#2e7d52; font-weight:600; display:flex; align-items:center; gap:.3rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;">check_circle</span>
                    Contraseña actualizada
                </span>
            @endif
        </div>

    </form>
</div>