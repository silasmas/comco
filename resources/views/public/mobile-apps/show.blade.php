@extends('layouts.public')

@section('content')
  <section class="py-6 py-lg-8">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
          <div class="text-center mb-4">
            <p class="text-uppercase text-warning fw-bold mb-2" style="letter-spacing: .08em; font-size: .75rem;">
              Application Android
            </p>
            <h1 class="fs-3 fs-md-2 mb-2">{{ $app->title }}</h1>
            @if (filled($app->version))
              <p class="text-body-secondary mb-0">Version {{ $app->version }}</p>
            @endif
            @if ($app->humanFileSize())
              <p class="text-body-secondary mt-1 mb-0">Taille : {{ $app->humanFileSize() }}</p>
            @endif
          </div>

          <div class="border rounded-3 p-4 p-md-5 bg-white text-center shadow-sm">
            <p class="mb-4">Scannez ce QR code ou utilisez le bouton ci-dessous pour télécharger l’APK.</p>

            <img
              src="{{ $app->qrCodeImageUrl(260) }}"
              alt="QR code {{ $app->title }}"
              width="260"
              height="260"
              class="img-fluid mb-4 border rounded bg-white p-2"
            >

            <div class="d-grid gap-2 col-md-8 mx-auto">
              <a href="{{ $app->downloadUrl() }}" class="btn btn-warning btn-lg rounded">
                Télécharger l’APK
                <span class="fas fa-download ms-2"></span>
              </a>
            </div>

            <p class="small text-body-secondary mt-4 mb-0">
              Sur Android, autorisez l’installation depuis des sources inconnues si demandé.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
