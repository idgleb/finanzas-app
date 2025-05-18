@extends('layouts.app')

@section('content')
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="display-3 fw-bold text-primary mb-6">Bienvenido a Finanzas</h2>

            <p class="fs-5 text-muted mx-auto mb-6" style="max-width: 720px;">
                Una plataforma sencilla y poderosa para llevar el control de tus ingresos, gastos y decisiones financieras.
                Empezá hoy mismo a ordenar tus cuentas y planificar tu futuro.
            </p>

            <div class="d-flex flex-wrap justify-content-center mb-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-finanzas">Ir al Dashboard</a>
                @else
                    <!-- Espacio muy grande entre botones -->
                    <a href="{{ route('login') }}" class="btn-outline-finanzas me-11 m-9">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="btn-finanzas m-9">Registrarse</a>
                @endauth
            </div>

            <div class="mt-11 mb-11">
                <a href="{{ route('news.index') }}" class="link-finanzas fs-6">
                    Ver novedades del sitio →
                </a>
            </div>
        </div>
    </section>
@endsection
