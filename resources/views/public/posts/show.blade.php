@extends('layouts.public')

@section('page-header')
  <x-elixir.page-title :title="$post->title" />
@endsection

@section('content')
  <section class="bg-100">
    <div class="container">
      @if (! empty($isPreview))
        <div class="comco-preview-banner alert alert-warning mb-4" role="status">
          <strong>Mode prévisualisation</strong>
          — Ce contenu n’est visible que pour les administrateurs connectés.
          @if (! $post->is_published)
            Il est encore en <em>brouillon</em> et n’apparaît pas sur le site public.
          @endif
          <a class="alert-link ms-1" href="{{ \App\Filament\Resources\Posts\PostResource::getUrl('edit', ['record' => $post]) }}">Retour à l’édition</a>
        </div>
      @endif

      <div class="overflow-hidden mb-4">
        <div>
          @if ($post->isActivity())
            <span class="badge bg-warning text-primary me-2">Activité</span>
          @endif
          @if ($post->author)
            <span class="d-inline-block text-500">{{ $post->author }}</span>
          @endif
          @if ($post->published_at)
            <span class="d-inline-block text-500"> · {{ $post->published_at->format('d/m/Y') }}</span>
          @endif
        </div>
        <h4>{{ $post->title }}</h4>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card mb-6 overflow-hidden">
            @if ($post->hasVideo())
              <div class="comco-post-media">
                <video
                  class="comco-post-media__video"
                  controls
                  playsinline
                  preload="metadata"
                  poster="{{ postImage($post->featured_image) }}"
                >
                  <source src="{{ postVideo($post->featured_video) }}">
                  Votre navigateur ne prend pas en charge la lecture vidéo.
                </video>
              </div>
            @else
              <img class="card-img-top" src="{{ postImage($post->featured_image) }}" alt="{{ $post->title }}">
            @endif
            <div class="card-body p-5 content-page">
              {!! $post->body !!}
            </div>
          </div>
        </div>

        <div class="col-lg-4 text-center ms-auto mt-5 mt-lg-0">
          <div class="px-2">
            @if ($relatedPosts->isNotEmpty())
              <h5 class="mb-4 text-start">Articles connexes</h5>
              @foreach ($relatedPosts as $related)
                <div class="card mb-4 text-start overflow-hidden">
                  <a class="comco-post-card-media" href="{{ route('posts.show', $related->slug) }}">
                    <img class="card-img-top" src="{{ postImage($related->featured_image) }}" alt="{{ $related->title }}">
                    @if ($related->hasVideo())
                      <span class="comco-post-card-media__play" aria-hidden="true">
                        <span class="fas fa-play"></span>
                      </span>
                    @endif
                  </a>
                  <div class="card-body">
                    <h6 class="mb-2">
                      <a class="text-decoration-none text-primary" href="{{ route('posts.show', $related->slug) }}">
                        {{ $related->title }}
                      </a>
                    </h6>
                    @if ($related->excerpt)
                      <p class="text-500 small mb-0">{{ \Illuminate\Support\Str::limit($related->excerpt, 100) }}</p>
                    @endif
                  </div>
                </div>
              @endforeach
            @endif

            <a class="btn btn-warning w-100" href="{{ route('sections.show', ['section' => 'centre-information', 'slug' => 'actualites']) }}">
              <span class="text-primary fw-semi-bold">Toutes les actualités</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
