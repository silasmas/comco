<section class="bg-100">
  <div class="container">
    @if ($page->excerpt)
      <p class="lead text-700 text-center mb-5">{{ $page->excerpt }}</p>
    @endif

    <div class="bg-white px-3 py-4 px-lg-5 rounded-3 content-page mb-6">
      {!! $page->body !!}
    </div>

    @if ($page->teamMembers->isNotEmpty())
      <div class="text-center mb-5">
        <h3 class="fs-2 fs-md-3">Équipe & partenaires</h3>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
      </div>

      <div class="row justify-content-center">
        @foreach ($page->teamMembers as $member)
          <div class="col-sm-6 col-lg-4 mt-4">
            <div class="card h-100">
              @if ($member->image)
                <img class="card-img-top" src="{{ pageAsset($member->image, $member->image_source) }}" alt="{{ $member->name }}">
              @endif
              <div class="card-body">
                <h5>{{ $member->name }}</h5>
                @if ($member->role)
                  <h6 class="fw-normal text-500">{{ $member->role }}</h6>
                @endif
                @if ($member->text)
                  <p class="py-3 mb-0">{{ $member->text }}</p>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
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
