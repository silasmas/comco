<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 pe-lg-3">
        <img class="rounded-3 img-fluid" src="{{ themeAsset('assets/img/why-choose-us.jpg') }}" alt="{{ $page->title }}">
      </div>
      <div class="col-lg-6 px-lg-5 mt-6 mt-lg-0" data-zanim-timeline="{}" data-zanim-trigger="scroll">
        <div class="overflow-hidden">
          <div class="px-4 px-sm-0" data-zanim-xs='{"delay":0}'>
            <h5 class="fs-0 fs-lg-1">{{ $page->title }}</h5>
            @if ($page->excerpt)
              <p class="mt-3">{{ $page->excerpt }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bg-100">
  <div class="container">
    <div class="bg-white px-3 py-5 px-lg-5 rounded-3 content-page">
      {!! $page->body !!}
    </div>
  </div>
</section>

<section class="bg-100 pb-6">
  <div class="container">
    <div class="row g-0 position-relative mb-4">
      <div class="col-lg-6 py-3 py-lg-0 mb-0 position-relative" style="min-height:400px;">
        <div class="bg-holder rounded-ts-lg rounded-te-lg rounded-lg-te-0" style="background-image:url({{ themeAsset('assets/img/6.jpg') }});"></div>
      </div>
      <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 bg-white rounded-bs-lg rounded-lg-bs-0 rounded-be-lg rounded-lg-te-lg">
        <div class="elixir-caret d-none d-lg-block"></div>
        <h5>Régulation et contrôle</h5>
        <p class="mt-3">La COMCO veille au respect de la législation congolaise en matière de concurrence et mène des actions de contrôle sur les marchés.</p>
      </div>
    </div>
    <div class="row g-0 position-relative">
      <div class="col-lg-6 py-3 py-lg-0 mb-0 position-relative order-lg-2" style="min-height:400px;">
        <div class="bg-holder rounded-ts-lg rounded-te-lg rounded-lg-te-0 rounded-lg-ts-0" style="background-image:url({{ themeAsset('assets/img/7.jpg') }});"></div>
      </div>
      <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 bg-white rounded-bs-lg rounded-lg-bs-0 rounded-be-lg rounded-lg-be-0">
        <div class="elixir-caret d-none d-lg-block"></div>
        <h5>Protection du consommateur</h5>
        <p class="mt-3">Information, alertes et e-services pour signaler les pratiques abusives et les produits dangereux.</p>
      </div>
    </div>
  </div>
</section>
