<x-layouts.app>
    <div class="border-b border-neutral-200 mb-4 pb-4">
        <h1 class="text-2xl font-bold text-neutral-900">Visão Geral</h1>
        <p class="text-neutral-500">Selecione o curso para verificar o seu progresso</p>
        @unless ($courses->isEmpty())
            <div class="my-4">
                {{-- aqui vai os botoes de escolha de curso --}}
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

        <div class="my-2">
            {{-- aqui vai o card de certificados --}}
        </div>
    @endif
</x-layouts.app>
