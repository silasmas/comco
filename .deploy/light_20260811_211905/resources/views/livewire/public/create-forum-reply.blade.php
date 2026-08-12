<div>
  <x-form-feedback :message="$feedbackMessage" :type="$feedbackType" />

  <form wire:submit="submit">
    <x-form-required-note />
    <div class="mb-3">
      <label for="reply-author" class="form-label fw-semi-bold">Votre nom <span class="text-danger">*</span></label>
      <input id="reply-author" type="text" wire:model="authorName" required class="form-control bg-white @error('authorName') is-invalid @enderror">
      @error('authorName') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="reply-email" class="form-label fw-semi-bold">Votre email <span class="text-danger">*</span></label>
      <input id="reply-email" type="email" wire:model="authorEmail" required class="form-control bg-white @error('authorEmail') is-invalid @enderror">
      @error('authorEmail') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="reply-body" class="form-label fw-semi-bold">Réponse <span class="text-danger">*</span></label>
      <textarea id="reply-body" rows="5" wire:model="body" required class="form-control bg-white @error('body') is-invalid @enderror"></textarea>
      @error('body') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-warning w-100">
      <span class="text-primary fw-semi-bold">Envoyer la réponse</span>
    </button>
  </form>
</div>
