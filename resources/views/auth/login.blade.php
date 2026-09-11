<x-guest-layout>
    {{-- Alerta de sesión --}}
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Usuario --}}
        <div class="mb-3">
            <label for="email" class="form-label">Usuario</label>
            <input id="email" type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="Ingresá tu usuario">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Contraseña --}}
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                required autocomplete="current-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Recordarme --}}
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label" style="font-size:.82rem;">Recordarme</label>
        </div>

        <button type="submit" class="btn-hu-login">Iniciar sesión</button>

        @if (Route::has('password.request'))
            <div class="divider"></div>
            <p class="register-link">
                ¿No tenés cuenta? <a href="{{ route('register') }}">Registrarse</a>
            </p>
        @endif
    </form>
</x-guest-layout>
