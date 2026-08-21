{{--
  Aperçu inline (toujours visible) avant ouverture du panneau latéral.
  @var array $preview
--}}
@php
  extract(\App\Support\HomeSlideStyle::previewViewData($preview), EXTR_SKIP);
@endphp
@include('filament.site-blocks.partials.slide-preview-styles')

<div
  class="comco-slide-preview-shell comco-slide-preview-shell--inline"
  x-data="{ device: 'pc' }"
  wire:key="slide-inline-{{ md5(json_encode($preview)) }}"
>
  <div class="comco-slide-preview-shell__toolbar">
    <div class="comco-slide-preview-shell__toggle" role="group" aria-label="Choix d’appareil">
      <button type="button" @click="device = 'pc'" :class="{ 'is-active': device === 'pc' }">PC</button>
      <button type="button" @click="device = 'mobile'" :class="{ 'is-active': device === 'mobile' }">Mobile</button>
    </div>
    <div class="comco-slide-preview-shell__meta">
      Hauteur slide : PC {{ $desktopH }} · Mobile {{ $mobileH }} (écran téléphone {{ $phoneShellH }})
    </div>
  </div>

  <div class="comco-slide-preview-shell__stage">
    <div class="comco-slide-preview-shell__pc" x-show="device === 'pc'" x-cloak>
      @include('filament.site-blocks.partials.slide-preview-frame', array_merge($frameData, [
        'frameHeight' => $desktopH,
        'narrow' => false,
        'fixedHeight' => true,
      ]))
    </div>
    <div class="comco-slide-preview-shell__mobile" x-show="device === 'mobile'" x-cloak>
      @include('filament.site-blocks.partials.slide-preview-phone', [
        'frameData' => $frameData,
        'mobileH' => $mobileH,
        'phoneShellH' => $phoneShellH,
      ])
    </div>
  </div>
</div>
