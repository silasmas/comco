{{-- Bandeau contact + signalement (au-dessus du menu / du slider) --}}
<div class="bg-primary py-2 py-sm-3 text-white fw-bold">
  <div class="container">
    <div class="row align-items-center gx-3 gx-md-4">
      <div class="col-auto d-none d-lg-block fs--1">
        <span class="fas fa-map-marker-alt text-warning me-2" data-fa-transform="grow-3"></span>
        {{ config('institution.contact.address') }}
      </div>
      <div class="col col-md-auto ms-md-auto order-md-2 d-flex fs--1 align-items-center gap-2 gap-md-3 flex-wrap justify-content-end">
        <span class="d-inline-flex align-items-center">
          <span class="fas fa-envelope text-warning me-2" data-fa-transform="grow-3"></span>
          <a href="mailto:{{ config('institution.contact.email') }}" class="text-white text-decoration-none text-truncate">
            {{ config('institution.contact.email') }}
          </a>
        </span>
        <a
          class="btn btn-danger btn-sm rounded-pill comco-topbar-signal"
          href="{{ route('sections.show', ['section' => 'e-services', 'slug' => 'signaler-pratique']) }}"
        >
          <span class="fas fa-exclamation-triangle me-1" aria-hidden="true"></span>
          <span class="d-none d-sm-inline">Signaler une pratique</span>
          <span class="d-inline d-sm-none">Signaler</span>
        </a>
      </div>
      <div class="col-auto d-none d-md-block">
        <span class="fas fa-phone-alt text-warning" data-fa-transform="shrink-3"></span>
        <a href="tel:{{ preg_replace('/\s+/', '', config('institution.contact.phone')) }}" class="ms-2 fs--1 d-inline text-white fw-bold text-decoration-none">
          {{ config('institution.contact.phone') }}
        </a>
      </div>
    </div>
  </div>
</div>
