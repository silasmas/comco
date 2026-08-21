@extends('layouts.public')

@push('styles')
  <link href="{{ themeAsset('vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
  <script src="{{ themeAsset('vendors/bigpicture/BigPicture.js') }}"></script>
  <script src="{{ themeAsset('vendors/swiper/swiper-bundle.min.js') }}"></script>
@endpush

@section('content')
  @if ($homeContent->sectionEnabled('slider'))
    {{-- Slider hero --}}
    <section class="py-0">
      <div class="swiper theme-slider comco-hero" data-swiper='{"loop":true,"allowTouchMove":true,"autoplay":false,"effect":"fade","speed":400}'>
        <div class="swiper-wrapper">
          @foreach ($homeContent->slider() as $slide)
            @php
              $hAlign = \App\Support\HomeSlideStyle::horizontalAlignClasses($slide['content_h_align'] ?? 'start');
              $vAlign = \App\Support\HomeSlideStyle::verticalAlignClass($slide['content_v_align'] ?? 'center');
              $btnShape = \App\Support\HomeSlideStyle::buttonShapeClass($slide['btn_shape'] ?? 'rounded');
              $btnSizeClass = \App\Support\HomeSlideStyle::buttonSizeClass($slide['btn_size'] ?? 'md');
              $btnSizeStyle = \App\Support\HomeSlideStyle::buttonSizeStyle(
                $slide['btn_size'] ?? 'md',
                $slide['btn_size_custom'] ?? null
              );
              $btnPlacement = $slide['btn_placement'] ?? 'after_text';
              $btnAlignClass = \App\Support\HomeSlideStyle::buttonAlignClass(
                $slide['btn_h_align'] ?? 'inherit',
                $slide['content_h_align'] ?? 'start'
              );
              $minHeight = $slide['min_height'] ?? 'default';
              $titleColor = $slide['title_color'] ?? '#ffffff';
              $textColor = $slide['text_color'] ?? '#ffc107';
              $titleFont = ($slide['title_font'] ?? 'inherit') === 'inherit' ? null : $slide['title_font'];
              $textFont = ($slide['text_font'] ?? 'inherit') === 'inherit' ? null : $slide['text_font'];
              $titleSizeCss = \App\Support\HomeSlideStyle::titleSizeCss(
                $slide['title_size'] ?? 'default',
                $slide['title_size_custom'] ?? null
              );
              $textSizeCss = \App\Support\HomeSlideStyle::textSizeCss(
                $slide['text_size'] ?? 'default',
                $slide['text_size_custom'] ?? null
              );
              $hasCustomPrimary = filled($slide['btn_primary_label'] ?? null)
                || filled($slide['btn_primary_url'] ?? null)
                || filled($slide['btn_primary_section'] ?? null);
              $primaryLabel = $hasCustomPrimary
                ? ($slide['btn_primary_label'] ?? null)
                : 'En savoir plus';
              $primaryUrl = $hasCustomPrimary
                ? \App\Support\HomeSlideStyle::buttonUrl($slide, 'btn_primary')
                : route('sections.show', ['section' => 'qui-sommes-nous', 'slug' => 'presentation']);
              $primaryLook = \App\Support\HomeSlideStyle::buttonAppearance($slide, 'btn_primary', 'warning');
              $secondaryLabel = $slide['btn_secondary_label'] ?? null;
              $secondaryUrl = filled($secondaryLabel)
                ? \App\Support\HomeSlideStyle::buttonUrl($slide, 'btn_secondary')
                : null;
              $secondaryLook = \App\Support\HomeSlideStyle::buttonAppearance($slide, 'btn_secondary', 'danger');
              $showActions = filled($primaryLabel) || filled($secondaryLabel);
              $heightStyle = ($minHeight !== 'default' && filled($minHeight))
                ? '--comco-slide-min-h: '.$minHeight.';'
                : '';
              $contentColClass = 'col-sm-8 col-lg-7 px-5 px-sm-3 comco-slide__content '.$hAlign['col'].' '.$hAlign['text'];
              if ($btnPlacement === 'bottom') {
                $contentColClass .= ' comco-slide__content--actions-bottom';
              }
              $titleClass = $titleSizeCss ? 'lh-1 comco-slide__title' : 'fs-4 fs-md-5 lh-1 comco-slide__title';
              $textClass = 'slide-subtitle comco-slide__text pt-4 lh-xs '
                .(($btnPlacement === 'after_text' || $btnPlacement === 'bottom') ? 'mb-5 ' : 'mb-0 ')
                .($textSizeCss ? '' : 'fs-1 fs-md-2');
              $actionsProps = [
                'primaryLabel' => $primaryLabel,
                'primaryUrl' => $primaryUrl,
                'primaryClass' => $primaryLook['class'],
                'primaryStyleAttr' => trim($primaryLook['style'].' '.$btnSizeStyle),
                'secondaryLabel' => $secondaryLabel,
                'secondaryUrl' => $secondaryUrl,
                'secondaryClass' => $secondaryLook['class'],
                'secondaryStyleAttr' => trim($secondaryLook['style'].' '.$btnSizeStyle),
                'btnShape' => $btnShape,
                'btnSizeClass' => $btnSizeClass,
                'btnAlignClass' => $btnAlignClass,
              ];
            @endphp
            <div class="swiper-slide comco-slide" @if($heightStyle !== '') style="{{ $heightStyle }}" @endif>
              <div class="bg-holder" style="background-image:url({{ blockAsset($slide) }});"></div>
              <div class="container">
                <div class="row comco-hero__inner py-6 {{ $vAlign }}">
                  <div class="{{ $contentColClass }}">
                    @if ($showActions && $btnPlacement === 'before_title')
                      <x-home-slide-actions
                        :primary-label="$actionsProps['primaryLabel']"
                        :primary-url="$actionsProps['primaryUrl']"
                        :primary-class="$actionsProps['primaryClass']"
                        :primary-style-attr="$actionsProps['primaryStyleAttr']"
                        :secondary-label="$actionsProps['secondaryLabel']"
                        :secondary-url="$actionsProps['secondaryUrl']"
                        :secondary-class="$actionsProps['secondaryClass']"
                        :secondary-style-attr="$actionsProps['secondaryStyleAttr']"
                        :btn-shape="$actionsProps['btnShape']"
                        :btn-size-class="$actionsProps['btnSizeClass']"
                        :btn-align-class="$actionsProps['btnAlignClass'].' mb-3'"
                      />
                    @endif
                    <div class="overflow-hidden">
                      <h1
                        class="{{ $titleClass }}"
                        style="color: {{ $titleColor }};@if($titleFont) font-family: {{ $titleFont }};@endif@if($titleSizeCss) font-size: {{ $titleSizeCss }};@endif"
                      >{{ $slide['title'] }}</h1>
                    </div>
                    @if ($showActions && $btnPlacement === 'after_title')
                      <x-home-slide-actions
                        :primary-label="$actionsProps['primaryLabel']"
                        :primary-url="$actionsProps['primaryUrl']"
                        :primary-class="$actionsProps['primaryClass']"
                        :primary-style-attr="$actionsProps['primaryStyleAttr']"
                        :secondary-label="$actionsProps['secondaryLabel']"
                        :secondary-url="$actionsProps['secondaryUrl']"
                        :secondary-class="$actionsProps['secondaryClass']"
                        :secondary-style-attr="$actionsProps['secondaryStyleAttr']"
                        :btn-shape="$actionsProps['btnShape']"
                        :btn-size-class="$actionsProps['btnSizeClass']"
                        :btn-align-class="$actionsProps['btnAlignClass'].' mb-4'"
                      />
                    @endif
                    @if (filled($slide['text'] ?? null))
                      <div class="overflow-hidden">
                        <p
                          class="{{ $textClass }}"
                          style="color: {{ $textColor }} !important;@if($textFont) font-family: {{ $textFont }};@endif@if($textSizeCss) font-size: {{ $textSizeCss }};@endif"
                        >{{ $slide['text'] }}</p>
                      </div>
                    @endif
                    @if ($showActions && in_array($btnPlacement, ['after_text', 'bottom'], true))
                      <x-home-slide-actions
                        :primary-label="$actionsProps['primaryLabel']"
                        :primary-url="$actionsProps['primaryUrl']"
                        :primary-class="$actionsProps['primaryClass']"
                        :primary-style-attr="$actionsProps['primaryStyleAttr']"
                        :secondary-label="$actionsProps['secondaryLabel']"
                        :secondary-url="$actionsProps['secondaryUrl']"
                        :secondary-class="$actionsProps['secondaryClass']"
                        :secondary-style-attr="$actionsProps['secondaryStyleAttr']"
                        :btn-shape="$actionsProps['btnShape']"
                        :btn-size-class="$actionsProps['btnSizeClass']"
                        :btn-align-class="$actionsProps['btnAlignClass']"
                        :extra-class="$btnPlacement === 'bottom' ? 'comco-slide__actions-wrap--bottom' : ''"
                      />
                    @endif
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
  @endif

  @if ($homeContent->sectionEnabled('welcome'))
    {{-- Welcome --}}
    <section class="bg-white text-center">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-10 col-md-6">
            <h3 class="fs-2 fs-lg-3">Bienvenue à la {{ config('institution.shortName') }}</h3>
            <p class="px-lg-4 mt-3">{{ $homeContent->tagline() }}</p>
            <hr class="short">
          </div>
        </div>
        <div class="row mt-4 mt-md-5">
          @foreach ($homeContent->welcomeItems() as $item)
            <div class="col-sm-6 col-lg-3 mt-4">
              <div class="ring-icon mx-auto"><span class="{{ $item['icon'] }}"></span></div>
              <h5 class="mt-4">{{ $item['title'] }}</h5>
              <p class="mb-0 mt-3 px-3">{{ $item['desc'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('alert'))
    <section>
      <div class="container">
        <div class="row g-4 align-items-center">
          @php $alert = $homeContent->alertSignalement(); @endphp
          <div class="col-lg-8">
            <div class="card shadow-sm comco-alert-box">
              <div class="card-body p-4 p-lg-5">
                <h4 class="text-primary mb-3">{{ $alert['title'] }}</h4>
                <p class="mb-0">{{ $alert['text'] }}</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 text-lg-end">
            <a class="btn btn-danger btn-lg" href="{{ route('sections.show', ['section' => $alert['button_section'], 'slug' => $alert['button_slug']]) }}">
              {{ $alert['button_label'] }}
            </a>
            <p class="mt-3 mb-0 text-500">
              <span class="fas fa-envelope text-warning me-2"></span>{{ config('institution.contact.email') }}
            </p>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('story'))
    {{-- Story + vidéos (jusqu'à 3) --}}
    <section class="pt-0">
      <div class="container">
        @php
          $homeVideos = $homeContent->latestVideos();
        @endphp
        @if (count($homeVideos) > 0)
          <div class="row flex-center text-center pb-6 g-4">
            @foreach ($homeVideos as $homeVideo)
              @php
                $playback = homeVideoPlayback($homeVideo);
                $poster = blockAsset($homeVideo);
                $colClass = count($homeVideos) === 1 ? 'col-12' : (count($homeVideos) === 2 ? 'col-md-6' : 'col-md-6 col-lg-4');
              @endphp
              <div class="{{ $colClass }}">
                <div class="position-relative mt-4 comco-video-band @if(count($homeVideos) > 1) comco-video-band--grid @endif">
                  @if ($playback['kind'] === 'youtube' && filled($playback['src']))
                    <div class="bg-holder rounded-3" @if(filled($poster)) style="background-image:url({{ $poster }});" @endif></div>
                    <button
                      class="btn-elixir-play"
                      type="button"
                      data-bigpicture='{"ytSrc":"{{ $playback['src'] }}"}'
                      aria-label="Lire la vidéo"
                    >
                      <span class="fas fa-play fs-1"></span>
                    </button>
                  @elseif ($playback['kind'] === 'file' && filled($playback['src']))
                    <video
                      class="comco-home-video rounded-3"
                      controls
                      playsinline
                      preload="metadata"
                      @if(filled($poster)) poster="{{ $poster }}" @endif
                      src="{{ $playback['src'] }}"
                    >
                      Votre navigateur ne prend pas en charge la lecture vidéo.
                    </video>
                  @else
                    <div class="bg-holder rounded-3 comco-video-band__empty" @if(filled($poster)) style="background-image:url({{ $poster }});" @endif></div>
                  @endif
                </div>
                @if (filled($homeVideo['title'] ?? null))
                  <h5 class="mt-3 mb-1">{{ $homeVideo['title'] }}</h5>
                @endif
                @if (filled($homeVideo['text'] ?? null))
                  <p class="mb-0 text-700 px-lg-3">{{ $homeVideo['text'] }}</p>
                @endif
              </div>
            @endforeach
          </div>
        @endif
        <div class="row">
          @foreach ($homeContent->storyItems() as $story)
            <div class="col-sm-6 col-lg-4 mt-3 mt-lg-0 px-4 px-sm-3">
              <h5><span class="text-primary me-3 {{ $story['icon'] }}"></span>{{ $story['title'] }}</h5>
              <p class="mt-3 pe-3 pe-lg-5">{{ $story['text'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('missions'))
    {{-- Missions / services --}}
    <section class="bg-100">
      <div class="container">
        <div class="text-center mb-6">
          <h3 class="fs-2 fs-md-3">Nos missions</h3>
          <hr class="short">
        </div>
        @foreach ($homeContent->services() as $service)
          <div class="row g-0 position-relative mb-4 mb-lg-0">
            <div class="col-lg-6 py-3 py-lg-0 mb-0 position-relative @if($service['reverse'] ?? false) order-lg-2 @endif" style="min-height:240px;">
              <div class="bg-holder rounded-ts-lg rounded-te-lg rounded-lg-te-0 @if($service['reverse'] ?? false) rounded-lg-ts-0 @endif" style="background-image:url({{ blockAsset($service) }});"></div>
            </div>
            <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 bg-white rounded-bs-lg rounded-lg-bs-0 rounded-be-lg @if($service['reverse'] ?? false) rounded-lg-be-0 @else rounded-lg-be-0 rounded-lg-te-lg @endif">
              <div class="elixir-caret d-none d-lg-block"></div>
              <div class="d-flex align-items-center h-100">
                <div>
                  <div class="overflow-hidden"><h5>{{ $service['title'] }}</h5></div>
                  <div class="overflow-hidden"><p class="mt-3">{{ $service['text'] }}</p></div>
                  <div class="overflow-hidden">
                    <div>
                      <a class="d-flex align-items-center" href="{{ route('sections.show', $service['link']) }}">
                        En savoir plus
                        <div class="overflow-hidden ms-2"><span class="d-inline-block">&xrarr;</span></div>
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
  @endif

  @if ($homeContent->sectionEnabled('why_choose'))
    {{-- Why Choose --}}
    <section>
      <div class="container">
        <div class="text-center mb-7">
          <h3 class="fs-2 fs-md-3">Pourquoi la COMCO</h3>
          <hr class="short">
        </div>
        <div class="row">
          <div class="col-lg-6 pe-lg-3">
            @php $whyChooseImage = $homeContent->whyChooseImage(); @endphp
            <img class="rounded-3 img-fluid" src="{{ blockAsset($whyChooseImage) }}" alt="Commission de la Concurrence">
          </div>
          <div class="col-lg-6 px-lg-5 mt-6 mt-lg-0">
            @foreach ($homeContent->whyChoose() as $item)
              <div class="overflow-hidden">
                <div class="px-4 px-sm-0 @if(!$loop->first) mt-5 @endif">
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
  @endif

  @if ($homeContent->sectionEnabled('contact_cta'))
    {{-- CTA --}}
    @php $contactCta = $homeContent->contactCta(); @endphp
    <section class="bg-primary py-6 text-center text-md-start">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md">
            <h4 class="text-white mb-0">{{ $contactCta['title'] }}</h4>
          </div>
          <div class="col-md-auto mt-md-0 mt-4">
            <a class="btn btn-light rounded-pill" href="{{ route($contactCta['button_route']) }}">{{ $contactCta['button_label'] }}</a>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('features'))
    {{-- Things You Get --}}
    <section class="text-center">
      <div class="container">
        <div class="text-center">
          <h3 class="fs-2 fs-md-3">Nos ressources</h3>
          <hr class="short">
        </div>
        <div class="row">
          @foreach ($homeContent->features() as $feature)
            <div class="col-md-6 col-lg-4 mt-4">
              <div class="px-3 py-4 px-lg-4">
                <div class="overflow-hidden"><img src="{{ themeAsset('assets/img/icons/' . $feature['icon']) }}" alt="icon" height="37"></div>
                <div class="overflow-hidden"><h5 class="mt-3">{{ $feature['title'] }}</h5></div>
                <div class="overflow-hidden"><p class="mb-0">{{ $feature['text'] }}</p></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('legislation_talo'))
    {{-- Législation + TALO --}}
    @php
      $legislationPromo = $homeContent->legislationPromo();
      $taloPromo = $homeContent->taloPromo();
    @endphp
    <section class="bg-100">
      <div class="container">
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="card h-100">
              <div class="card-body p-4 p-lg-5">
                <h3 class="fs-2 fs-md-3 mb-4">{{ $legislationPromo['section_title'] }}</h3>
                <a class="d-block border rounded-3 p-4 text-decoration-none" href="{{ route('sections.show', ['section' => $legislationPromo['link_section'], 'slug' => $legislationPromo['link_slug']]) }}">
                  <h5 class="text-primary mb-2">{{ $legislationPromo['law_title'] }}</h5>
                  <p class="mb-0 text-500">{{ $legislationPromo['law_text'] }}</p>
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card h-100 bg-primary text-white">
              <div class="card-body p-4 p-lg-5">
                <h3 class="fs-2 fs-md-3 text-white mb-4">{{ $taloPromo['title'] }}</h3>
                <p class="mb-4">{{ $taloPromo['text'] }}</p>
                <img class="img-fluid rounded-3" src="{{ blockAsset($taloPromo) }}" alt="Application TALO">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('fun_facts'))
    {{-- FunFact --}}
    @php $funFactHeader = $homeContent->funFactHeader(); @endphp
    <section>
      <div class="bg-holder overlay overlay-elixir" style="background-image:url({{ themeAsset('assets/img/background-15.jpg') }});"></div>
      <div class="container">
        <div class="d-flex">
          <span class="me-3"><img src="{{ themeAsset('assets/img/checkmark.png') }}" alt="checkmark" style="width: 55px"></span>
          <div class="flex-1">
            <h2 class="text-warning fs-3 fs-lg-4">{{ $funFactHeader['line_one'] }}<br><span class="text-white">{{ $funFactHeader['line_two'] }}</span></h2>
            <div class="row mt-4 pe-lg-10">
              @foreach ($homeContent->funFacts() as $fact)
                <div class="overflow-hidden col-6 col-md-3">
                  <div class="fs-3 fs-lg-4 mb-0 fw-bold text-white mt-lg-5 mt-3 lh-xs">{{ $fact['value'] }}</div>
                  <h6 class="fs-0 text-white">{{ $fact['label'] }}</h6>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('news'))
    {{-- Actualités & activités --}}
    <section class="bg-100">
      <div class="container">
        <div class="text-center mb-6">
          <h3 class="fs-2 fs-md-3">Actualités &amp; activités</h3>
          <hr class="short">
        </div>
        <ul class="nav nav-tabs justify-content-center border-0 mb-5 comco-tab-pane" role="tablist">
          @foreach ($homeContent->homeTabs() as $key => $label)
            <li class="nav-item" role="presentation">
              <button class="nav-link @if($loop->first) active @endif fw-semi-bold border-0" data-bs-toggle="tab" data-bs-target="#pane-{{ $key }}" type="button" role="tab">
                {{ $label }}
              </button>
            </li>
          @endforeach
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="pane-actualite" role="tabpanel">
            @livewire('public.latest-posts', ['variant' => 'elixir', 'contentType' => 'news'])
          </div>
          <div class="tab-pane fade" id="pane-une" role="tabpanel">
            @php $featured = $homeContent->featured(); @endphp
            <div class="card card-featured">
              <div class="row g-0">
                <div class="col-md-5">
                  <img class="card-img h-100 object-fit-cover" src="{{ blockAsset($featured) }}" alt="{{ $featured['title'] ?? '' }}">
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
            @livewire('public.latest-posts', ['variant' => 'elixir', 'contentType' => 'activity'])
          </div>
        </div>
      </div>
    </section>
  @endif

  @if ($homeContent->sectionEnabled('testimonials'))
    {{-- Témoignages --}}
    <section class="bg-white">
      <div class="container">
        <div class="text-center mb-5">
          <h3 class="fs-2 fs-md-3">Témoignages</h3>
          <hr class="short">
        </div>
        <div class="swiper theme-slider" data-swiper='{"loop":true,"slidesPerView":1,"autoplay":false}'>
          <div class="swiper-wrapper">
            @foreach ($homeContent->testimonials() as $testimonial)
              <div class="swiper-slide">
                <div class="row px-lg-8">
                  <div class="col-4 col-md-3 mx-auto">
                    <img class="rounded-3 mx-auto img-fluid" src="{{ blockAsset($testimonial, 'image', 'theme') }}" alt="{{ $testimonial['name'] ?? '' }}">
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
  @endif

  @if ($homeContent->sectionEnabled('partners'))
    {{-- Partenaires --}}
    <div class="bg-200 py-6">
      <div class="container">
        <div class="row align-items-center">
          @foreach ($homeContent->partners() as $partner)
            <div class="col-4 col-md-2 my-3 overflow-hidden">
              <img class="img-fluid" src="{{ blockAsset($partner, 'logo', 'theme') }}" alt="{{ $partner['name'] ?? '' }}">
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
@endsection
