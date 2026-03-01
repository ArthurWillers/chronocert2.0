<x-layouts.app>
    <x-page-header title="Gerenciar Cursos" action="{{ route('courses.create') }}" actionText="Novo Curso" icon="plus" />

    @if ($courses->isEmpty())
        <x-card>
            <x-empty-state message="Você ainda não possui nenhum curso cadastrado." icon="academic-cap"
                actionText="Criar meu primeiro curso" :actionRoute="route('courses.create')" />
        </x-card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($courses as $course)
                <x-card class="flex flex-col gap-3 group relative">
                    {{-- Dropdown de ações --}}
                    <div class="absolute top-4 right-4">
                        <x-dropdown position="bottom-end" contentClass="w-40">
                            <x-slot name="trigger">
                                <button
                                    class="p-1 rounded-lg text-neutral-400 hover:text-neutral-700 hover:bg-neutral-100 transition-colors cursor-pointer">
                                    <x-icon name="ellipsis-horizontal" class="w-5 h-5" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <form method="POST" action="{{ route('courses.destroy', $course) }}"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este curso? Todas as categorias e certificados serão removidos.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-100 cursor-pointer">
                                        <x-icon name="trash" class="w-4 h-4" />
                                        Excluir
                                    </button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Conteúdo do card --}}
                    <div>
                        <h3 class="text-lg font-bold text-neutral-900 pr-8">{{ $course->name }}</h3>
                        <p class="text-sm text-neutral-500 mt-1">
                            {{ number_format($course->total_hours, 0, ',', '.') }} horas total
                        </p>
                    </div>

                    <div class="flex items-center gap-4 text-sm text-neutral-500">
                        <span class="flex items-center gap-1">
                            <x-icon name="folder" class="w-4 h-4" />
                            {{ $course->categories_count }}
                            {{ $course->categories_count === 1 ? 'categoria' : 'categorias' }}
                        </span>
                    </div>

                    <div class="mt-auto pt-3 border-t border-neutral-100">
                        <x-button href="{{ route('courses.edit', $course) }}" color="outline" class="w-full">
                            <x-icon name="pencil" class="w-4 h-4" />
                            Editar curso
                        </x-button>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
</x-layouts.app>
