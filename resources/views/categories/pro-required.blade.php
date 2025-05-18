@extends('layouts.app')

@section('title', 'Plan PRO Requerido')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">¡Desbloquea el {{ $plan->name }}!</h1>
            <p class="text-lg text-gray-600">{{ $plan->description }}</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Características del Plan PRO -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold mb-4">Beneficios del {{ $plan->name }}</h2>
                <ul class="space-y-3">
                    @foreach($plan->features as $feature)
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Formulario de pago -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Obtén el {{ $plan->name }}</h2>
                <p class="text-3xl font-bold text-gray-900 mb-4">${{ number_format($plan->price, 2) }}<span class="text-lg text-gray-600"></span></p>

                <form action="{{ route('payments.process') }}" method="POST" class="space-y-4">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        Actualizar a PRO
                    </button>
                </form>

                <p class="text-sm text-gray-500 mt-4">
                    Pago seguro a través de Mercado Pago
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
