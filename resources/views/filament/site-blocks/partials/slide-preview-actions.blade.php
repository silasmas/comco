{{-- Boutons de l'aperçu slide. --}}
@if (($showPrimary ?? false) || ($showSecondary ?? false))
  <div class="comco-slide-preview__actions" style="justify-content: {{ $btnJustify }};">
    @if ($showPrimary ?? false)
      <span
        class="comco-slide-preview__btn"
        style="background: {{ $primaryColors['bg'] }}; color: {{ $primaryColors['color'] }}; border-color: {{ $primaryColors['border'] }}; border-radius: {{ $btnRadius }}; padding: {{ $btnPadding ?? '0.45rem 0.85rem' }}; font-size: {{ $btnFontSize ?? '0.88rem' }};"
      >{{ $preview['primaryLabel'] }}</span>
    @endif
    @if ($showSecondary ?? false)
      <span
        class="comco-slide-preview__btn"
        style="background: {{ $secondaryColors['bg'] }}; color: {{ $secondaryColors['color'] }}; border-color: {{ $secondaryColors['border'] }}; border-radius: {{ $btnRadius }}; padding: {{ $btnPadding ?? '0.45rem 0.85rem' }}; font-size: {{ $btnFontSize ?? '0.88rem' }};"
      >{{ $preview['secondaryLabel'] }}</span>
    @endif
  </div>
@endif
