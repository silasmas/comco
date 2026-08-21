{{--
  Téléphone mobile : le slide garde sa hauteur réelle ; le reste de l’écran reste visible.
  Attend $frameData, $mobileH, $phoneShellH.
--}}
<div class="comco-slide-phone" style="--comco-phone-h: {{ $phoneShellH }};">
  <div class="comco-slide-phone__chrome">Aperçu mobile · hauteur slide {{ $mobileH }}</div>
  @include('filament.site-blocks.partials.slide-preview-frame', array_merge($frameData, [
    'frameHeight' => $mobileH,
    'narrow' => true,
    'fixedHeight' => true,
  ]))
  <div class="comco-slide-phone__rest">
    <div class="comco-slide-phone__rest-line"></div>
    <div class="comco-slide-phone__rest-line"></div>
    <div class="comco-slide-phone__rest-line"></div>
    Suite de la page (le slide ne remplit pas tout l’écran)
  </div>
</div>
