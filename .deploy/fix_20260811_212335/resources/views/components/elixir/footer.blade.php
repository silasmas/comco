<footer class="footer bg-primary text-center py-4">
  <div class="container">
    <div class="row align-items-center opacity-85 text-white">
      <div class="col-md-3 text-md-start">
        <a href="{{ route('home') }}" class="d-inline-block bg-white rounded-3 px-3 py-2">
          <img src="{{ comcoAsset('logo01.png') }}" alt="{{ config('institution.fullName') }}" class="comco-logo comco-logo-footer">
        </a>
      </div>
      <div class="col-md mt-3 mt-md-0">
        <p class="lh-lg mb-2 fw-semi-bold footer-tagline">{{ config('institution.tagline') }}</p>
        <p class="lh-lg mb-0">&copy; {{ date('Y') }} {{ config('institution.shortName') }} / RDC. Tous droits réservés.</p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <p class="mb-1">{{ config('institution.contact.email') }}</p>
        <p class="mb-0">{{ config('institution.contact.phone') }}</p>
      </div>
    </div>
  </div>
</footer>
