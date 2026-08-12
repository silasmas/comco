@php
  $galleryItems = $page->galleryItems;
@endphp

<section class="bg-100">
  <div class="container">
    @if ($page->excerpt)
      <p class="lead text-700 mb-4">{{ $page->excerpt }}</p>
    @endif

    <div class="bg-white px-3 py-4 px-lg-5 rounded-3 content-page mb-5">
      {!! $page->body !!}
    </div>

    @if ($galleryItems->isNotEmpty())
      <p class="text-500 mb-4">Cliquez sur une photo pour l'afficher en plein écran et parcourir l'album sans quitter la page.</p>

      <div class="row g-3" id="comco-gallery">
        @foreach ($galleryItems as $index => $item)
          @php
            $imageUrl = pageAsset($item->image, $item->image_source);
            $caption = $item->caption ?: 'COMCO — Activités institutionnelles';
          @endphp
          <div class="col-6 col-md-4 col-lg-3">
            <a
              class="d-block comco-gallery-link overflow-hidden rounded-3"
              href="javascript:void(0)"
              role="button"
              data-bp="{{ $imageUrl }}"
              data-bigpicture='{"gallery":"#comco-gallery"}'
              data-caption="{{ $caption }} ({{ $index + 1 }}/{{ $galleryItems->count() }})"
              aria-label="Ouvrir la photo {{ $index + 1 }} en plein écran"
            >
              <img class="img-fluid w-100 comco-gallery-thumb" src="{{ $imageUrl }}" alt="{{ $caption }} {{ $index + 1 }}">
            </a>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
