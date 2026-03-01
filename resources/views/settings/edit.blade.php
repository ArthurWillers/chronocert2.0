<x-layouts.app>
    <div class="max-w-2xl mx-auto">
        <x-page-header title="Configurações" />

        {{-- Informações do Perfil --}}
        <x-card class="mb-6">
            <h2 class="text-lg font-bold text-neutral-900 mb-1">Informações do Perfil</h2>
            <p class="text-sm text-neutral-500 mb-6">Atualize seu nome e endereço de e-mail.</p>

            <form method="POST" action="{{ route('user-profile-information.update') }}" x-data="{ loading: false }"
                @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <x-form-input label="Nome" name="name" placeholder="Seu nome completo" :value="old('name', $user->name)"
                            bag="updateProfileInformation" />
                    </div>

                    <div>
                        <x-form-input label="E-mail" name="email" type="email" placeholder="seu@email.com"
                            :value="old('email', $user->email)" bag="updateProfileInformation" />
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <x-button type="submit">
                        Salvar
                    </x-button>
                </div>
            </form>
        </x-card>

        {{-- Alterar Senha --}}
        <x-card class="mb-6" x-data="{ showRules: false }">
            <h2 class="text-lg font-bold text-neutral-900 mb-1">Alterar Senha</h2>
            <p class="text-sm text-neutral-500 mb-6">Use uma senha forte para proteger sua conta.</p>

            <x-password-rules />

            <form method="POST" action="{{ route('user-password.update') }}" x-data="{ loading: false }"
                @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <x-form-input label="Senha Atual" name="current_password" type="password" viewable
                            placeholder="Digite sua senha atual" bag="updatePassword" />
                    </div>

                    <div>
                        <x-form-input label="Nova Senha" name="password" type="password" viewable
                            placeholder="Digite a nova senha" bag="updatePassword" />
                    </div>

                    <div>
                        <x-form-input label="Confirmar Nova Senha" name="password_confirmation" type="password" viewable
                            placeholder="Confirme a nova senha" bag="updatePassword" />
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <x-button type="submit">
                        Alterar Senha
                    </x-button>
                </div>
            </form>
        </x-card>

        {{-- Excluir Conta --}}
        <x-card class="border-red-200" x-data="{ showDeleteConfirm: {{ $errors->has('delete_password') ? 'true' : 'false' }}, loading: false }">
            <h2 class="text-lg font-bold text-red-600 mb-1">Excluir Conta</h2>
            <p class="text-sm text-neutral-500 mb-4">Após excluir sua conta, todos os seus dados serão permanentemente
                removidos. Esta ação não pode ser desfeita.</p>

            <div x-show="!showDeleteConfirm">
                <x-button color="red" @click="showDeleteConfirm = true">
                    Excluir Conta
                </x-button>
            </div>

            <div x-show="showDeleteConfirm" x-cloak x-transition>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-sm text-red-700 font-semibold mb-4">Tem certeza que deseja excluir sua conta? Digite
                        sua senha para confirmar.</p>

                    <form method="POST" action="{{ route('settings.destroy') }}" x-data="{ loading: false }"
                        @submit="loading = true">
                        @csrf
                        @method('DELETE')

                        <div class="mb-4">
                            <x-form-input label="Senha" name="delete_password" type="password" viewable
                                placeholder="Digite sua senha para confirmar" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-button type="submit" color="red">
                                Confirmar Exclusão
                            </x-button>
                            <x-button color="outline" @click.prevent="showDeleteConfirm = false">
                                Cancelar
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>
