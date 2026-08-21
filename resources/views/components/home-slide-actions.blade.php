{{--
  Groupe de boutons d'un slide d'accueil.
  @var string|null $primaryLabel
  @var string $primaryStyle
  @var string $primaryUrl
  @var string|null $secondaryLabel
  @var string $secondaryStyle
  @var string|null $secondaryUrl
  @var string $btnShape
  @var string $btnAlignClass
  @var string $extraClass Classes supplémentaires sur le conteneur actions
--}}
@props([
  'primaryLabel' => null,
  'primaryStyle' => 'warning',
  'primaryUrl' => '#',
  'secondaryLabel' => null,
  'secondaryStyle' => 'danger',
  'secondaryUrl' => null,
  'btnShape' => 'rounded',
  'btnAlignClass' => '',
  'extraClass' => '',
])

@if (filled($primaryLabel) || filled($secondaryLabel))
  <div @class(['overflow-hidden', $extraClass])>
    <div class="comco-slide__actions d-flex flex-wrap {{ $btnAlignClass }}">
      @if (filled($primaryLabel))
        <a class="btn btn-{{ $primaryStyle }} {{ $btnShape }} me-3 mt-3" href="{{ $primaryUrl }}">
          {{ $primaryLabel }}<span class="fas fa-chevron-right ms-2"></span>
        </a>
      @endif
      @if (filled($secondaryLabel))
        <a class="btn btn-{{ $secondaryStyle }} {{ $btnShape }} mt-3" href="{{ $secondaryUrl }}">
          {{ $secondaryLabel }}<span class="fas fa-exclamation-triangle ms-2"></span>
        </a>
      @endif
    </div>
  </div>
@endif
