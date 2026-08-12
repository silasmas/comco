<section class="bg-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        @if ($page->excerpt)
          <p class="lead text-700 mb-4">{{ $page->excerpt }}</p>
        @endif
        <article class="card shadow-sm mb-4">
          <div class="card-body p-4 p-lg-5 content-page">
            {!! $page->body !!}
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
