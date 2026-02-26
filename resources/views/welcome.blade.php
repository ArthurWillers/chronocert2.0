<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-neutral-50 antialiased flex flex-col">

    {{-- Header --}}
    <header class="w-full border-b border-neutral-200 bg-white/80 backdrop-blur-sm sticky top-0 z-10">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-3">
            <a href="/">
                <x-app-logo />
            </a>
            <nav class="flex items-center gap-2">
                <x-button href="{{ route('login') }}" color="outline">Entrar</x-button>
                <x-button href="{{ route('register') }}" color="default">Criar conta</x-button>
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="flex-1 mx-auto w-full max-w-5xl px-6 py-8 flex flex-col items-center text-center gap-4">
        <x-app-logo-icon class="w-20" />
        <h1 class="text-4xl font-bold tracking-tight text-neutral-900 sm:text-5xl">
            Seus certificados,<br class="hidden sm:block"> organizados de verdade.
        </h1>
        <p class="max-w-xl text-lg text-neutral-500 leading-relaxed">
            O ChronoCert é uma plataforma para gerenciamento de certificados de atividades complementares. Crie suas
            próprias categorias, faça upload dos certificados e acompanhe suas horas — tudo em um só lugar.
        </p>
        <div class="flex items-center gap-3">
            <x-button href="{{ route('register') }}" color="default" class="px-5 py-2.5 text-base">
                Começar agora
            </x-button>
            <x-button href="{{ route('login') }}" color="outline" class="px-5 py-2.5 text-base">
                Já tenho conta
            </x-button>
        </div>
    </section>

    {{-- Features --}}
    <section class="mx-auto max-w-5xl px-6 pb-8">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-xl border border-neutral-200 bg-white p-6 flex flex-col gap-3 shadow-sm">
                <div class="flex size-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                </div>
                <h3 class="font-semibold text-neutral-900">Upload fácil</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Envie seus certificados em PDF diretamente pela plataforma, de forma rápida e segura.</p>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 flex flex-col gap-3 shadow-sm">
                <div class="flex size-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-neutral-900">Categorias personalizadas</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Crie categorias com limite de horas de acordo com as exigências do seu curso.</p>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 flex flex-col gap-3 shadow-sm">
                <div class="flex size-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-neutral-900">Controle de horas</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Acompanhe o progresso de horas em cada categoria e veja o quanto falta para completar.</p>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 flex flex-col gap-3 shadow-sm">
                <div class="flex size-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
                <h3 class="font-semibold text-neutral-900">Download em lote</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Exporte todos os seus certificados de uma vez em um único arquivo ZIP para entrega.</p>
            </div>

        </div>
    </section>

    <x-guest-footer />

</body>

</html>
