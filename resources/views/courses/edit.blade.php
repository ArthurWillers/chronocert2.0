<x-layouts.app>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <x-button href="{{ route('courses.index') }}" color="none" class="text-neutral-500 hover:text-neutral-700">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Voltar
            </x-button>
        </div>

        <x-card>
            <h1 class="text-2xl font-bold text-neutral-900 mb-1">Editar Curso</h1>
            <p class="text-sm text-neutral-500 mb-6">Atualize as informações do curso e suas categorias.</p>

            <form method="POST" action="{{ route('courses.update', $course) }}" x-data="{
                loading: false,
                categories: @js($course->categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'max_hours' => $c->max_hours])->values()),
                addCategory() {
                    this.categories.push({ id: null, name: '', max_hours: '' });
                },
                removeCategory(index) {
                    if (this.categories.length > 1) {
                        this.categories.splice(index, 1);
                    }
                }
            }"
                @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Nome do curso --}}
                    <x-form-input label="Nome do Curso" name="name" placeholder="Ex: Ciência da Computação"
                        :value="old('name', $course->name)" />
                    <x-form-error name="name" />

                    {{-- Total de horas --}}
                    <x-form-input label="Carga Horária Total" name="total_hours" placeholder="Ex: 200" :value="old('total_hours', $course->total_hours)"
                        numeric />
                    <x-form-error name="total_hours" />
                </div>

                {{-- Categorias --}}
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-neutral-900">Categorias</h2>
                            <p class="text-sm text-neutral-500">Edite, adicione ou remova categorias de atividades
                                complementares.</p>
                        </div>
                    </div>

                    <x-form-error name="categories" />

                    <div class="space-y-3">
                        <template x-for="(category, index) in categories" :key="index">
                            <div class="flex items-start gap-3 p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                                <input type="hidden" :name="'categories[' + index + '][id]'" :value="category.id" />
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="grid w-full items-center gap-1.5">
                                        <label :for="'categories[' + index + '][name]'"
                                            class="inline-flex items-center text-sm font-semibold text-neutral-700">
                                            Nome da Categoria
                                        </label>
                                        <input type="text" :name="'categories[' + index + '][name]'"
                                            :id="'categories[' + index + '][name]'" x-model="category.name"
                                            placeholder="Ex: Extensão"
                                            class="w-full border appearance-none text-sm rounded-xl block py-2.5 px-4 bg-white shadow-xs focus:shadow-lg text-neutral-700 placeholder-neutral-400 outline-none focus:border-accent focus:ring-2 focus:ring-accent/40 transition-colors duration-300 border-neutral-200" />
                                    </div>
                                    <div class="grid w-full items-center gap-1.5">
                                        <label :for="'categories[' + index + '][max_hours]'"
                                            class="inline-flex items-center text-sm font-semibold text-neutral-700">
                                            Horas Máximas
                                        </label>
                                        <input type="text" :name="'categories[' + index + '][max_hours]'"
                                            :id="'categories[' + index + '][max_hours]'" x-model="category.max_hours"
                                            placeholder="Ex: 40" inputmode="decimal"
                                            @input="$event.target.value = $event.target.value.replace(/[^0-9.,]/g, '')"
                                            class="w-full border appearance-none text-sm rounded-xl block py-2.5 px-4 bg-white shadow-xs focus:shadow-lg text-neutral-700 placeholder-neutral-400 outline-none focus:border-accent focus:ring-2 focus:ring-accent/40 transition-colors duration-300 border-neutral-200" />
                                    </div>
                                </div>
                                <button type="button" @click="removeCategory(index)" x-show="categories.length > 1"
                                    class="mt-7 p-1.5 rounded-lg text-neutral-400 hover:text-red-500 hover:bg-red-50 transition-colors cursor-pointer">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </div>
                        </template>
                    </div>

                    @foreach (['categories.*.name', 'categories.*.max_hours'] as $field)
                        <x-form-error name="{{ $field }}" />
                    @endforeach

                    <button type="button" @click="addCategory()"
                        class="mt-3 w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border-2 border-dashed border-neutral-300 text-sm font-semibold text-neutral-500 hover:border-neutral-400 hover:text-neutral-700 hover:bg-neutral-50 transition-colors cursor-pointer">
                        <x-icon name="plus" class="w-4 h-4" />
                        Adicionar Categoria
                    </button>
                </div>

                {{-- Botões --}}
                <div class="mt-8 flex justify-end gap-3">
                    <x-button href="{{ route('courses.index') }}" color="outline">
                        Cancelar
                    </x-button>
                    <x-button type="submit">
                        Salvar Alterações
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
