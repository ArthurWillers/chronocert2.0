<x-layouts.app>
    <div class="border-b border-neutral-200 mb-4 pb-4">
        <h1 class="text-2xl font-bold text-neutral-900">Visão Geral</h1>
        <p class="text-neutral-500">Selecione o curso para verificar o seu progresso</p>
        @unless ($courses->isEmpty())
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($courses as $course)
                    @php $isActive = $activeCourse && $activeCourse->id === $course->id; @endphp
                    <a href="{{ route('dashboard', $course->id) }}" @class([
                        'inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold transition-colors',
                        'bg-neutral-900 text-white' => $isActive,
                        'bg-neutral-100 text-neutral-600 hover:bg-neutral-200 hover:border-neutral-300 border border-neutral-200' => !$isActive,
                    ])>
                        {{ $course->name }}
                    </a>
                @endforeach
            </div>
        @endunless
    </div>

    @if ($courses->isEmpty())
        <x-card class="flex flex-col items-center justify-center py-16 text-center">
            <div class="bg-neutral-100 rounded-full p-5 mb-5">
                <x-icon name="academic-cap" class="w-10 h-10 text-neutral-400" />
            </div>
            <h2 class="text-lg font-semibold text-neutral-800 mb-1">Nenhum curso cadastrado</h2>
            <p class="text-neutral-500 text-sm mb-6 max-w-sm">Crie seu primeiro curso para começar a registrar as horas
                complementares.</p>
            <x-button href="{{ route('courses.create') }}">
                Criar meu primeiro curso
            </x-button>
        </x-card>
    @else
        <div class="my-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card class="flex items-center justify-between gap-4">
                <div class="flex flex-col gap-0.5 flex-1">
                    <span class="text-sm font-bold text-neutral-500 uppercase tracking-wide">Horas Concluídas</span>
                    <div class="flex items-end gap-1 mt-1">
                        <span
                            class="text-2xl font-bold text-neutral-900">{{ number_format($stats['completed_hours'], 0, ',', '.') }}h</span>
                        <span class="text-sm text-neutral-400 mb-0.5">/
                            {{ $activeCourse ? number_format($activeCourse->total_hours, 0, ',', '.') : '—' }}h</span>
                    </div>
                    <span class="text-xs text-neutral-400">horas registradas</span>
                </div>
                <div class="shrink-0 w-24 h-24 flex items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                        <x-icon name="clock" class="w-8 h-8" />
                    </div>
                </div>
            </x-card>

            @php
                $pct = $stats['progress_percentage'];
                $strokeColor = match (true) {
                    $pct >= 100 => 'text-green-500',
                    $pct >= 67 => 'text-blue-500',
                    $pct >= 34 => 'text-amber-400',
                    default => 'text-red-400',
                };
                $textColor = match (true) {
                    $pct >= 100 => 'text-green-600',
                    $pct >= 67 => 'text-blue-600',
                    $pct >= 34 => 'text-amber-500',
                    default => 'text-red-500',
                };
                $circumference = 188.5;
                $targetOffset = round($circumference * (1 - $pct / 100), 2);
            @endphp
            <x-card class="flex items-center justify-between gap-4" x-data="{ offset: {{ $circumference }}, displayPct: 0 }" x-init="requestAnimationFrame(() => requestAnimationFrame(() => {
                offset = {{ $targetOffset }};
                const target = {{ $pct }},
                    duration = 1000;
                let start = null;
            
                function step(ts) {
                    if (!start) start = ts;
                    const p = Math.min((ts - start) / duration, 1);
                    displayPct = Math.round(p * target);
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }))">
                <div class="flex flex-col gap-0.5 flex-1">
                    <span class="text-sm font-bold text-neutral-500 uppercase tracking-wide">Progresso Geral</span>
                    <span class="text-sm text-neutral-400 mt-1">de {{ number_format($activeCourse->total_hours, 1) }}h
                        totais</span>
                </div>
                <div class="relative shrink-0 w-24 h-24">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 80 80">
                        <circle cx="40" cy="40" r="30" fill="none" stroke-width="8"
                            stroke="currentColor" class="text-neutral-200" />
                        <circle cx="40" cy="40" r="30" fill="none" stroke-width="8"
                            stroke="currentColor" class="{{ $strokeColor }}" stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $circumference }}" :stroke-dashoffset="offset" stroke-linecap="round"
                            style="transition: stroke-dashoffset 1s ease-out" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-base font-bold {{ $textColor }}" x-text="displayPct + '%'">0%</span>
                    </div>
                </div>
            </x-card>

            <x-card class="flex items-center justify-between gap-4">
                <div class="flex flex-col gap-0.5 flex-1">
                    <span class="text-sm font-bold text-neutral-500 uppercase tracking-wide">Categorias</span>
                    <span class="text-2xl font-bold text-neutral-900 mt-1">{{ $stats['categories_count'] }}</span>
                    <span
                        class="text-xs text-neutral-400">{{ $stats['categories_count'] === 1 ? 'categoria' : 'categorias' }}
                        no curso</span>
                </div>
                <div class="shrink-0 w-24 h-24 flex items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-violet-50 flex items-center justify-center text-violet-500">
                        <x-icon name="folder" class="w-8 h-8" />
                    </div>
                </div>
            </x-card>

            <x-card class="flex items-center justify-between gap-4">
                <div class="flex flex-col gap-0.5 flex-1">
                    <span class="text-sm font-bold text-neutral-500 uppercase tracking-wide">Certificados</span>
                    <span class="text-2xl font-bold text-neutral-900 mt-1">{{ $stats['certificates_count'] }}</span>
                    <span
                        class="text-xs text-neutral-400">{{ $stats['certificates_count'] === 1 ? 'certificado enviado' : 'certificados enviados' }}</span>
                </div>
                <div class="shrink-0 w-24 h-24 flex items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                        <x-icon name="inbox-arrow-down" class="w-8 h-8" />
                    </div>
                </div>
            </x-card>
        </div>

        <div class="my-2">
            {{-- aqui vai o card de progresso por categoria --}}
        </div>

        {{-- Card de Certificados --}}
        <x-card class="my-4 !p-0 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                <div>
                    <h2 class="text-lg font-bold text-neutral-900">Certificados</h2>
                    <p class="text-sm text-neutral-500">Certificados cadastrados neste curso</p>
                </div>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('certificates.create', ['course_id' => $activeCourse->id]) }}">
                        <x-icon name="plus" class="w-4 h-4" />
                        Novo Certificado
                    </x-button>
                </div>
            </div>

            @php
                $allCertificates = $activeCourse->categories->flatMap->certificates;
            @endphp

            @if ($allCertificates->isEmpty())
                <div class="px-6">
                    <x-empty-state message="Nenhum certificado cadastrado neste curso." icon="inbox-arrow-down"
                        actionText="Cadastrar certificado" :actionRoute="route('certificates.create', ['course_id' => $activeCourse->id])" />
                </div>
            @else
                <div x-data="{
                    loading: false,
                    selected: [],
                    allIds: {{ Js::from($allCertificates->pluck('id')) }},
                    get allSelected() {
                        return this.allIds.length > 0 && this.selected.length === this.allIds.length;
                    },
                    toggleAll() {
                        this.selected = this.allSelected ? [] : [...this.allIds];
                    },
                    toggleOne(id) {
                        const idx = this.selected.indexOf(id);
                        if (idx > -1) { this.selected.splice(idx, 1); } else { this.selected.push(id); }
                    }
                }">
                    {{-- Barra de ações em massa --}}
                    <div x-show="selected.length > 0" x-cloak
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b border-neutral-200 bg-neutral-50 px-6 py-3">
                        <span class="text-sm font-medium text-neutral-700"
                            x-text="selected.length + ' certificado(s) selecionado(s)'"></span>
                        <form method="POST" action="{{ route('certificates.bulk-download') }}"
                            @submit="loading = true">
                            @csrf
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="certificates[]" :value="id" />
                            </template>
                            <x-button type="submit" color="outline">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                Baixar selecionados (.zip)
                            </x-button>
                        </form>
                    </div>

                    {{-- Cabeçalho --}}
                    <div
                        class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 border-b border-neutral-200 bg-neutral-50 text-xs font-semibold text-neutral-500 uppercase tracking-wide">
                        <div class="col-span-1 flex items-center">
                            <input type="checkbox" @change="toggleAll()" :checked="allSelected"
                                class="rounded border-neutral-300 text-neutral-800 focus:ring-accent cursor-pointer" />
                        </div>
                        <div class="col-span-4">Título</div>
                        <div class="col-span-2">Categoria</div>
                        <div class="col-span-1">Horas</div>
                        <div class="col-span-2">Arquivo</div>
                        <div class="col-span-2 text-right">Ações</div>
                    </div>

                    {{-- Selecionar todos (mobile) --}}
                    <div class="md:hidden flex items-center gap-2 px-6 py-3 border-b border-neutral-100">
                        <input type="checkbox" @change="toggleAll()" :checked="allSelected"
                            class="rounded border-neutral-300 text-neutral-800 focus:ring-accent cursor-pointer" />
                        <span class="text-sm text-neutral-500">Selecionar todos</span>
                    </div>

                    {{-- Linhas --}}
                    <div class="divide-y divide-neutral-100">
                        @foreach ($allCertificates as $certificate)
                            @php
                                $media = $certificate->getFirstMedia('certificate_file');
                                $fileExtension = $media
                                    ? strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION))
                                    : null;
                                $fileSize = $media ? number_format($media->size / 1024, 0, ',', '.') . ' KB' : null;
                            @endphp

                            {{-- Desktop row --}}
                            <div
                                class="hidden md:grid grid-cols-12 gap-4 items-center px-6 py-3 hover:bg-neutral-50 transition-colors">
                                <div class="col-span-1">
                                    <input type="checkbox" :checked="selected.includes({{ $certificate->id }})"
                                        @change="toggleOne({{ $certificate->id }})"
                                        class="rounded border-neutral-300 text-neutral-800 focus:ring-accent cursor-pointer" />
                                </div>
                                <div class="col-span-4">
                                    <span
                                        class="font-medium text-neutral-900 truncate block">{{ $certificate->title }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        {{ $certificate->category->name ?? '—' }}
                                    </span>
                                </div>
                                <div class="col-span-1 text-sm text-neutral-700 font-medium">
                                    {{ number_format($certificate->hours, 1, ',', '.') }}h
                                </div>
                                <div class="col-span-2">
                                    @if ($media)
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-medium {{ $fileExtension === 'PDF' ? 'text-red-500' : 'text-blue-500' }}">
                                            <x-icon name="document" class="w-4 h-4" />
                                            {{ $fileExtension }} · {{ $fileSize }}
                                        </span>
                                    @else
                                        <span class="text-xs text-neutral-400">Sem arquivo</span>
                                    @endif
                                </div>
                                <div class="col-span-2 flex items-center justify-end gap-1">
                                    @if ($media)
                                        <a href="{{ route('certificates.download', $certificate) }}"
                                            class="p-1.5 rounded-lg text-neutral-400 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
                                            title="Baixar">
                                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                        </a>
                                    @endif
                                    <a href="{{ route('certificates.edit', $certificate) }}"
                                        class="p-1.5 rounded-lg text-neutral-400 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
                                        title="Editar">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </a>
                                    <form method="POST" action="{{ route('certificates.destroy', $certificate) }}"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este certificado?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg text-neutral-400 hover:text-red-500 hover:bg-red-50 transition-colors cursor-pointer"
                                            title="Excluir">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Mobile row --}}
                            <div class="md:hidden flex items-start gap-3 px-6 py-4">
                                <input type="checkbox" :checked="selected.includes({{ $certificate->id }})"
                                    @change="toggleOne({{ $certificate->id }})"
                                    class="mt-0.5 rounded border-neutral-300 text-neutral-800 focus:ring-accent cursor-pointer" />
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-neutral-900 truncate">{{ $certificate->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                            {{ $certificate->category->name ?? '—' }}
                                        </span>
                                        <span
                                            class="text-xs text-neutral-500 font-medium">{{ number_format($certificate->hours, 1, ',', '.') }}h</span>
                                        @if ($media)
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-medium {{ $fileExtension === 'PDF' ? 'text-red-500' : 'text-blue-500' }}">
                                                <x-icon name="document" class="w-3.5 h-3.5" />
                                                {{ $fileExtension }} · {{ $fileSize }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 mt-2">
                                        @if ($media)
                                            <a href="{{ route('certificates.download', $certificate) }}"
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
                                                title="Baixar">
                                                <x-icon name="arrow-down-tray" class="w-3.5 h-3.5" />
                                                Baixar
                                            </a>
                                        @endif
                                        <a href="{{ route('certificates.edit', $certificate) }}"
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
                                            title="Editar">
                                            <x-icon name="pencil" class="w-3.5 h-3.5" />
                                            Editar
                                        </a>
                                        <form method="POST"
                                            action="{{ route('certificates.destroy', $certificate) }}"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este certificado?')"
                                            class="ml-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium text-neutral-400 hover:text-red-500 hover:bg-red-50 transition-colors cursor-pointer"
                                                title="Excluir">
                                                <x-icon name="trash" class="w-3.5 h-3.5" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-card>
    @endif
</x-layouts.app>
