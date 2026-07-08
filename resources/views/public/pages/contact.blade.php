@extends('layouts.public')

@section('page-header')
  <x-elixir.page-header title="Contact" :breadcrumb="[['label' => 'Contact']]" />
@endsection

@section('content')
  <section class="bg-100">
    <div class="container">
      <div class="row align-items-stretch justify-content-center mb-4">
        <div class="col-lg-4 mb-4 mb-lg-0">
          <div class="card h-100 shadow-sm">
            <div class="card-body px-5">
              <h5 class="mb-3"><span class="fas fa-map-marker-alt text-warning me-2"></span>Adresse</h5>
              <p class="mb-0 text-1100">{{ config('institution.contact.address') }}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mb-4 mb-lg-0">
          <div class="card h-100 shadow-sm">
            <div class="card-body px-5">
              <h5 class="mb-3"><span class="fas fa-envelope text-warning me-2"></span>Email</h5>
              <p class="mb-0">
                <a href="mailto:{{ config('institution.contact.email') }}">{{ config('institution.contact.email') }}</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mb-4 mb-lg-0">
          <div class="card h-100 shadow-sm">
            <div class="card-body px-5">
              <h5 class="mb-3"><span class="fas fa-phone-alt text-warning me-2"></span>Téléphone</h5>
              <p class="mb-0">
                <a href="tel:{{ preg_replace('/\s+/', '', config('institution.contact.phone')) }}">
                  {{ config('institution.contact.phone') }}
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>

      @php
        $provincialOffices = $contactContent->provincialOffices();
        $eServicesCta = $contactContent->eServicesCta();
      @endphp

      <div class="row g-4 mb-4">
        <div class="col-lg-6">
          <div class="card shadow-sm h-100">
            <div class="card-body p-4 p-lg-5">
              <h5 class="mb-3">{{ $provincialOffices['title'] }}</h5>
              <p class="text-500 mb-0">{{ $provincialOffices['text'] }}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card shadow-sm h-100 bg-primary text-white">
            <div class="card-body p-4 p-lg-5">
              <h5 class="text-white mb-3">{{ $eServicesCta['title'] }}</h5>
              <p class="mb-4">{{ $eServicesCta['text'] }}</p>
              <a class="btn btn-warning me-2 mb-2" href="{{ route('sections.show', ['section' => 'e-services', 'slug' => 'deposer-fusion']) }}"><span class="text-primary fw-semi-bold">Fusion</span></a>
              <a class="btn btn-warning me-2 mb-2" href="{{ route('sections.show', ['section' => 'e-services', 'slug' => 'plainte-consommateur']) }}"><span class="text-primary fw-semi-bold">Plainte</span></a>
              <a class="btn btn-light mb-2" href="{{ route('sections.show', ['section' => 'e-services', 'slug' => 'signaler-pratique']) }}">Signalement</a>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4 pb-0">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h5 class="mb-0">Localisation</h5>
            <a class="btn btn-sm btn-warning" href="{{ config('institution.contact.mapLinkUrl') }}" target="_blank" rel="noopener noreferrer">
              <span class="text-primary fw-semi-bold">Ouvrir dans Google Maps</span>
            </a>
          </div>
          <p class="text-500 small mb-3">{{ config('institution.contact.address') }}</p>
        </div>
        <iframe
          title="Localisation COMCO — {{ config('institution.contact.address') }}"
          src="{{ config('institution.contact.mapEmbedUrl') }}"
          width="100%"
          height="450"
          style="border:0;"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen
        ></iframe>
      </div>

      <div class="card shadow-sm">
        <div class="card-body h-100 p-4 p-lg-5">
          <h5 class="mb-4">Écrivez-nous</h5>
          @livewire('public.contact-form')
        </div>
      </div>
    </div>
  </section>
@endsection
