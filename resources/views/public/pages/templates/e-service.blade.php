<section class="bg-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        @if ($page->excerpt)
          <p class="lead text-700 mb-4">{{ $page->excerpt }}</p>
        @endif
        <article class="card shadow-sm mb-4">
          <div class="card-body p-4 p-lg-5 content-page">
            {!! $page->body !!}
          </div>
        </article>

        @if ($hasEServiceForm ?? false)
          <div class="card shadow-sm border-warning mb-4">
            <div class="card-body p-4 p-lg-5">
              <h4 class="text-primary mb-1">{{ $serviceConfig['label'] ?? $page->title }}</h4>
              @livewire('public.e-service-form', ['serviceSlug' => $page->slug], key('eservice-' . $page->slug))
            </div>
          </div>
        @endif
      </div>

      <div class="col-lg-4">
        <div class="card shadow-sm bg-primary text-white mb-4">
          <div class="card-body p-4">
            <h5 class="text-white">Informations utiles</h5>
            <p class="mb-3">Complétez le formulaire avec le maximum de détails. Un agent COMCO vous répondra dans les meilleurs délais.</p>
            <p class="mb-0 small">Email : {{ config('institution.contact.email') }}</p>
          </div>
        </div>
        <div class="card shadow-sm bg-primary text-white">
          <div class="card-body p-4">
            <h5 class="text-white">Signalement urgent</h5>
            <p class="mb-3">Pour un signalement confidentiel de pratique anticoncurrentielle.</p>
            <a class="btn btn-light btn-sm" href="{{ route('sections.show', ['section' => 'e-services', 'slug' => 'signaler-pratique']) }}">En savoir plus</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
