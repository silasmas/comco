@php
  use App\Models\CoordinationMember;
  use App\Support\NavigationUrl;

  $isOverview = ($page->slug ?? '') === 'presentation';
  $sidebarItems = collect(config('navigation.main', []))
    ->first(fn (array $item): bool => ($item['section'] ?? null) === 'qui-sommes-nous' && ! empty($item['sidebar']));
  $sidebarChildren = $sidebarItems['children'] ?? [];
  $coverImage = $page->galleryItems->first();
  $cards = $page->slug === 'coordination'
    ? CoordinationMember::query()->active()->ordered()->get()
    : $page->teamMembers;
@endphp

<section class="comco-presentation">
  <div class="container">
    <div class="row g-4">
      <aside class="col-lg-3">
        <nav class="comco-presentation__sidebar" aria-label="Sous-menu Présentation">
          <ul class="comco-presentation__nav">
            <li>
              <a
                class="comco-presentation__nav-link @if($isOverview) is-active @endif"
                href="{{ route('sections.show', ['section' => 'qui-sommes-nous', 'slug' => 'presentation']) }}"
              >
                Présentation
              </a>
            </li>
            @foreach ($sidebarChildren as $child)
              <li>
                <a
                  class="comco-presentation__nav-link @if(($page->slug ?? '') === ($child['slug'] ?? null)) is-active @endif"
                  href="{{ NavigationUrl::resolveChild(['section' => 'qui-sommes-nous'], $child) }}"
                >
                  {{ $child['label'] }}
                </a>
              </li>
            @endforeach
          </ul>
        </nav>
      </aside>

      <div class="col-lg-9">
        @if ($isOverview)
          @if ($coverImage)
            <img
              class="comco-presentation__overview-image mb-4"
              src="{{ pageAsset($coverImage->image, $coverImage->image_source) }}"
              alt="{{ $page->title }}"
            >
          @else
            <img
              class="comco-presentation__overview-image mb-4"
              src="{{ themeAsset('assets/img/background-2.jpg') }}"
              alt="{{ $page->title }}"
            >
          @endif

          @if ($page->excerpt)
            <p class="lead text-700 mb-4">{{ $page->excerpt }}</p>
          @endif

          <div class="content-page">
            {!! $page->body !!}
          </div>
        @else
          @if ($page->excerpt)
            <p class="lead text-700 mb-4">{{ $page->excerpt }}</p>
          @elseif ($page->body)
            <div class="content-page mb-4">
              {!! $page->body !!}
            </div>
          @endif

          <div class="row g-3">
            @forelse ($cards as $card)
              @php
                $isCoordination = $card instanceof CoordinationMember;
                $title = $isCoordination ? $card->title : $card->name;
                $summary = $isCoordination ? $card->summary : ($card->text ?: $card->role);
                $image = $card->image;
                $imageSource = $card->image_source ?? 'theme';
                $linkUrl = $isCoordination ? $card->link_url : ($card->link_url ?? null);
                $linkLabel = $isCoordination
                  ? ($card->link_label ?: 'En détail')
                  : (($card->link_label ?? null) ?: 'Voir plus');
              @endphp
              <div class="col-md-6 col-xl-4">
                <article class="comco-presentation-card">
                  @if ($image)
                    <img
                      class="comco-presentation-card__image"
                      src="{{ pageAsset($image, $imageSource) }}"
                      alt="{{ $title }}"
                    >
                  @else
                    <div class="comco-presentation-card__image" aria-hidden="true"></div>
                  @endif
                  <div class="comco-presentation-card__body">
                    <h3 class="comco-presentation-card__title">{{ $title }}</h3>
                    @if ($summary)
                      <p class="comco-presentation-card__summary">{{ \Illuminate\Support\Str::limit(strip_tags($summary), 140) }}</p>
                    @endif
                    @if ($linkUrl)
                      <a class="comco-presentation-card__link text-primary" href="{{ $linkUrl }}">
                        {{ $linkLabel }} <span aria-hidden="true">→</span>
                      </a>
                    @endif
                  </div>
                </article>
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
