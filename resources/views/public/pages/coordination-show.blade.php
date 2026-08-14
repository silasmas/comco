@extends('layouts.public')

@section('page-header')
  <x-elixir.page-title :title="$member->title" />
@endsection

@section('content')
  <section class="comco-coord-detail">
    <div class="container">
      <div class="mb-4">
        <a class="comco-coord-detail__back" href="{{ route('sections.show', ['section' => 'qui-sommes-nous', 'slug' => 'coordination']) }}">
          <span class="fas fa-arrow-left me-2" aria-hidden="true"></span>Retour à la Coordination
        </a>
      </div>

      <div class="comco-coord-detail__panel">
        <div class="row g-4 g-lg-5 align-items-start">
          <div class="col-md-4">
            <div class="comco-coord-detail__media">
              @if ($member->image)
                <img
                  src="{{ pageAsset($member->image, $member->image_source ?? 'theme') }}"
                  alt="{{ $member->title }}"
                >
              @else
                <div class="comco-coord-detail__placeholder" aria-hidden="true">
                  <span class="fas fa-users"></span>
                </div>
              @endif
            </div>
          </div>
          <div class="col-md-8">
            <p class="comco-coord-detail__eyebrow mb-2">Coordination</p>
            <h2 class="comco-coord-detail__title">{{ $member->title }}</h2>
            @if ($member->summary)
              <p class="comco-coord-detail__summary">{{ $member->summary }}</p>
            @endif
            @if ($member->body)
              <div class="comco-coord-detail__body content-page">
                {!! str_contains((string) $member->body, '<') ? $member->body : nl2br(e($member->body)) !!}
              </div>
            @endif
            @if ($member->link_url)
              <a class="btn btn-primary mt-4" href="{{ $member->link_url }}" target="_blank" rel="noopener noreferrer">
                {{ $member->link_label ?: 'En savoir plus' }}
              </a>
            @endif
          </div>
        </div>
      </div>

      @if ($related->isNotEmpty())
        <div class="mt-5">
          <h3 class="comco-coord-detail__related-title">Autres fiches</h3>
          <div class="row g-4 mt-1">
            @foreach ($related as $item)
              <div class="col-md-4">
                <a class="comco-lift-card text-decoration-none" href="{{ route('coordination.show', $item) }}">
                  <article class="comco-lift-card__inner">
                    <div class="comco-lift-card__media">
                      @if ($item->image)
                        <img src="{{ pageAsset($item->image, $item->image_source ?? 'theme') }}" alt="{{ $item->title }}">
                      @else
                        <div class="comco-lift-card__placeholder" aria-hidden="true">
                          <span class="fas fa-users"></span>
                        </div>
                      @endif
                    </div>
                    <div class="comco-lift-card__body">
                      <h3 class="comco-lift-card__title">{{ $item->title }}</h3>
                      @if ($item->summary)
                        <p class="comco-lift-card__summary">{{ \Illuminate\Support\Str::limit(strip_tags($item->summary), 90) }}</p>
                      @endif
                      <span class="comco-lift-card__cta">En détail <span aria-hidden="true">→</span></span>
                    </div>
                  </article>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </section>
@endsection
