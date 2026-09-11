<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="register-grid">

            {{-- Nombre y apellido --}}
            <div class="register-field">
                <label for="name" class="form-label">
                    Nombre y Apellido
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Ingresá tu nombre y apellido"
                >

                <x-input-error
                    :messages="$errors->get('name')"
                    class="mt-1"
                />
            </div>

            {{-- Usuario --}}
            <div class="register-field">
                <label for="email" class="form-label">
                    Usuario
                </label>

                <input
                    id="email"
                    type="text"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="Ingresá tu usuario"
                >

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-1"
                />
            </div>

            {{-- Contraseña --}}
            <div class="register-field">
                <label for="password" class="form-label">
                    Contraseña
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-1"
                />
            </div>

            {{-- Confirmar contraseña --}}
            <div class="register-field">
                <label for="password_confirmation" class="form-label">
                    Confirmar contraseña
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >

                <x-input-error
                    :messages="$errors->get('password_confirmation')"
                    class="mt-1"
                />
            </div>

        </div>

        {{-- Rol --}}
        <div class="register-role">
            <label class="form-label" for="role">
                Rol
            </label>

            <input
                id="role"
                type="text"
                class="form-control"
                value="Técnico"
                readonly
            >

            <p class="register-role-help">
                Para cambiar el rol, debés comunicarte con el administrador.
            </p>
        </div>

        {{-- Acciones --}}
        <div class="register-actions">

            <a
                href="{{ route('login') }}"
                class="register-login-link"
            >
                ¿Ya estás registrado? <span>Iniciar sesión</span>
            </a>

            <button type="submit" class="btn-hu-login">
                Registrarse
            </button>

        </div>

    </form>

</x-guest-layout>