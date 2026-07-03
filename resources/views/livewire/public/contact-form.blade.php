<div>
  <x-form-feedback :message="$feedbackMessage" :type="$feedbackType" />

  <form wire:submit="submit">
    <x-form-required-note />
    <div class="mb-4">
      <label for="name" class="form-label fw-semi-bold">Nom complet <span class="text-danger">*</span></label>
      <input id="name" type="text" wire:model="name" required class="form-control bg-white @error('name') is-invalid @enderror">
      @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="mb-4">
      <label for="email" class="form-label fw-semi-bold">Email <span class="text-danger">*</span></label>
      <input id="email" type="email" wire:model="email" required class="form-control bg-white @error('email') is-invalid @enderror">
      @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="mb-4">
      <label for="message" class="form-label fw-semi-bold">Message <span class="text-danger">*</span></label>
      <textarea id="message" rows="6" wire:model="message" required class="form-control bg-white @error('message') is-invalid @enderror"></textarea>
      @error('message') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">
      <span class="color-white fw-600">Envoyer le message</span>
    </button>
  </form>
</div>
