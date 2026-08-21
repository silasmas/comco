{{--
  Cadre unique d'aperçu slide (réutilisé PC / mobile).
--}}
@php
  $contentStyle = 'text-align: '.$contentTextAlign.';';
  if (! ($narrow ?? false)) {
    $contentStyle .= ' margin: '.$contentMargin.';';
  }
  if (($btnPlacement ?? '') === 'bottom') {
    $contentStyle .= ' flex: 1;';
  }
@endphp

<div
  class="comco-slide-preview__frame"
  style="min-height: {{ $frameHeight }}; background-image: url('{{ $preview['imageUrl'] }}');"
>
  <div class="comco-slide-preview__inner" style="justify-content: {{ $vJustify }};">
    <div class="comco-slide-preview__content" style="{{ $contentStyle }}">
      @if (($btnPlacement ?? '') === 'before_title')
        @include('filament.site-blocks.partials.slide-preview-actions')
      @endif

      <h3
        class="comco-slide-preview__title"
        style="color: {{ $preview['titleColor'] }}; font-family: {{ $titleFont }};"
      >{{ $preview['title'] }}</h3>

      @if (($btnPlacement ?? '') === 'after_title')
        @include('filament.site-blocks.partials.slide-preview-actions')
      @endif

      <p
        class="comco-slide-preview__text"
        style="color: {{ $preview['textColor'] }}; font-family: {{ $textFont }};"
      >{{ $preview['text'] }}</p>

      @if (in_array($btnPlacement ?? '', ['after_text', 'bottom'], true))
        <div @if(($btnPlacement ?? '') === 'bottom') style="margin-top: auto;" @endif>
          @include('filament.site-blocks.partials.slide-preview-actions')
        </div>
      @endif
    </div>
  </div>
</div>
