<div>
  @if ($posts->isEmpty())
    <p class="text-500">Aucun article publié pour le moment. Le contenu apparaîtra ici une fois ajouté depuis l'administration.</p>
  @elseif ($variant === 'list')
    <div class="list-group list-group-flush">
      @foreach ($posts as $post)
        <a class="list-group-item list-group-item-action px-0 py-4 border-bottom text-decoration-none" href="{{ route('posts.show', $post->slug) }}">
          @if ($post->category)
            <span class="badge bg-warning text-primary mb-2">{{ $post->category }}</span>
          @endif
          <h5 class="mb-2 text-primary">{{ $post->title }}</h5>
          @if ($post->excerpt)
            <p class="mb-0 text-500">{{ $post->excerpt }}</p>
          @endif
        </a>
      @endforeach
    </div>
  @elseif ($variant === 'elixir')
    <div class="row g-4">
      @foreach ($posts as $post)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 overflow-hidden">
            <a class="comco-post-card-media" href="{{ route('posts.show', $post->slug) }}">
              <img class="card-img-top" src="{{ postImage($post->featured_image) }}" alt="{{ $post->title }}">
              @if ($post->hasVideo())
                <span class="comco-post-card-media__play" aria-hidden="true">
                  <span class="fas fa-play"></span>
                </span>
              @endif
            </a>
            <div class="card-body">
              <div class="overflow-hidden">
                <a class="text-decoration-none" href="{{ route('posts.show', $post->slug) }}">
                  <h5 class="text-primary">{{ $post->title }}</h5>
                </a>
              </div>
              @if ($post->category)
                <div class="overflow-hidden">
                  <p class="text-500">{{ $post->category }}</p>
                </div>
              @endif
              @if ($post->excerpt)
                <div class="overflow-hidden">
                  <p class="mt-3">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                </div>
              @endif
              <div class="overflow-hidden">
                <div class="d-inline-block">
                  <a class="d-flex align-items-center text-primary text-decoration-none" href="{{ route('posts.show', $post->slug) }}">
                    Lire la suite
                    <span class="ms-2 fw-medium">&xrarr;</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="row g-4">
      @foreach ($posts as $post)
        <div class="col-md-6">
          <article class="card h-100 shadow-sm overflow-hidden">
            <a class="comco-post-card-media" href="{{ route('posts.show', $post->slug) }}">
              <img class="card-img-top" src="{{ postImage($post->featured_image) }}" alt="{{ $post->title }}">
              @if ($post->hasVideo())
                <span class="comco-post-card-media__play" aria-hidden="true">
                  <span class="fas fa-play"></span>
                </span>
              @endif
            </a>
            <div class="card-body p-4">
              @if ($post->category)
                <span class="badge bg-primary mb-2">{{ $post->category }}</span>
              @endif
              <h5 class="mb-3">
                <a class="text-decoration-none text-primary" href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
              </h5>
              @if ($post->excerpt)
                <p class="mb-0 text-500">{{ $post->excerpt }}</p>
              @endif
            </div>
          </article>
        </div>
      @endforeach
    </div>
  @endif
</div>
