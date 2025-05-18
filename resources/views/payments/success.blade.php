@extends('layouts.app')

@section('title', 'Pago exitoso')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <h2 class="mt-4 text-2xl font-bold text-gray-900">¡Pago Exitoso!</h2>
            <p class="mt-2 text-gray-600">Tu pago ha sido procesado correctamente y tu plan ha sido actualizado a PRO.</p>
            <p class="mt-2 text-gray-600">Ahora tienes acceso a todas las funcionalidades premium.</p>
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
