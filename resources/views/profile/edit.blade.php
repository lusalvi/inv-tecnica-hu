<x-app-layout>
    <x-slot name="header">
        <h2 style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:1.1rem; color:var(--hu-azul); margin:0;">
            Perfil
        </h2>
    </x-slot>

    <div style="max-width:860px; margin:2.5rem auto; padding:0 1.25rem; display:flex; flex-direction:column; gap:1.5rem;">
        @include('profile.partials.update-profile-information-form')
        @include('profile.partials.update-password-form')
    </div>
</x-app-layout>
