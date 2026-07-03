<div>
  <x-form-feedback :message="$feedbackMessage" :type="$feedbackType" />

  @if (! empty($serviceConfig))
    <p class="text-500 mb-4">{{ $serviceConfig['intro'] ?? '' }}</p>

    <form wire:submit="submit">
      <x-form-required-note />
      <div class="row">
        <div class="col-md-6 mb-4">
          <label for="es-name" class="form-label fw-semi-bold">Nom complet <span class="text-danger">*</span></label>
          <input id="es-name" type="text" wire:model="name" required class="form-control bg-white @error('name') is-invalid @enderror">
          @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-4">
          <label for="es-email" class="form-label fw-semi-bold">Email <span class="text-danger">*</span></label>
          <input id="es-email" type="email" wire:model="email" required class="form-control bg-white @error('email') is-invalid @enderror">
          @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mb-4">
        <label for="es-phone" class="form-label fw-semi-bold">Téléphone</label>
        <input id="es-phone" type="text" wire:model="phone" class="form-control bg-white @error('phone') is-invalid @enderror">
        @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>

      @foreach (($serviceConfig['fields'] ?? []) as $field)
        @php
          $isRequired = $field['required'] ?? false;
        @endphp
        <div class="mb-4">
          @if (($field['type'] ?? 'text') === 'checkbox')
            <div class="form-check">
              <input
                id="field-{{ $field['name'] }}"
                type="checkbox"
                wire:model="fields.{{ $field['name'] }}"
                class="form-check-input @error('fields.' . $field['name']) is-invalid @enderror"
              >
              <label class="form-check-label" for="field-{{ $field['name'] }}">{{ $field['label'] }}</label>
              @error('fields.' . $field['name']) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
          @elseif (($field['type'] ?? 'text') === 'select')
            <label for="field-{{ $field['name'] }}" class="form-label fw-semi-bold">
              {{ $field['label'] }} @if($isRequired)<span class="text-danger">*</span>@endif
            </label>
            <select id="field-{{ $field['name'] }}" wire:model="fields.{{ $field['name'] }}" @if($isRequired) required @endif class="form-select bg-white @error('fields.' . $field['name']) is-invalid @enderror">
              <option value="">Sélectionnez...</option>
              @foreach (($field['options'] ?? []) as $option)
                <option value="{{ $option }}">{{ $option }}</option>
              @endforeach
            </select>
            @error('fields.' . $field['name']) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          @elseif (($field['type'] ?? 'text') === 'textarea')
            <label for="field-{{ $field['name'] }}" class="form-label fw-semi-bold">
              {{ $field['label'] }} @if($isRequired)<span class="text-danger">*</span>@endif
            </label>
            <textarea
              id="field-{{ $field['name'] }}"
              rows="{{ $field['rows'] ?? 4 }}"
              wire:model="fields.{{ $field['name'] }}"
              @if($isRequired) required @endif
              class="form-control bg-white @error('fields.' . $field['name']) is-invalid @enderror"
            ></textarea>
            @error('fields.' . $field['name']) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          @else
            <label for="field-{{ $field['name'] }}" class="form-label fw-semi-bold">
              {{ $field['label'] }} @if($isRequired)<span class="text-danger">*</span>@endif
            </label>
            <input
              id="field-{{ $field['name'] }}"
              type="text"
              wire:model="fields.{{ $field['name'] }}"
              @if($isRequired) required @endif
              class="form-control bg-white @error('fields.' . $field['name']) is-invalid @enderror"
            >
            @error('fields.' . $field['name']) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          @endif
        </div>
      @endforeach

      <div class="mb-4">
        <label for="es-description" class="form-label fw-semi-bold">Description détaillée <span class="text-danger">*</span></label>
        <textarea id="es-description" rows="6" wire:model="description" required class="form-control bg-white @error('description') is-invalid @enderror"></textarea>
        @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn btn-warning w-100">
        <span class="text-primary fw-semi-bold">Transmettre le dossier</span>
      </button>
    </form>
  @endif
</div>
