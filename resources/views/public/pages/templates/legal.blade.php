@php
  $documents = $page->legalDocuments;
@endphp

<section class="bg-100">
  <div class="container">
    @if ($page->excerpt)
      <p class="lead text-700 mb-4">{{ $page->excerpt }}</p>
    @endif

    @if ($page->body)
      <div class="bg-white px-3 py-4 px-lg-5 rounded-3 content-page mb-5">
        {!! $page->body !!}
      </div>
    @endif

    @include('public.pages.partials.legal-documents', [
      'documents' => $documents,
      'listTitle' => 'Textes législatifs',
    ])
  </div>
</section>
