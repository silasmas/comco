@php
  use App\Support\NavigationUrl;
  $mainMenu = config('navigation.main');
@endphp

<div class="sticky-top navbar-elixir">
  <div class="container">
    <nav class="navbar navbar-expand-lg align-items-lg-center py-lg-2">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ comcoAsset('logo01.png') }}" alt="{{ config('institution.fullName') }}" class="comco-logo">
      </a>
      <button
        class="navbar-toggler p-0"
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
      <div class="collapse navbar-collapse" id="primaryNavbarCollapse">
        <ul class="navbar-nav py-3 py-lg-0 ms-lg-n1 align-items-lg-center">
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
      </div>
    </nav>
  </div>
</div>
