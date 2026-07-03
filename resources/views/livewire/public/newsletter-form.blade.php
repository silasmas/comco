<div>
  <x-form-feedback :message="$feedbackMessage" :type="$feedbackType" />

  <form class="mt-4" wire:submit="subscribe">
    <div class="row align-items-center">
      <div class="col-md-7 pe-md-0">
        <div class="input-group">
          <input
            class="form-control @error('email') is-invalid @enderror"
            type="email"
            wire:model="email"
            placeholder="Votre email"
            required
          >
          @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="col-md-5 mt-3 mt-md-0">
        <div class="d-grid">
          <button class="btn btn-warning" type="submit">
            <span class="text-primary fw-semi-bold">S'abonner</span>
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
