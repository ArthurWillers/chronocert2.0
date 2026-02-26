<x-layouts.guest>
    <x-auth-header title="Criar uma conta" description="Digite seus dados abaixo para criar sua conta" />

    <x-auth-session-status :status="session('status')" />

    <form action="{{ route('register.store') }}" method="POST" class="space-y-6" x-data="{ loading: false, showRules: false }"
        @submit="loading = true">
        @csrf

        {{-- Nome --}}
        <x-form-input label="Nome" type="text" name="name" :value="old('name')" placeholder="Seu nome completo"
            required autofocus />

        {{-- Endereço de email --}}
        <x-form-input label="Endereço de Email" type="email" name="email" :value="old('email')"
            placeholder="email@exemplo.com" required />

        {{-- Senha --}}
        <x-form-input label="Senha" type="password" name="password" placeholder="Sua senha" required viewable />

        {{-- Regras de senha --}}
        <x-password-rules />

        {{-- Confirmar Senha --}}
        <x-form-input label="Confirmar Senha" type="password" name="password_confirmation"
            placeholder="Confirme sua senha" required viewable />

        {{-- Botão criar conta --}}
        <x-button type="submit" class="w-full">
            <x-icon name="user-plus" class="w-6 h-6" />
            Criar Conta
        </x-button>

        {{-- Link para login --}}
        <div class="text-center text-sm text-neutral-600">
            <span>Já tem uma conta?</span>
            <x-link href="{{ route('login') }}">
                Fazer Login
            </x-link>
        </div>
    </form>

</x-layouts.guest>
