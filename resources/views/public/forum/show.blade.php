@extends('layouts.public')

@section('page-header')
  <x-elixir.page-header :title="$topic->title" :breadcrumb="[['label' => 'Forum', 'url' => route('forum.index')], ['label' => $topic->title]]" />
@endsection

@section('content')
  <section class="bg-100">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-8">
          <article class="card shadow-sm mb-4">
            <div class="card-body p-4 p-lg-5">
              <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <span class="badge bg-warning text-primary">{{ $topic->categoryLabel() }}</span>
                <span class="small text-700">{{ $topic->views }} vue(s)</span>
              </div>
              <div class="content-page mb-4">
                {!! nl2br(e($topic->body)) !!}
              </div>
              <div class="small text-700 border-top pt-3">
                Publié par {{ $topic->author_name }} le {{ $topic->created_at->format('d/m/Y H:i') }}
              </div>
            </div>
          </article>

          <div class="card shadow-sm mb-4">
            <div class="card-body p-4 p-lg-5">
              <h5 class="text-primary mb-4">Réponses ({{ $replies->count() }})</h5>

              @forelse ($replies as $reply)
                <div class="border-bottom pb-4 mb-4" id="reponses">
                  <div class="content-page mb-2">{!! nl2br(e($reply->body)) !!}</div>
                  <div class="small text-700">
                    {{ $reply->author_name }} · {{ $reply->created_at->format('d/m/Y H:i') }}
                  </div>
                </div>
              @empty
                <p class="text-500 mb-0">Aucune réponse pour le moment.</p>
              @endforelse
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm border-warning mb-4">
            <div class="card-body p-4">
              <a class="btn btn-light w-100 mb-0" href="{{ route('forum.index') }}">Retour au forum</a>
            </div>
          </div>

          <div class="card shadow-sm border-warning" id="repondre">
            <div class="card-body p-4 p-lg-5">
              <h5 class="text-primary mb-3">Répondre</h5>
              @livewire('public.create-forum-reply', ['topicId' => $topic->id], key('forum-reply-' . $topic->id))
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
