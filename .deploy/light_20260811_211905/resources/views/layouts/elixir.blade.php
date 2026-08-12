<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? config('institution.name') }}{{ config('institution.seo.titleSuffix') }}</title>
    <meta name="description" content="{{ $metaDescription ?? config('institution.seo.defaultDescription') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/png" href="{{ comcoAsset('ico.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ comcoAsset('ico.png') }}">
    <meta name="theme-color" content="#003DA5">

    <script src="{{ themeAsset('vendors/overlayscrollbars/OverlayScrollbars.min.js') }}"></script>

    <link href="{{ themeAsset('vendors/hamburgers/hamburgers.min.css') }}" rel="stylesheet">
    <link href="{{ themeAsset('vendors/loaders.css/loaders.min.css') }}" rel="stylesheet">
    <link href="{{ themeAsset('assets/css/theme.min.css') }}" rel="stylesheet">
    <link href="{{ themeAsset('assets/css/user.min.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    @stack('styles')
    @livewireStyles
  </head>
  <body>
    <a href="#top" class="visually-hidden-focusable btn btn-primary position-absolute top-0 start-0 m-3">
      Aller au contenu principal
    </a>

    <x-elixir.top-bar />
    <x-elixir.navbar />

    @yield('page-header')

    <main class="main" id="top">
      <div class="preloader" id="preloader">
        <div class="loader">
          <div class="line-scale">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
        </div>
      </div>

      @yield('content')
    </main>

    <x-toast-container />

    <x-elixir.pre-footer />
    <x-elixir.footer />

    <script src="{{ themeAsset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ themeAsset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ themeAsset('vendors/is/is.min.js') }}"></script>
    <script src="{{ themeAsset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ themeAsset('vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ themeAsset('vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ themeAsset('vendors/gsap/gsap.js') }}"></script>
    <script src="{{ themeAsset('vendors/gsap/customEase.js') }}"></script>
    <script src="{{ themeAsset('vendors/bigpicture/BigPicture.js') }}"></script>

    @stack('scripts')

    <script src="{{ themeAsset('assets/js/theme.min.js') }}"></script>
    <script src="{{ themeAsset('assets/js/comco-gallery.js') }}"></script>
    @livewireScripts
    <script src="{{ themeAsset('assets/js/comco-toast.js') }}"></script>
  </body>
</html>
