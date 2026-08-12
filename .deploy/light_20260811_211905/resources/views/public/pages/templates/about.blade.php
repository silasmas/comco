<section class="bg-100">
  <div class="container">
    <div class="row g-0 mb-6">
      <div class="col-lg-4 py-3 py-lg-0 position-relative" style="min-height:400px;">
        <div class="bg-holder rounded-ts-lg rounded-lg-bs-lg rounded-te-lg rounded-lg-te-0" style="background-image:url({{ themeAsset('assets/img/ceo.jpg') }});"></div>
      </div>
      <div class="col-lg-8 px-5 py-6 my-lg-0 bg-white rounded-lg-te-lg rounded-be-lg rounded-bs-lg rounded-lg-bs-0 d-flex align-items-center">
        <div>
          <h5>{{ config('institution.fullName') }}</h5>
          @if ($page->excerpt)
            <p class="my-4">{{ $page->excerpt }}</p>
          @endif
          <p class="mb-0 fw-medium text-primary">{{ config('institution.shortName') }} — République Démocratique du Congo</p>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <h3 class="text-center fs-2 fs-md-3">{{ $page->title }}</h3>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
      </div>
      <div class="col-12">
        <div class="bg-white px-3 mt-4 px-lg-5 py-5 rounded-3 content-page">
          {!! $page->body !!}
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bg-primary py-6 text-center text-md-start">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md">
        <h4 class="text-white mb-0">Pour des marchés libres, transparents et équitables.</h4>
      </div>
      <div class="col-md-auto mt-4 mt-md-0">
        <a class="btn btn-light rounded-pill" href="{{ route('sections.show', ['section' => 'centre-information', 'slug' => 'cadre-juridique']) }}">Cadre juridique</a>
      </div>
    </div>
  </div>
</section>
