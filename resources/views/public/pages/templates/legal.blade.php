@php
  $documents = config('legal.documents', []);
  $activeDocument = $documents[0] ?? null;
@endphp

<section class="bg-100">
  <div class="container">
    @if ($page->excerpt)
      <p class="lead text-700 mb-4">{{ $page->excerpt }}</p>
    @endif

    <div class="bg-white px-3 py-4 px-lg-5 rounded-3 content-page mb-5">
      {!! $page->body !!}
    </div>

    @if (count($documents) > 0)
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="card shadow-sm h-100">
            <div class="card-body p-4">
              <h5 class="text-primary mb-4">Textes législatifs</h5>
              <div class="list-group list-group-flush comco-legal-list">
                @foreach ($documents as $document)
                  <button
                    type="button"
                    class="list-group-item list-group-item-action comco-legal-tab @if($loop->first) is-selected @endif"
                    data-pdf-url="{{ legalDocumentUrl($document['filename']) }}"
                    data-pdf-title="{{ $document['title'] }}"
                  >
                    <strong class="d-block comco-legal-tab-title">{{ $document['title'] }}</strong>
                    <small class="comco-legal-tab-desc">{{ $document['description'] }}</small>
                  </button>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
              <h5 class="text-white mb-0 comco-legal-title">{{ $activeDocument['title'] ?? 'Document' }}</h5>
              <a
                class="btn btn-warning btn-sm comco-legal-download"
                href="{{ $activeDocument ? legalDocumentUrl($activeDocument['filename']) : '#' }}"
                download
              >
                <span class="text-primary fw-semi-bold">Télécharger le PDF</span>
              </a>
            </div>
            <div class="card-body p-0">
              <iframe
                class="legal-pdf-viewer comco-legal-viewer"
                title="{{ $activeDocument['title'] ?? 'Document juridique' }}"
                src="{{ $activeDocument ? legalDocumentUrl($activeDocument['filename']) . '#view=FitH' : '' }}"
              ></iframe>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
</section>

@push('scripts')
  <script>
    document.querySelectorAll('.comco-legal-tab').forEach(function (button) {
      button.addEventListener('click', function () {
        document.querySelectorAll('.comco-legal-tab').forEach(function (item) {
          item.classList.remove('is-selected');
        });
        button.classList.add('is-selected');
        document.querySelector('.comco-legal-viewer').src = button.dataset.pdfUrl + '#view=FitH';
        document.querySelector('.comco-legal-title').textContent = button.dataset.pdfTitle;
        document.querySelector('.comco-legal-download').href = button.dataset.pdfUrl;
      });
    });
  </script>
@endpush
