@php
  use App\Support\NavigationUrl;
  $footerNav = config('navigation.footer.navigation');
  $footerEServices = config('navigation.footer.eServices');
  $footerQuickLinks = config('navigation.footer.quickLinks');
  $social = array_filter([
    ['label' => 'LinkedIn', 'icon' => 'linkedin-in', 'url' => config('institution.social.linkedin')],
    ['label' => 'Twitter', 'icon' => 'twitter', 'url' => config('institution.social.twitter')],
    ['label' => 'Facebook', 'icon' => 'facebook-f', 'url' => config('institution.social.facebook')],
    ['label' => 'YouTube', 'icon' => 'youtube', 'url' => config('institution.social.youtube')],
  ], fn (array $item): bool => ! empty($item['url']));
@endphp

<section style="background-color: #3D4C6F">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="bg-primary text-white p-5 p-lg-6 rounded-3">
          <h4 class="text-white fs-1 fs-lg-2 mb-1">Restez informé</h4>
          <p class="text-white">Recevez nos actualités, conseils et nouveautés directement par email.</p>
          @livewire('public.newsletter-form')
        </div>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="row">
          <div class="col-6 col-lg-4 text-white">
            <h6 class="text-warning text-uppercase mb-3">Liens rapides</h6>
            <ul class="list-unstyled">
              @foreach ($footerQuickLinks as $item)
                <li class="mb-3">
                  <a class="text-white text-decoration-none" href="{{ NavigationUrl::resolve($item) }}">{{ $item['label'] }}</a>
                </li>
              @endforeach
            </ul>
          </div>
          <div class="col-6 col-lg-4 text-white">
            <h6 class="text-warning text-uppercase mb-3">Navigation</h6>
            <ul class="list-unstyled">
              @foreach ($footerNav as $item)
                <li class="mb-3">
                  <a
                    class="text-white text-decoration-none"
                    href="{{ NavigationUrl::resolve($item) }}"
                    @if(isset($item['url'])) target="_blank" rel="noopener noreferrer" @endif
                  >
                    {{ $item['label'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
          <div class="col-6 col-lg-4 text-white">
            <h6 class="text-warning text-uppercase mb-3">E-Services</h6>
            <ul class="list-unstyled">
              @foreach ($footerEServices as $item)
                <li class="mb-3">
                  <a class="text-white text-decoration-none" href="{{ NavigationUrl::resolve($item) }}">
                    {{ $item['label'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
          @if (count($social) > 0)
            <div class="col-12 col-sm-6 ms-sm-auto mt-4 mt-sm-0">
              <ul class="list-unstyled">
                @foreach ($social as $item)
                  <li class="mb-3">
                    <a class="text-decoration-none d-flex align-items-center" href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer">
                      <span class="brand-icon me-3"><span class="fab fa-{{ $item['icon'] }}"></span></span>
                      <h5 class="fs-0 text-white mb-0 d-inline-block">{{ $item['label'] }}</h5>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
