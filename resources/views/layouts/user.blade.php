<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mis Finanzas')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">

<nav class="bg-white shadow p-4 flex justify-between">
    <div class="text-xl font-bold">Mi Finanzas</div>
    <div class="space-x-4">
        <a href="{{ route('movements.index') }}">Movimientos</a>
        <a href="{{ route('categories.index') }}">Categorías</a>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Cerrar sesión
        </a>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
</nav>

<main class="p-6">
    @yield('content')
</main>

</body>
</html>
