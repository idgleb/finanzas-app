<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">

<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
        <div class="p-4 text-2xl font-bold border-b border-gray-700">
            Admin Panel
        </div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block hover:bg-gray-700 rounded px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('news.index') }}" class="block hover:bg-gray-700 rounded px-3 py-2">
                Ver noticias
            </a>
            <a href="{{ route('admin.news.index') }}" class="block hover:bg-gray-700 rounded px-3 py-2">
                Administrar noticias
            </a>
            <a href="{{ route('admin.users.index') }}" class="block hover:bg-gray-700 rounded px-3 py-2">
                Usuarios
            </a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="block hover:bg-red-700 rounded px-3 py-2 mt-8 text-red-300">
                Cerrar sesión
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>

</body>
</html>
