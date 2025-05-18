<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="text-gray-900 bg-gray-100 min-h-screen font-sans">

<div class="min-h-screen flex flex-col">

    {{-- Navbar --}}
    @include('layouts.navigation')

    {{-- Page Heading --}}
    @isset($header)
        <header class="bg-white shadow-sm py-4">
            <div class="container px-4 page-heading">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Main Content --}}
    <main class="flex-grow flex items-center justify-center px-4">


        <div>
            @if(session('success'))
                <div
                    class="max-w-4xl mx-auto mb-4 bg-green-50 border-t-4 border-green-400 rounded-lg shadow-md p-4 relative"
                    role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <!-- Icono de check -->
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    <!-- Cerrar botón (opcional) -->
                    <button class="absolute top-2 right-2 text-green-400 hover:text-green-600"
                            onclick="this.parentElement.style.display='none'" aria-label="Cerrar">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                    <div
                        class="max-w-4xl mx-auto mb-4 bg-red-50 border-t-4 border-red-400 rounded-lg shadow-md p-4 relative"
                        role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <!-- Icono de exclamación -->
                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8-4a.75.75 0 00-.75.75v3.5a.75.75 0 001.5 0v-3.5A.75.75 0 0010 6zm.75 7.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                        <!-- Cerrar botón (opcional) -->
                        <button class="absolute top-2 right-2 text-red-400 hover:text-red-600"
                                onclick="this.parentElement.style.display='none'" aria-label="Cerrar">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @endif
            @yield('content')
        </div>


    </main>

</div>

</body>
</html>
