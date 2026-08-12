@extends('layouts.public')

@section('page-header')
  <x-elixir.page-header
    :title="$post->title"
    :breadcrumb="[
      ['label' => 'Centre d\'information', 'url' => route('sections.show', ['section' => 'centre-information', 'slug' => 'actualites'])],
      ['label' => $post->title],
    ]"
  />
@endsection

@section('content')
  <section class="bg-100">
    <div class="container">
      <div class="overflow-hidden mb-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
        <div data-zanim-xs='{"delay":0}'>
          @if ($post->author)
            <span class="d-inline-block text-500">{{ $post->author }}</span>
          @endif
          @if ($post->published_at)
            <span class="d-inline-block text-500"> · {{ $post->published_at->format('d/m/Y') }}</span>
          @endif
        </div>
        <h4 data-zanim-xs='{"delay":0.1}'>{{ $post->title }}</h4>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card mb-6">
            <img class="card-img-top" src="{{ postImage($post->featured_image) }}" alt="{{ $post->title }}">
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
                <div class="card mb-4 text-start">
                  <a href="{{ route('posts.show', $related->slug) }}">
                    <img class="card-img-top" src="{{ postImage($related->featured_image) }}" alt="{{ $related->title }}">
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
