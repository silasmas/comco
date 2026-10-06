@extends('layouts.public')

@section('content')
  <section class="py-6 py-lg-8">
    <div class="container">
      <div class="row justify-content-center mb-4">
        <div class="col-lg-8 text-center">
          <p class="text-uppercase text-warning fw-bold mb-2" style="letter-spacing: .08em; font-size: .75rem;">
            Applications Android
          </p>
          <h1 class="fs-3 fs-md-2 mb-2">Télécharger une application</h1>
          <p class="text-body-secondary mb-0">Choisissez l’application à installer sur votre téléphone.</p>
        </div>
      </div>

      <div class="row g-4 justify-content-center">
        @foreach ($apps as $app)
          <div class="col-md-6 col-lg-5">
            <div class="border rounded-3 p-4 bg-white h-100 shadow-sm text-center">
              <h2 class="fs-4 mb-2">{{ $app->title }}</h2>
              @if (filled($app->version))
                <p class="text-body-secondary mb-3">Version {{ $app->version }}</p>
              @endif
              <a href="{{ $app->shareUrl() }}" class="btn btn-warning rounded">
                Voir et télécharger
                <span class="fas fa-arrow-right ms-2"></span>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
