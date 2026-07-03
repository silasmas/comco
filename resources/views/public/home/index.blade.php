@extends('layouts.public')

@push('styles')
  <link href="{{ themeAsset('vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
  <script src="{{ themeAsset('vendors/bigpicture/BigPicture.js') }}"></script>
  <script src="{{ themeAsset('vendors/countup/countUp.umd.js') }}"></script>
  <script src="{{ themeAsset('vendors/swiper/swiper-bundle.min.js') }}"></script>
@endpush

@section('content')
  {{-- Slider hero (index.html) --}}
  <section class="py-0">
    <div class="swiper theme-slider min-vh-100" data-swiper='{"loop":true,"allowTouchMove":false,"autoplay":{"delay":5000},"effect":"fade","speed":800}'>
      <div class="swiper-wrapper">
        @foreach (config('institution.slider') as $slide)
          <div class="swiper-slide" data-zanim-timeline="{}">
            <div class="bg-holder" style="background-image:url({{ comcoAsset($slide['image']) }});"></div>
            <div class="container">
              <div class="row min-vh-100 py-8 align-items-center" data-inertia='{"weight":1.5}'>
                <div class="col-sm-8 col-lg-7 px-5 px-sm-3">
                  <div class="overflow-hidden">
                    <h1 class="fs-4 fs-md-5 lh-1 text-white" data-zanim-xs='{"delay":0}'>{{ $slide['title'] }}</h1>
                  </div>
                  <div class="overflow-hidden">
                    <p class="slide-subtitle text-warning pt-4 mb-5 fs-1 fs-md-2 lh-xs" data-zanim-xs='{"delay":0.1}'>{{ $slide['text'] }}</p>
                  </div>
                  <div class="overflow-hidden">
                    <div data-zanim-xs='{"delay":0.2}'>
                      <a class="btn btn-warning me-3 mt-3" href="{{ route('sections.show', ['section' => 'qui-sommes-nous', 'slug' => 'presentation']) }}">
                        En savoir plus<span class="fas fa-chevron-right ms-2"></span>
                      </a>
                      <a class="btn btn-danger mt-3" href="{{ route('sections.show', ['section' => 'e-services', 'slug' => 'signaler-pratique']) }}">
                        Signaler une pratique<span class="fas fa-exclamation-triangle ms-2"></span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="swiper-nav">
        <div class="swiper-button-prev"><span class="fas fa-chevron-left"></span></div>
        <div class="swiper-button-next"><span class="fas fa-chevron-right"></span></div>
      </div>
    </div>
  </section>

  {{-- Welcome --}}
  <section class="bg-white text-center">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-10 col-md-6">
          <h3 class="fs-2 fs-lg-3">Bienvenue à la {{ config('institution.shortName') }}</h3>
          <p class="px-lg-4 mt-3">{{ config('institution.tagline') }}</p>
          <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
        </div>
      </div>
      <div class="row mt-4 mt-md-5">
        @foreach (config('institution.welcomeItems') as $item)
          <div class="col-sm-6 col-lg-3 mt-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
            <div class="ring-icon mx-auto" data-zanim-xs='{"delay":0}'><span class="{{ $item['icon'] }}"></span></div>
            <h5 class="mt-4" data-zanim-xs='{"delay":0.1}'>{{ $item['title'] }}</h5>
            <p class="mb-0 mt-3 px-3" data-zanim-xs='{"delay":0.2}'>{{ $item['desc'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-8">
          <div class="card shadow-sm comco-alert-box">
            <div class="card-body p-4 p-lg-5">
              <h4 class="text-primary mb-3">Signaler une pratique abusive</h4>
              <p class="mb-0">Toute personne peut signaler à la COMCO les ententes, abus de position dominante, fixation des prix ou pratiques trompeuses. Les signalements sont traités confidentiellement.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 text-lg-end">
          <a class="btn btn-danger btn-lg" href="{{ route('sections.show', ['section' => 'e-services', 'slug' => 'signaler-pratique']) }}">
            Signaler maintenant
          </a>
          <p class="mt-3 mb-0 text-500">
            <span class="fas fa-envelope text-warning me-2"></span>{{ config('institution.contact.email') }}
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- Story + vidéo --}}
  <section class="pt-0">
    <div class="container">
      <div class="row flex-center text-center pb-6">
        <div class="col-12">
          <div class="position-relative mt-4 py-5 py-md-11">
            <div class="bg-holder rounded-3" style="background-image:url({{ comcoAsset(config('institution.latestVideo.image')) }});"></div>
            <button
              class="btn-elixir-play"
              type="button"
              data-bigpicture='{"ytSrc":"{{ config('institution.latestVideo.youtube') }}"}'
              data-zanim-trigger="scroll"
              data-zanim-xs='{"from":{"opacity":0,"scale":0.8},"to":{"opacity":1,"scale":1},"duration":1}'
            >
              <span class="fas fa-play fs-1"></span>
            </button>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach (config('institution.storyItems') as $story)
          <div class="col-sm-6 col-lg-4 mt-3 mt-lg-0 px-4 px-sm-3" data-zanim-timeline="{}" data-zanim-trigger="scroll">
            <h5 data-zanim-xs='{"delay":0}'><span class="text-primary me-3 {{ $story['icon'] }}"></span>{{ $story['title'] }}</h5>
            <p class="mt-3 pe-3 pe-lg-5" data-zanim-xs='{"delay":0.1}'>{{ $story['text'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Missions / services --}}
  <section class="bg-100">
    <div class="container">
      <div class="text-center mb-6">
        <h3 class="fs-2 fs-md-3">Nos missions</h3>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
      </div>
      @foreach (config('institution.services') as $service)
        <div class="row g-0 position-relative mb-4 mb-lg-0">
          <div class="col-lg-6 py-3 py-lg-0 mb-0 position-relative @if($service['reverse'] ?? false) order-lg-2 @endif" style="min-height:400px;">
            <div class="bg-holder rounded-ts-lg rounded-te-lg rounded-lg-te-0 @if($service['reverse'] ?? false) rounded-lg-ts-0 @endif" style="background-image:url({{ comcoAsset($service['image']) }});"></div>
          </div>
          <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 bg-white rounded-bs-lg rounded-lg-bs-0 rounded-be-lg @if($service['reverse'] ?? false) rounded-lg-be-0 @else rounded-lg-be-0 rounded-lg-te-lg @endif">
            <div class="elixir-caret d-none d-lg-block"></div>
            <div class="d-flex align-items-center h-100">
              <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
                <div class="overflow-hidden"><h5 data-zanim-xs='{"delay":0}'>{{ $service['title'] }}</h5></div>
                <div class="overflow-hidden"><p class="mt-3" data-zanim-xs='{"delay":0.1}'>{{ $service['text'] }}</p></div>
                <div class="overflow-hidden">
                  <div data-zanim-xs='{"delay":0.2}'>
                    <a class="d-flex align-items-center" href="{{ route('sections.show', $service['link']) }}">
                      En savoir plus
                      <div class="overflow-hidden ms-2"><span class="d-inline-block" data-zanim-xs='{"from":{"opacity":0,"x":-30},"to":{"opacity":1,"x":0},"delay":0.8}'>&xrarr;</span></div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- Why Choose --}}
  <section>
    <div class="container">
      <div class="text-center mb-7">
        <h3 class="fs-2 fs-md-3">Pourquoi la COMCO</h3>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
      </div>
      <div class="row">
        <div class="col-lg-6 pe-lg-3">
          <img class="rounded-3 img-fluid" src="{{ comcoAsset('img4.jpg') }}" alt="Commission de la Concurrence">
        </div>
        <div class="col-lg-6 px-lg-5 mt-6 mt-lg-0" data-zanim-timeline="{}" data-zanim-trigger="scroll">
          @foreach (config('institution.whyChoose') as $item)
            <div class="overflow-hidden">
              <div class="px-4 px-sm-0 @if(!$loop->first) mt-5 @endif" data-zanim-xs='{"delay":0}'>
                <h5 class="fs-0 fs-lg-1">
                  <span class="{{ $item['icon'] }} fs-1 me-2" data-fa-transform="{{ $item['transform'] }}"></span>{{ $item['title'] }}
                </h5>
                <p class="mt-3">{{ $item['text'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="bg-primary py-6 text-center text-md-start">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md">
          <h4 class="text-white mb-0">Une question sur la concurrence ou les prix ?<br class="d-md-none"> La COMCO est à votre écoute.</h4>
        </div>
        <div class="col-md-auto mt-md-0 mt-4">
          <a class="btn btn-light rounded-pill" href="{{ route('contact') }}">Nous contacter</a>
        </div>
      </div>
    </div>
  </section>

  {{-- Things You Get --}}
  <section class="text-center">
    <div class="container">
      <div class="text-center">
        <h3 class="fs-2 fs-md-3">Nos ressources</h3>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
      </div>
      <div class="row">
        @foreach (config('institution.features') as $feature)
          <div class="col-md-6 col-lg-4 mt-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
            <div class="px-3 py-4 px-lg-4">
              <div class="overflow-hidden"><img src="{{ themeAsset('assets/img/icons/' . $feature['icon']) }}" alt="icon" height="37" data-zanim-xs='{"delay":0}'></div>
              <div class="overflow-hidden"><h5 class="mt-3" data-zanim-xs='{"delay":0.1}'>{{ $feature['title'] }}</h5></div>
              <div class="overflow-hidden"><p class="mb-0" data-zanim-xs='{"delay":0.2}'>{{ $feature['text'] }}</p></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Législation + TALO --}}
  <section class="bg-100">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-body p-4 p-lg-5">
              <h3 class="fs-2 fs-md-3 mb-4">Législation congolaise en matière de concurrence</h3>
              <a class="d-block border rounded-3 p-4 text-decoration-none" href="{{ route('sections.show', ['section' => 'centre-information', 'slug' => 'cadre-juridique']) }}">
                <h5 class="text-primary mb-2">LOI N°18/020 DU 09 JUILLET 2018</h5>
                <p class="mb-0 text-500">Relative à la liberté des prix et à la concurrence</p>
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card h-100 bg-primary text-white">
            <div class="card-body p-4 p-lg-5">
              <h3 class="fs-2 fs-md-3 text-white mb-4">Bientôt disponible</h3>
              <p class="mb-4">L'Application TALO pour la surveillance des prix sur les marchés.</p>
              <img class="img-fluid rounded-3" src="{{ comcoAsset('talo.jpg') }}" alt="Application TALO">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FunFact --}}
  <section>
    <div class="bg-holder overlay overlay-elixir" style="background-image:url({{ themeAsset('assets/img/background-15.jpg') }});"></div>
    <div class="container">
      <div class="d-flex">
        <span class="me-3"><img src="{{ themeAsset('assets/img/checkmark.png') }}" alt="checkmark" style="width: 55px"></span>
        <div class="flex-1">
          <h2 class="text-warning fs-3 fs-lg-4">Agir pour une concurrence loyale,<br><span class="text-white">au service de l'économie congolaise.</span></h2>
          <div class="row mt-4 pe-lg-10">
            @foreach (config('institution.funFacts') as $fact)
              <div class="overflow-hidden col-6 col-md-3" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                <div class="fs-3 fs-lg-4 mb-0 fw-bold text-white mt-lg-5 mt-3 lh-xs" data-zanim-xs='{"delay":0.1}' data-countup='{"endValue":{{ $fact['value'] }}}'>{{ $fact['value'] }}</div>
                <h6 class="fs-0 text-white" data-zanim-xs='{"delay":0.2}'>{{ $fact['label'] }}</h6>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Actualités & activités --}}
  <section class="bg-100">
    <div class="container">
      <div class="text-center mb-6">
        <h3 class="fs-2 fs-md-3">Actualités &amp; activités</h3>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
      </div>
      <ul class="nav nav-tabs justify-content-center border-0 mb-5 comco-tab-pane" role="tablist">
        @foreach (config('institution.homeTabs') as $key => $label)
          <li class="nav-item" role="presentation">
            <button class="nav-link @if($loop->first) active @endif fw-semi-bold border-0" data-bs-toggle="tab" data-bs-target="#pane-{{ $key }}" type="button" role="tab">
              {{ $label }}
            </button>
          </li>
        @endforeach
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="pane-actualite" role="tabpanel">
          @livewire('public.latest-posts', ['variant' => 'elixir'])
        </div>
        <div class="tab-pane fade" id="pane-une" role="tabpanel">
          @php $featured = config('institution.featured'); @endphp
          <div class="card card-featured">
            <div class="row g-0">
              <div class="col-md-5">
                <img class="card-img h-100 object-fit-cover" src="{{ comcoAsset($featured['image']) }}" alt="{{ $featured['title'] }}">
              </div>
              <div class="col-md-7">
                <div class="card-body p-4 p-lg-5">
                  <span class="badge bg-danger mb-2">A la une</span>
                  <h4 class="mb-3">{{ $featured['title'] }}</h4>
                  <p class="mb-0">{{ $featured['text'] }}</p>
                  <p class="text-500 mt-3 mb-0">comco.gouv.cd — {{ $featured['date'] }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="pane-activites" role="tabpanel">
          <div class="row g-4">
            @foreach (config('institution.activities') as $activity)
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-body p-4">
                    <h5>{{ $activity['title'] }}</h5>
                    <p class="mb-0 text-500">{{ $activity['text'] }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Témoignages --}}
  <section class="bg-white">
    <div class="container">
      <div class="text-center mb-5">
        <h3 class="fs-2 fs-md-3">Témoignages</h3>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
      </div>
      <div class="swiper theme-slider" data-swiper='{"loop":true,"slidesPerView":1,"autoplay":{"delay":5000}}'>
        <div class="swiper-wrapper">
          @foreach (config('institution.testimonials') as $testimonial)
            <div class="swiper-slide">
              <div class="row px-lg-8">
                <div class="col-4 col-md-3 mx-auto">
                  <img class="rounded-3 mx-auto img-fluid" src="{{ themeAsset($testimonial['image']) }}" alt="{{ $testimonial['name'] }}">
                </div>
                <div class="col-md-9 mt-4 mt-md-0 px-4 px-sm-3">
                  <p class="lead">{{ $testimonial['quote'] }}</p>
                  <h6 class="fs-0 mb-1 mt-4">{{ $testimonial['name'] }}</h6>
                  <p class="mb-0 text-500">{{ $testimonial['role'] }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="swiper-nav">
          <div class="swiper-button-prev icon-item icon-item-lg"><span class="fas fa-chevron-left fs--2"></span></div>
          <div class="swiper-button-next icon-item icon-item-lg"><span class="fas fa-chevron-right fs--2"></span></div>
        </div>
      </div>
    </div>
  </section>

  {{-- Partenaires --}}
  <div class="bg-200 py-6">
    <div class="container">
      <div class="row align-items-center" data-zanim-timeline="{}" data-zanim-trigger="scroll">
        @foreach (config('institution.partners') as $partner)
          <div class="col-4 col-md-2 my-3 overflow-hidden">
            <img class="img-fluid" src="{{ themeAsset($partner['logo']) }}" alt="{{ $partner['name'] }}" data-zanim-xs="{}">
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
