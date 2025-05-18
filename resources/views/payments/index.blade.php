@extends('layouts.app')

@section('title', 'Pagar Plan PRO')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Pagar Plan PRO</h1>

        <form action="{{ route('payments.process') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Pagar ahora $9.99
            </button>
        </form>
    </div>
@endsection
