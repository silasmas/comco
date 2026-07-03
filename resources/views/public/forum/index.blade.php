@extends('layouts.public')

@section('page-header')
  <x-elixir.page-header title="Forum" :breadcrumb="[['label' => 'Forum']]" />
@endsection

@section('content')
  <section class="bg-100">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card shadow-sm mb-4">
            <div class="card-body p-4 p-lg-5">
              <h4 class="text-primary mb-4">Sujets de discussion</h4>

              @forelse ($topics as $topic)
                <article class="border-bottom pb-4 mb-4">
                  <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                    <h5 class="mb-0">
                      <a class="text-decoration-none text-primary" href="{{ route('forum.show', $topic->slug) }}">
                        {{ $topic->title }}
                      </a>
                    </h5>
                    <span class="badge bg-warning text-primary">{{ $topic->categoryLabel() }}</span>
                  </div>
                  <p class="text-500 mb-2">{{ \Illuminate\Support\Str::limit(strip_tags($topic->body), 180) }}</p>
                  <div class="small text-700 mb-3">
                    Par {{ $topic->author_name }} · {{ $topic->created_at->format('d/m/Y') }}
                    · {{ $topic->approved_replies_count }} réponse(s) · {{ $topic->views }} vue(s)
                  </div>
                  <a class="d-inline-flex align-items-center text-primary text-decoration-none fw-semi-bold" href="{{ route('forum.show', $topic->slug) }}#repondre">
                    Lire et répondre
                    <span class="ms-2">&xrarr;</span>
                  </a>
                </article>
              @empty
                <p class="text-500 mb-0">Aucun sujet publié pour le moment. Soyez le premier à lancer une discussion.</p>
              @endforelse

              {{ $topics->links() }}
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm border-warning">
            <div class="card-body p-4 p-lg-5">
              <h5 class="text-primary mb-3">Nouveau sujet</h5>
              <p class="text-500 small">Les sujets sont modérés avant publication sur le forum public.</p>
              @livewire('public.create-forum-topic')
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
