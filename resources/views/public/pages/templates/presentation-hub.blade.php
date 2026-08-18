@php
  use App\Models\CoordinationMember;
  use App\Support\NavigationUrl;
  use App\Support\PageSidebar;

  $sidebar = PageSidebar::forPage($page);
  $sectionKey = $sidebar['section'] ?? ($page->section ?? '');
  $hubSlug = $sidebar['hubSlug'] ?? null;
  $sidebarLabel = $sidebar['label'] ?? ($page->title ?? 'Rubriques');
  $sidebarChildren = $sidebar['children'] ?? [];
  $isOverview = $hubSlug !== null && ($page->slug ?? '') === $hubSlug;
  $isCoordination = ($page->slug ?? '') === 'coordination';
  $coverImage = $page->galleryItems->first();
  $cards = $isCoordination
    ? CoordinationMember::query()->active()->ordered()->get()
    : $page->teamMembers;
  $showText = $page->showsContent();
  $showPdf = $page->showsPdf();
  $pdfDocuments = $showPdf ? $page->legalDocuments : collect();

  $sidebarIcons = [
    'presentation' => 'fas fa-building',
    'notre-mandat' => 'fas fa-balance-scale',
    'missions-services' => 'fas fa-bullseye',
    'coordination' => 'fas fa-users',
    'partenaires' => 'fas fa-handshake',
    'equipe' => 'fas fa-user-tie',
    'cadre-juridique' => 'fas fa-gavel',
    'decrets' => 'fas fa-file-alt',
    'documentation-diverse' => 'fas fa-folder-open',
    'actualites' => 'fas fa-newspaper',
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
              <p class="comco-side-nav__brand-title mb-0">{{ $sidebarLabel }}</p>
              <p class="comco-side-nav__brand-sub mb-0">COMCO</p>
            </div>
          </div>

          <button
            class="comco-side-nav__toggle d-lg-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#comcoPageSideNav"
            aria-expanded="false"
            aria-controls="comcoPageSideNav"
          >
            <span>Rubriques</span>
            <span class="fas fa-chevron-down" aria-hidden="true"></span>
          </button>

          <nav
            id="comcoPageSideNav"
            class="comco-side-nav__panel collapse d-lg-block"
            aria-label="Sous-menu {{ $sidebarLabel }}"
          >
            @if ($hubSlug && filled($sectionKey))
              <ul class="comco-side-nav__list">
                <li>
                  <a
                    class="comco-side-nav__link{{ $isOverview ? ' is-active' : '' }}"
                    href="{{ route('sections.show', ['section' => $sectionKey, 'slug' => $hubSlug]) }}"
                  >
                    <span class="{{ $sidebarIcons[$hubSlug] ?? 'fas fa-home' }}" aria-hidden="true"></span>
                    <span>{{ $sidebarLabel }}</span>
                  </a>
                </li>
              </ul>

              @if (count($sidebarChildren) > 0)
                <div class="comco-side-nav__divider" role="presentation"></div>
              @endif
            @endif

            <ul class="comco-side-nav__list">
              @forelse ($sidebarChildren as $child)
                @php $childSlug = $child['slug'] ?? ''; @endphp
                <li>
                  <a
                    class="comco-side-nav__link{{ ($page->slug ?? '') === $childSlug ? ' is-active' : '' }}"
                    href="{{ NavigationUrl::resolveChild(['section' => $sectionKey], $child) }}"
                  >
                    <span class="{{ $sidebarIcons[$childSlug] ?? 'fas fa-circle' }}" aria-hidden="true"></span>
                    <span>{{ $child['label'] }}</span>
                  </a>
                </li>
              @empty
                @unless ($hubSlug)
                  <li>
                    <p class="text-500 small mb-0 px-2">Aucun élément de menu latéral. Ajoutez des enfants sous le groupe dans Navigation.</p>
                  </li>
                @endunless
              @endforelse
            </ul>
          </nav>
        </div>
      </aside>

      <div class="col-lg-9">
        @if ($isOverview && $showText)
          <article class="comco-overview">
            <div class="comco-overview__media">
              <img
                class="comco-overview__image"
                src="{{ $coverImage ? pageAsset($coverImage->image, $coverImage->image_source) : themeAsset('assets/img/background-2.jpg') }}"
                alt="{{ $coverImage->caption ?? $page->title }}"
              >
            </div>

            @if ($page->excerpt)
              <p class="comco-overview__lead">{{ $page->excerpt }}</p>
            @endif

            @if ($page->body)
              <div class="comco-overview__body content-page">
                {!! $page->body !!}
              </div>
            @endif
          </article>
        @elseif ($showText)
          @php
            $isCardHub = in_array($page->slug ?? '', ['coordination', 'partenaires', 'equipe'], true);
            $hasCards = $cards->isNotEmpty();
          @endphp

          @if ($page->excerpt)
            <p class="comco-overview__lead mb-4">{{ $page->excerpt }}</p>
          @endif

          @if ($page->body)
            <div class="content-page comco-hub-body mb-4">
              {!! $page->body !!}
            </div>
          @endif

          @if ($isCardHub || $hasCards)
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
                @unless (filled($page->body) || filled($page->excerpt) || $pdfDocuments->isNotEmpty())
                  <div class="col-12">
                    <p class="text-500 mb-0">Le contenu de cette rubrique sera publié prochainement.</p>
                  </div>
                @endunless
              @endforelse
            </div>
          @elseif (! filled($page->body) && ! filled($page->excerpt) && $pdfDocuments->isEmpty())
            <p class="text-500 mb-0">Le contenu de cette rubrique sera publié prochainement.</p>
          @endif
        @endif

        @if ($showPdf)
          <div class="{{ $showText ? 'mt-5' : '' }}">
            @include('public.pages.partials.legal-documents', [
              'documents' => $pdfDocuments,
              'listTitle' => 'Documents PDF',
            ])
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
