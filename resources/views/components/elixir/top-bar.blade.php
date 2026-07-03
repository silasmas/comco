<div class="bg-primary py-3 d-none d-sm-block text-white fw-bold">
  <div class="container">
    <div class="row align-items-center gx-4">
      <div class="col-auto d-none d-lg-block fs--1">
        <span class="fas fa-map-marker-alt text-warning me-2" data-fa-transform="grow-3"></span>
        {{ config('institution.contact.address') }}
      </div>
      <div class="col-auto ms-md-auto order-md-2 d-none d-md-flex fs--1 align-items-center">
        <span class="fas fa-envelope text-warning me-2" data-fa-transform="grow-3"></span>
        <a href="mailto:{{ config('institution.contact.email') }}" class="text-white text-decoration-none">
          {{ config('institution.contact.email') }}
        </a>
      </div>
      <div class="col-auto">
        <span class="fas fa-phone-alt text-warning" data-fa-transform="shrink-3"></span>
        <a href="tel:{{ preg_replace('/\s+/', '', config('institution.contact.phone')) }}" class="ms-2 fs--1 d-inline text-white fw-bold text-decoration-none">
          {{ config('institution.contact.phone') }}
        </a>
      </div>
    </div>
  </div>
</div>
