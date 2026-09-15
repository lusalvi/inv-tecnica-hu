<x-guest-layout>

    <h1 class="auth-title">Crear cuenta</h1>
    <p class="auth-subtitle">Completá los datos para registrarte</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="register-grid">

            <div class="auth-field">
                <label for="name">Nombre y Apellido</label>
                <input id="name" type="text" name="name"
                       class="auth-input @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       required autofocus autocomplete="name"
                       placeholder="Tu nombre completo">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div class="auth-field">
                <label for="email">Usuario</label>
                <input id="email" type="text" name="email"
                       class="auth-input @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required autocomplete="username"
                       placeholder="Ingresá tu usuario">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="auth-field">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password"
                       class="auth-input @error('password') is-invalid @enderror"
                       required autocomplete="new-password"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="auth-field">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="auth-input @error('password_confirmation') is-invalid @enderror"
                       required autocomplete="new-password"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

        </div>

        <div class="auth-field" style="margin-top: 1.25rem;">
            <label for="role">Rol asignado</label>
            <input id="role" type="text" class="auth-input" value="Técnico" readonly
                   style="color: #8a9ab0; cursor: default;">
            <p class="register-role-note">Para cambiar el rol, comunicarse con el administrador.</p>
        </div>

        <div class="register-actions">
            <a href="{{ route('login') }}" class="register-login-link">
                ¿Ya estás registrado? <span>Iniciar sesión</span>
            </a>
            <button type="submit" class="btn-hu">Registrarse</button>
        </div>

    </form>

</x-guest-layout>
