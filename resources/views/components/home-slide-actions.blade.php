{{--
  Groupe de boutons d'un slide d'accueil.
--}}
@props([
  'primaryLabel' => null,
  'primaryUrl' => '#',
  'primaryClass' => 'btn-warning',
  'primaryStyleAttr' => '',
  'secondaryLabel' => null,
  'secondaryUrl' => null,
  'secondaryClass' => 'btn-danger',
  'secondaryStyleAttr' => '',
  'btnShape' => 'rounded',
  'btnSizeClass' => '',
  'btnAlignClass' => '',
  'extraClass' => '',
])

@if (filled($primaryLabel) || filled($secondaryLabel))
  <div @class(['overflow-hidden', $extraClass])>
    <div class="comco-slide__actions d-flex flex-wrap {{ $btnAlignClass }}">
      @if (filled($primaryLabel))
        <a
          class="btn {{ $primaryClass }} {{ $btnShape }} {{ $btnSizeClass }} me-3 mt-3"
          href="{{ $primaryUrl }}"
          @if(filled($primaryStyleAttr)) style="{{ $primaryStyleAttr }}" @endif
        >
          {{ $primaryLabel }}<span class="fas fa-chevron-right ms-2"></span>
        </a>
      @endif
      @if (filled($secondaryLabel))
        <a
          class="btn {{ $secondaryClass }} {{ $btnShape }} {{ $btnSizeClass }} mt-3"
          href="{{ $secondaryUrl }}"
          @if(filled($secondaryStyleAttr)) style="{{ $secondaryStyleAttr }}" @endif
        >
          {{ $secondaryLabel }}<span class="fas fa-exclamation-triangle ms-2"></span>
        </a>
      @endif
    </div>
  </div>
@endif
