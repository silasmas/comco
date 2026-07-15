@php
  use App\Support\NavigationUrl;
  $mainMenu = config('navigation.main');
@endphp

<div class="sticky-top navbar-elixir">
  <div class="container">
    <nav class="navbar navbar-expand-lg align-items-lg-center py-lg-2">
      <div class="d-flex align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
          <img src="{{ comcoAsset('logo01.png') }}" alt="{{ config('institution.fullName') }}" class="comco-logo">
        </a>
        <a
          class="comco-ministry-link comco-ministry-link--mobile d-lg-none"
          href="{{ config('institution.ministry.url') }}"
          target="_blank"
          rel="noopener noreferrer"
          title="{{ config('institution.ministry.name') }}"
        >
          <img
            src="{{ comcoAsset(config('institution.ministry.logo')) }}"
            alt="{{ config('institution.ministry.name') }}"
            class="comco-ministry-logo"
          >
        </a>
      </div>
      <button
        class="navbar-toggler p-0 ms-auto"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#primaryNavbarCollapse"
        aria-controls="primaryNavbarCollapse"
        aria-expanded="false"
        aria-label="Ouvrir le menu"
      >
        <span class="hamburger hamburger--emphatic">
          <span class="hamburger-box">
            <span class="hamburger-inner"></span>
          </span>
        </span>
      </button>
      <div class="collapse navbar-collapse align-items-lg-center" id="primaryNavbarCollapse">
        <ul class="navbar-nav py-3 py-lg-0 ms-lg-n1 me-lg-auto align-items-lg-center">
          @foreach ($mainMenu as $item)
            <li class="nav-item @if(isset($item['children'])) dropdown @endif">
              @if (isset($item['children']))
                <a
                  class="nav-link dropdown-toggle dropdown-indicator"
                  href="javascript:void(0)"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  {{ $item['label'] }}
                </a>
                <ul class="dropdown-menu">
                  @foreach ($item['children'] as $child)
                    <li>
                      <a class="dropdown-item" href="{{ NavigationUrl::resolveChild($item, $child) }}">
                        {{ $child['label'] }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @else
                <a
                  class="nav-link @if(isset($item['route']) && request()->routeIs($item['route'])) active @endif"
                  href="{{ NavigationUrl::resolve($item) }}"
                >
                  {{ $item['label'] }}
                </a>
              @endif
            </li>
          @endforeach
        </ul>
        <a
          class="comco-ministry-link comco-ministry-link--desktop d-none d-lg-inline-flex"
          href="{{ config('institution.ministry.url') }}"
          target="_blank"
          rel="noopener noreferrer"
          title="{{ config('institution.ministry.name') }}"
        >
          <img
            src="{{ comcoAsset(config('institution.ministry.logo')) }}"
            alt="{{ config('institution.ministry.name') }}"
            class="comco-ministry-logo"
          >
        </a>
      </div>
    </nav>
  </div>
</div>
