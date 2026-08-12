@php
  use App\Models\Post;
  $posts = Post::query()->published()->latest('published_at')->paginate(12);
@endphp

<section class="bg-100">
  <div class="container">
    @if ($page->excerpt)
      <p class="lead text-700 mb-5">{{ $page->excerpt }}</p>
    @endif

    <div class="bg-white px-3 py-4 px-lg-5 rounded-3 content-page mb-5">
      {!! $page->body !!}
    </div>

    <div class="row g-4">
      @forelse ($posts as $post)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <a href="{{ route('posts.show', $post->slug) }}">
              <img class="card-img-top" src="{{ postImage($post->featured_image) }}" alt="{{ $post->title }}">
            </a>
            <div class="card-body" data-zanim-timeline="{}" data-zanim-trigger="scroll">
              <div class="overflow-hidden">
                <a href="{{ route('posts.show', $post->slug) }}">
                  <h5 data-zanim-xs='{"delay":0}'>{{ $post->title }}</h5>
                </a>
              </div>
              @if ($post->author)
                <div class="overflow-hidden">
                  <p class="text-500" data-zanim-xs='{"delay":0.1}'>Par {{ $post->author }}</p>
                </div>
              @endif
              @if ($post->excerpt)
                <div class="overflow-hidden">
                  <p class="mt-3" data-zanim-xs='{"delay":0.2}'>{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                </div>
              @endif
              <div class="overflow-hidden">
                <div class="d-inline-block" data-zanim-xs='{"delay":0.3}'>
                  <a class="d-flex align-items-center text-primary text-decoration-none" href="{{ route('posts.show', $post->slug) }}">
                    Lire la suite
                    <span class="ms-2 fw-medium">&xrarr;</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <p class="text-500">Aucun article publié pour le moment.</p>
        </div>
      @endforelse
    </div>

    @if ($posts->hasPages())
      <div class="d-flex justify-content-center mt-5 comco-pagination">
        {{ $posts->onEachSide(1)->links() }}
      </div>
    @endif
  </div>
</section>
