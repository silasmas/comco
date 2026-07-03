<div>
  <x-form-feedback :message="$feedbackMessage" :type="$feedbackType" />

  <form wire:submit="submit">
    <x-form-required-note />
    <div class="mb-3">
      <label for="forum-title" class="form-label fw-semi-bold">Titre <span class="text-danger">*</span></label>
      <input id="forum-title" type="text" wire:model="title" required class="form-control bg-white @error('title') is-invalid @enderror">
      @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="forum-category" class="form-label fw-semi-bold">Catégorie <span class="text-danger">*</span></label>
      <select id="forum-category" wire:model="category" required class="form-select bg-white @error('category') is-invalid @enderror">
        <option value="">Sélectionnez...</option>
        @foreach ($categories as $value => $label)
          <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
      </select>
      @error('category') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="forum-author" class="form-label fw-semi-bold">Votre nom <span class="text-danger">*</span></label>
      <input id="forum-author" type="text" wire:model="authorName" required class="form-control bg-white @error('authorName') is-invalid @enderror">
      @error('authorName') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="forum-email" class="form-label fw-semi-bold">Votre email <span class="text-danger">*</span></label>
      <input id="forum-email" type="email" wire:model="authorEmail" required class="form-control bg-white @error('authorEmail') is-invalid @enderror">
      @error('authorEmail') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="forum-body" class="form-label fw-semi-bold">Message <span class="text-danger">*</span></label>
      <textarea id="forum-body" rows="5" wire:model="body" required class="form-control bg-white @error('body') is-invalid @enderror"></textarea>
      @error('body') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-warning w-100">
      <span class="text-primary fw-semi-bold">Publier le sujet</span>
    </button>
  </form>
</div>
