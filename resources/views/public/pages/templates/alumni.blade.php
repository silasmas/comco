<section class="bg-100">
  <div class="container">
    @if ($page->excerpt)
      <p class="lead text-700 text-center mb-5">{{ $page->excerpt }}</p>
    @endif

    <div class="bg-white px-3 py-4 px-lg-5 rounded-3 content-page mb-6">
      {!! $page->body !!}
    </div>

    <div class="text-center mb-5">
      <h3 class="fs-2 fs-md-3">Équipe & partenaires</h3>
      <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
    </div>

    <div class="row justify-content-center">
      @foreach ([
        ['name' => 'Coordination nationale', 'role' => 'Direction stratégique', 'image' => 'portrait-1.jpg', 'text' => 'Pilotage institutionnel et coordination des actions de la COMCO.'],
        ['name' => 'Collège des analystes', 'role' => 'Analyse économique', 'image' => 'portrait-4.jpg', 'text' => 'Évaluation des concentrations et des pratiques anticoncurrentielles.'],
        ['name' => 'Corps des enquêteurs', 'role' => 'Enquêtes & contrôles', 'image' => 'portrait-6.jpg', 'text' => 'Missions de terrain et collecte des preuves.'],
      ] as $member)
        <div class="col-sm-6 col-lg-4 mt-4">
          <div class="card h-100">
            <img class="card-img-top" src="{{ themeAsset('assets/img/' . $member['image']) }}" alt="{{ $member['name'] }}">
            <div class="card-body">
              <h5>{{ $member['name'] }}</h5>
              <h6 class="fw-normal text-500">{{ $member['role'] }}</h6>
              <p class="py-3 mb-0">{{ $member['text'] }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="bg-primary py-6 text-center text-md-start">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md">
        <h4 class="text-white mb-0">Une question sur nos missions ou nos partenaires ?</h4>
      </div>
      <div class="col-md-auto mt-4 mt-md-0">
        <a class="btn btn-light rounded-pill" href="{{ route('contact') }}">Contactez la COMCO</a>
      </div>
    </div>
  </div>
</section>
