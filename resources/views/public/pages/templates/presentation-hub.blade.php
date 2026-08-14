@php
  use App\Models\CoordinationMember;
  use App\Support\NavigationUrl;

  $isOverview = ($page->slug ?? '') === 'presentation';
  $isCoordination = ($page->slug ?? '') === 'coordination';
  $sidebarItems = collect(config('navigation.main', []))
    ->first(fn (array $item): bool => ($item['section'] ?? null) === 'qui-sommes-nous');
  $sidebarChildren = $sidebarItems['children'] ?? [];
  $coverImage = $page->galleryItems->first();
  $cards = $isCoordination
    ? CoordinationMember::query()->active()->ordered()->get()
    : $page->teamMembers;

  $sidebarIcons = [
    'presentation' => 'fas fa-building',
    'notre-mandat' => 'fas fa-balance-scale',
    'missions-services' => 'fas fa-bullseye',
    'coordination' => 'fas fa-users',
    'partenaires' => 'fas fa-handshake',
    'equipe' => 'fas fa-user-tie',
  ];
@endphp

<section class="comco-presentation">
  <div class="container">
    <div class="row g-4 align-items-start">
      <aside class="col-lg-3">
        <div class="comco-side-nav" data-bs-spy="none">
          <div class="comco-side-nav__brand">
            <span class="comco-side-nav__brand-icon" aria-hidden="true">
              <span class="fas fa-landmark"></span>
            </span>
            <div>
              <p class="comco-side-nav__brand-title mb-0">Présentation</p>
              <p class="comco-side-nav__brand-sub mb-0">COMCO</p>
            </div>
          </div>

          <button
            class="comco-side-nav__toggle d-lg-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#comcoPresentationSideNav"
            aria-expanded="false"
            aria-controls="comcoPresentationSideNav"
          >
            <span>Rubriques</span>
            <span class="fas fa-chevron-down" aria-hidden="true"></span>
          </button>

          <nav
            id="comcoPresentationSideNav"
            class="comco-side-nav__panel collapse d-lg-block"
            aria-label="Sous-menu Présentation"
          >
            <ul class="comco-side-nav__list">
              <li>
                <a
                  class="comco-side-nav__link @if($isOverview) is-active @endif"
                  href="{{ route('sections.show', ['section' => 'qui-sommes-nous', 'slug' => 'presentation']) }}"
                >
                  <span class="{{ $sidebarIcons['presentation'] }}" aria-hidden="true"></span>
                  <span>Présentation</span>
                </a>
              </li>
            </ul>

            <div class="comco-side-nav__divider" role="presentation"></div>

            <ul class="comco-side-nav__list">
              @foreach ($sidebarChildren as $child)
                @php $childSlug = $child['slug'] ?? ''; @endphp
                <li>
                  <a
                    class="comco-side-nav__link @if(($page->slug ?? '') === $childSlug) is-active @endif"
                    href="{{ NavigationUrl::resolveChild(['section' => 'qui-sommes-nous'], $child) }}"
                  >
                    <span class="{{ $sidebarIcons[$childSlug] ?? 'fas fa-circle' }}" aria-hidden="true"></span>
                    <span>{{ $child['label'] }}</span>
                  </a>
                </li>
              @endforeach
            </ul>
          </nav>
        </div>
      </aside>

      <div class="col-lg-9">
        @if ($isOverview)
          <div class="comco-overview">
            <div class="row g-4 align-items-center">
              <div class="col-md-5">
                <div class="comco-overview__media">
                  <img
                    class="comco-overview__image"
                    src="{{ $coverImage ? pageAsset($coverImage->image, $coverImage->image_source) : themeAsset('assets/img/background-2.jpg') }}"
                    alt="{{ $page->title }}"
                  >
                </div>
              </div>
              <div class="col-md-7">
                @if ($page->excerpt)
                  <p class="comco-overview__lead">{{ $page->excerpt }}</p>
                @endif
                <div class="comco-overview__body content-page">
                  {!! $page->body !!}
                </div>
              </div>
            </div>
          </div>
        @else
          @if ($page->excerpt)
            <p class="comco-overview__lead mb-4">{{ $page->excerpt }}</p>
          @elseif ($page->body)
            <div class="content-page mb-4">
              {!! $page->body !!}
            </div>
          @endif

          <div class="row g-4">
            @forelse ($cards as $card)
              @php
                $cardIsCoordination = $card instanceof CoordinationMember;
                $title = $cardIsCoordination ? $card->title : $card->name;
                $summary = $cardIsCoordination ? $card->summary : ($card->text ?: $card->role);
                $image = $card->image;
                $imageSource = $card->image_source ?? 'theme';
                $detailUrl = $cardIsCoordination
                  ? route('coordination.show', $card)
                  : ($card->link_url ?? null);
                $linkLabel = $cardIsCoordination
                  ? ($card->link_label ?: 'En détail')
                  : (($card->link_label ?? null) ?: 'Voir plus');
              @endphp
              <div class="col-md-6 col-xl-4">
                @if ($detailUrl)
                  <a class="comco-lift-card text-decoration-none" href="{{ $detailUrl }}">
                    <article class="comco-lift-card__inner">
                      <div class="comco-lift-card__media">
                        @if ($image)
                          <img src="{{ pageAsset($image, $imageSource) }}" alt="{{ $title }}">
                        @else
                          <div class="comco-lift-card__placeholder" aria-hidden="true">
                            <span class="fas fa-users"></span>
                          </div>
                        @endif
                      </div>
                      <div class="comco-lift-card__body">
                        <h3 class="comco-lift-card__title">{{ $title }}</h3>
                        @if ($summary)
                          <p class="comco-lift-card__summary">{{ \Illuminate\Support\Str::limit(strip_tags($summary), 120) }}</p>
                        @endif
                        <span class="comco-lift-card__cta">{{ $linkLabel }} <span aria-hidden="true">→</span></span>
                      </div>
                    </article>
                  </a>
                @else
                  <article class="comco-lift-card comco-lift-card--static">
                    <div class="comco-lift-card__inner">
                      <div class="comco-lift-card__media">
                        @if ($image)
                          <img src="{{ pageAsset($image, $imageSource) }}" alt="{{ $title }}">
                        @else
                          <div class="comco-lift-card__placeholder" aria-hidden="true">
                            <span class="fas fa-users"></span>
                          </div>
                        @endif
                      </div>
                      <div class="comco-lift-card__body">
                        <h3 class="comco-lift-card__title">{{ $title }}</h3>
                        @if ($summary)
                          <p class="comco-lift-card__summary">{{ \Illuminate\Support\Str::limit(strip_tags($summary), 120) }}</p>
                        @endif
                      </div>
                    </div>
                  </article>
                @endif
              </div>
            @empty
              <div class="col-12">
                <p class="text-500 mb-0">Le contenu de cette rubrique sera publié prochainement.</p>
              </div>
            @endforelse
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
