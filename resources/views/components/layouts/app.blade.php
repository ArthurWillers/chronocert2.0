<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased bg-neutral-50 text-neutral-900">

    <nav class="bg-white/70 backdrop-blur-sm border-b border-neutral-200 sticky top-0 z-10" x-data="{ mobileOpen: false }">
        <div class=" mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}">
                    <x-app-logo />
                </a>
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-zinc-100 text-zinc-900' : 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100' }}">
                        Dashboard
                    </a>
                    <a href=""
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('certificates.*') ? 'bg-zinc-100 text-zinc-900' : 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100' }}">
                        Meus Certificados
                    </a>
                    <a href=""
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('courses.*') ? 'bg-zinc-100 text-zinc-900' : 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100' }}">
                        Gerenciar Cursos
                    </a>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="hidden md:block">
                    <x-button href="#"><x-icon name="plus" class="size-5" /> Novo Certificado</x-button>
                </div>
                <x-dropdown position="bottom-end" class="ms-auto" accent contentClass="w-60">
                    <x-slot name="trigger">
                        <button class="w-full flex items-center rounded-lg p-1 hover:bg-neutral-800/5 group cursor-pointer gap-2">
                            <div class="shrink-0 border rounded-md p-1 font-medium bg-neutral-200 border-neutral-300">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="ms-auto text-neutral-800/80 group-hover:text-neutral-800">
                                <x-icon name="chevron-down-solid" class="w-4 h-4" />
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="flex items-center gap-2 p-2">
                            <div class="shrink-0 border rounded-md p-1 font-medium bg-neutral-200 border-neutral-300">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="truncate">
                                <div class="text-sm font-semibold text-neutral-800 truncate">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-neutral-500 truncate">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <hr class="my-1 border-neutral-300">
                        <a href="" @click="open = !open"
                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                            <x-icon name="cog-6-tooth" class="w-5 h-5" />
                            Configurações
                        </a>
                        <button type="submit" form="logout" @click="open = !open"
                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                            <x-icon name="arrow-right-start-on-rectangle-solid" class="w-5 h-5" />
                            Sair
                        </button>
                    </x-slot>
                </x-dropdown>

                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden flex items-center justify-center rounded-lg p-1.5 text-neutral-500 hover:bg-neutral-100 hover:text-neutral-900 transition-colors cursor-pointer"
                    :aria-expanded="mobileOpen">
                    <x-icon x-show="!mobileOpen" name="bars-3" class="w-5 h-5" />
                    <x-icon x-show="mobileOpen" name="x-mark" class="w-5 h-5" x-cloak />
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col gap-1">
                <a href="{{ route('dashboard') }}" @click="mobileOpen = false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-zinc-100 text-zinc-900' : 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100' }}">
                    Dashboard
                </a>
                <a href="" @click="mobileOpen = false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('certificates.*') ? 'bg-zinc-100 text-zinc-900' : 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100' }}">
                    Meus Certificados
                </a>
                <a href="" @click="mobileOpen = false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('courses.*') ? 'bg-zinc-100 text-zinc-900' : 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100' }}">
                    Gerenciar Cursos
                </a>
                <hr class="my-1 border-neutral-200">
                <a href="#" class="cursor-pointer inline-flex items-center justify-center w-full font-semibold px-3 py-2 text-sm rounded-lg bg-neutral-800 hover:bg-neutral-700 text-white border border-black/10 shadow-[inset_0px_1px_rgba(255,255,255,0.5)] gap-1">
                    <x-icon name="plus" class="w-4 h-4" /> Novo Certificado
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-6">
        {{ $slot }}
    </main>

    <x-toast />

    <form id="logout" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>
</body>

</html>
