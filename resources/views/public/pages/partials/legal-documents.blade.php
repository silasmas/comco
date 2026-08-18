@php
  /** @var \Illuminate\Support\Collection<int, \App\Models\PageLegalDocument> $documents */
  $documents = $documents ?? collect();
  $activeDocument = $documents->first();
  $listTitle = $listTitle ?? 'Documents';
@endphp

@if ($documents->isNotEmpty())
  <div class="row g-4 {{ $wrapperClass ?? '' }}">
    <div class="col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-body p-4">
          <h5 class="text-primary mb-4">{{ $listTitle }}</h5>
          <div class="list-group list-group-flush comco-legal-list">
            @foreach ($documents as $document)
              <button
                type="button"
                class="list-group-item list-group-item-action comco-legal-tab @if($loop->first) is-selected @endif"
                data-pdf-url="{{ pageLegalDocumentUrl($document->filename) }}"
                data-pdf-title="{{ $document->title }}"
              >
                <strong class="d-block comco-legal-tab-title">{{ $document->title }}</strong>
                @if ($document->description)
                  <small class="comco-legal-tab-desc">{{ $document->description }}</small>
                @endif
              </button>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
          <h5 class="text-white mb-0 comco-legal-title">{{ $activeDocument?->title ?? 'Document' }}</h5>
          <a
            class="btn btn-warning btn-sm comco-legal-download"
            href="{{ $activeDocument ? pageLegalDocumentUrl($activeDocument->filename) : '#' }}"
            download
          >
            <span class="text-primary fw-semi-bold">Télécharger le PDF</span>
          </a>
        </div>
        <div class="card-body p-0">
          <iframe
            class="legal-pdf-viewer comco-legal-viewer"
            title="{{ $activeDocument?->title ?? 'Document PDF' }}"
            src="{{ $activeDocument ? pageLegalDocumentUrl($activeDocument->filename) . '#view=FitH' : '' }}"
          ></iframe>
        </div>
      </div>
    </div>
  </div>

  @once
    @push('scripts')
      <script>
        document.querySelectorAll('.comco-legal-tab').forEach(function (button) {
          button.addEventListener('click', function () {
            document.querySelectorAll('.comco-legal-tab').forEach(function (item) {
              item.classList.remove('is-selected');
            });
            button.classList.add('is-selected');
            var viewer = document.querySelector('.comco-legal-viewer');
            var title = document.querySelector('.comco-legal-title');
            var download = document.querySelector('.comco-legal-download');
            if (viewer) {
              viewer.src = button.dataset.pdfUrl + '#view=FitH';
            }
            if (title) {
              title.textContent = button.dataset.pdfTitle;
            }
            if (download) {
              download.href = button.dataset.pdfUrl;
            }
          });
        });
      </script>
    @endpush
  @endonce
@endif
