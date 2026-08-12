@props([
  'title' => '',
  'breadcrumb' => [],
])

<section class="comco-page-header">
  <div
    class="bg-holder overlay"
    style="background-image:url({{ themeAsset('assets/img/background-2.jpg') }});background-position:center bottom;"
  ></div>
  <div class="container">
    <div class="row pt-5 pb-4">
      <div class="col-md-8 text-white">
        <h1 class="text-white fs-4 fs-md-5 mb-0 lh-1">{{ $title }}</h1>
        <nav aria-label="Fil d'Ariane">
          <ol class="breadcrumb fs-1 ps-0 fw-bold mb-0 mt-2">
            <li class="breadcrumb-item">
              <a class="text-white" href="{{ route('home') }}">Accueil</a>
            </li>
            @foreach ($breadcrumb as $crumb)
              @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
              @else
                <li class="breadcrumb-item">
                  @if (isset($crumb['url']))
                    <a class="text-white" href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                  @else
                    <span class="text-white">{{ $crumb['label'] }}</span>
                  @endif
                </li>
              @endif
            @endforeach
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>
