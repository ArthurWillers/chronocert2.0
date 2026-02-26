<x-layouts.guest>
    <x-auth-header title="Confirme sua senha"
        description="Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar." />

    <x-auth-session-status :status="session('status')" />

    <form action="{{ route('password.confirm.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Senha --}}
        <x-form-input label="Senha" type="password" name="password" placeholder="Sua senha" required autofocus viewable />

        {{-- Botão de confirmar --}}
        <x-button type="submit" class="w-full">
            <x-icon name="lock-open" class="w-6 h-6" />
            Confirmar
        </x-button>

        {{-- Link voltar --}}
        <div class="text-center">
            <a href="{{ route('settings') }}"
                class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-800 transition-colors">
                <x-icon name="arrow-uturn-left" class="w-4 h-4 mr-1" />
                Voltar para configurações
            </a>
        </div>
    </form>
</x-layouts.guest>
