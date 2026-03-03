<x-layouts.app>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <x-button href="{{ route('dashboard', $certificate->category->course->id ?? '') }}" color="none"
                class="text-neutral-500 hover:text-neutral-700">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Voltar
            </x-button>
        </div>

        <x-card>
            <h1 class="text-2xl font-bold text-neutral-900 mb-1">Editar Certificado</h1>
            <p class="text-sm text-neutral-500 mb-6">Atualize as informações ou o arquivo do certificado.</p>

            <form method="POST" action="{{ route('certificates.update', $certificate) }}" enctype="multipart/form-data"
                x-data="{
                    loading: false,
                    courseId: '{{ old('course_id', $certificate->category->course->id ?? '') }}',
                    courses: {{ Js::from($courses) }},
                    fileName: '',
                    hasExistingFile: {{ $certificate->getFirstMedia('certificate_file') ? 'true' : 'false' }},
                    get categories() {
                        if (!this.courseId) return [];
                        const course = this.courses.find(c => c.id == this.courseId);
                        return course ? course.categories : [];
                    },
                    handleFile(event) {
                        const file = event.target.files[0];
                        this.fileName = file ? file.name : '';
                    }
                }" @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Curso --}}
                    <div class="grid w-full items-center gap-1.5">
                        <label for="course_id" class="inline-flex items-center text-sm font-semibold text-neutral-700">
                            Curso
                        </label>
                        <select name="course_id" id="course_id" x-model="courseId"
                            class="w-full border text-sm rounded-xl block py-2.5 px-4 bg-white shadow-xs focus:shadow-lg text-neutral-700 outline-none focus:border-accent focus:ring-2 focus:ring-accent/40 transition-colors duration-300 border-neutral-200">
                            <option value="">Selecione um curso</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Categoria --}}
                    <div class="grid w-full items-center gap-1.5">
                        <label for="category_id"
                            class="inline-flex items-center text-sm font-semibold text-neutral-700">
                            Categoria
                        </label>
                        <select name="category_id" id="category_id"
                            class="w-full border text-sm rounded-xl block py-2.5 px-4 bg-white shadow-xs focus:shadow-lg text-neutral-700 outline-none focus:border-accent focus:ring-2 focus:ring-accent/40 transition-colors duration-300 border-neutral-200 disabled:bg-neutral-100 disabled:text-neutral-400"
                            :disabled="!courseId">
                            <option value="">Selecione uma categoria</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"
                                    :selected="cat.id == '{{ old('category_id', $certificate->category_id) }}'">
                                </option>
                            </template>
                        </select>
                        <x-form-error name="category_id" />
                    </div>

                    {{-- Título --}}
                    <x-form-input label="Título do Certificado" name="title"
                        placeholder="Ex: Curso de Programação Web" :value="old('title', $certificate->title)" />
                    <x-form-error name="title" />

                    {{-- Horas --}}
                    <x-form-input label="Carga Horária" name="hours" placeholder="Ex: 40" :value="old('hours', $certificate->hours)" numeric />
                    <x-form-error name="hours" />

                    {{-- Arquivo atual --}}
                    @php $currentMedia = $certificate->getFirstMedia('certificate_file'); @endphp
                    @if ($currentMedia)
                        <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-200">
                            <x-icon name="document" class="w-5 h-5 text-neutral-500" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-700 truncate">
                                    {{ $currentMedia->file_name }}</p>
                                <p class="text-xs text-neutral-400">
                                    {{ strtoupper(pathinfo($currentMedia->file_name, PATHINFO_EXTENSION)) }} ·
                                    {{ number_format($currentMedia->size / 1024, 0, ',', '.') }} KB
                                </p>
                            </div>
                            <a href="{{ route('certificates.download', $certificate) }}"
                                class="p-1.5 rounded-lg text-neutral-400 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
                                title="Baixar">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            </a>
                        </div>
                    @endif

                    {{-- Upload --}}
                    <div class="grid w-full items-center gap-1.5">
                        <label class="inline-flex items-center text-sm font-semibold text-neutral-700">
                            {{ $currentMedia ? 'Substituir arquivo (opcional)' : 'Arquivo do Certificado' }}
                        </label>
                        <label
                            class="flex flex-col items-center justify-center w-full py-8 border-2 border-dashed rounded-xl cursor-pointer transition-colors border-neutral-300 hover:border-neutral-400 hover:bg-neutral-50">
                            <div class="flex flex-col items-center justify-center" x-show="!fileName">
                                <x-icon name="arrow-up-tray" class="w-8 h-8 text-neutral-400 mb-2" />
                                <p class="text-sm font-medium text-neutral-600">Clique para enviar</p>
                                <p class="text-xs text-neutral-400 mt-1">PDF, JPG, PNG ou WEBP (máx. 10MB)</p>
                            </div>
                            <div class="flex items-center gap-2" x-show="fileName" x-cloak>
                                <x-icon name="document" class="w-6 h-6 text-green-500" />
                                <span class="text-sm font-medium text-neutral-700" x-text="fileName"></span>
                            </div>
                            <input type="file" name="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.webp"
                                @change="handleFile($event)" />
                        </label>
                        <x-form-error name="file" />
                    </div>
                </div>

                {{-- Botão Submit --}}
                <div class="mt-8 flex justify-end gap-3">
                    <x-button href="{{ route('dashboard', $certificate->category->course->id ?? '') }}"
                        color="outline">
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
