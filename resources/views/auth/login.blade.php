<x-guest-layout>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <h1 class="auth-title">Bienvenido</h1>
    <p class="auth-subtitle">Sistema de Inventario — Área Técnica</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label for="email">Usuario</label>
            <input id="email" type="text" name="email"
                   class="auth-input @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="Ingresá tu usuario">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="auth-field">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password"
                   class="auth-input @error('password') is-invalid @enderror"
                   required autocomplete="current-password"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="auth-remember">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Recordarme</label>
        </div>

        <button type="submit" class="btn-hu">Iniciar sesión</button>

        <p class="auth-link">
            ¿No tenés cuenta? <a href="{{ route('register') }}">Registrarse</a>
        </p>

    </form>

</x-guest-layout>
