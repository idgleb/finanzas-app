@extends('layouts.app')

@section('content')

    <div class="container py-5 ">

        <h2 class="text-center fw-bold text-primary pb-6">Últimas Novedades</h2>


        @forelse ($news as $new)
            <div class="row justify-content-center mb-5">
                <div class="col-md-10 col-lg-8">
                    <div class="card border-0 shadow-lg rounded overflow-hidden">
                        @if ($new->imagen)
                            <img src="{{ asset($new->imagen) }}" class="card-img-top img-fluid" style="object-fit: cover; height: 300px;" alt="Imagen de la novedad">
                        @endif
                        <div class="card-body p-4">
                            <h4 class="card-title mb-3 text-dark">{{ $new->titulo }}</h4>
                            <p class="card-text text-muted">{{ Str::limit($new->contenido, 250) }}</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4">
                            <small class="text-secondary">
                                Publicado el {{ $new->fecha->format('d/m/Y') }}
                                @if ($new->autor)
                                    por <strong>{{ $new->autor->name }}</strong>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-info text-center fs-5">
                        No hay novedades disponibles por el momento.
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
