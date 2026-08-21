{{--
  Panneau latéral d'aperçu slide — bascule PC / Mobile (plein espace).
  @var array $preview
--}}
@php
  use App\Support\HomeSlideStyle;

  $hAlign = $preview['hAlign'] ?? 'start';
  $vAlign = $preview['vAlign'] ?? 'center';
  $btnAlign = ($preview['btnAlign'] ?? 'inherit') === 'inherit'
    ? $hAlign
    : ($preview['btnAlign'] ?? 'start');
  $btnPlacement = $preview['btnPlacement'] ?? 'after_text';
  $justifyMap = ['start' => 'flex-start', 'center' => 'center', 'end' => 'flex-end'];
  $textAlignMap = ['start' => 'left', 'center' => 'center', 'end' => 'right'];
  $contentTextAlign = $textAlignMap[$hAlign] ?? 'left';
  $btnJustify = $justifyMap[$btnAlign] ?? 'flex-start';
  $vJustify = $justifyMap[$vAlign] ?? 'center';
  $contentMargin = match ($hAlign) {
    'center' => '0 auto',
    'end' => '0 0 0 auto',
    default => '0',
  };
  $primaryColors = HomeSlideStyle::previewButtonColors($preview['primaryStyle'] ?? 'warning');
  $secondaryColors = HomeSlideStyle::previewButtonColors($preview['secondaryStyle'] ?? 'danger');
  $btnRadius = HomeSlideStyle::previewButtonRadius($preview['btnShape'] ?? 'rounded');
  $desktopH = HomeSlideStyle::previewFrameHeight($preview['minHeight'] ?? 'default', 'desktop');
  $mobileH = HomeSlideStyle::previewFrameHeight($preview['minHeight'] ?? 'default', 'mobile');
  $titleFont = $preview['titleFont'] ?? 'system-ui, sans-serif';
  $textFont = $preview['textFont'] ?? 'system-ui, sans-serif';
  $showPrimary = filled($preview['primaryLabel'] ?? null);
  $showSecondary = filled($preview['secondaryLabel'] ?? null);
  $frameData = compact(
    'preview',
    'btnPlacement',
    'contentTextAlign',
    'contentMargin',
    'vJustify',
    'btnJustify',
    'titleFont',
    'textFont',
    'showPrimary',
    'showSecondary',
    'primaryColors',
    'secondaryColors',
    'btnRadius'
  );
@endphp

<style>
  .comco-slide-panel {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-height: min(78vh, 52rem);
  }
  .comco-slide-panel__toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem 1rem;
  }
  .comco-slide-panel__toggle {
    display: inline-flex;
    border: 1px solid #d7dde5;
    border-radius: 0.55rem;
    overflow: hidden;
    background: #fff;
  }
  .comco-slide-panel__toggle button {
    appearance: none;
    border: 0;
    background: transparent;
    color: #475569;
    font-size: 0.875rem;
    font-weight: 700;
    padding: 0.5rem 1.1rem;
    cursor: pointer;
  }
  .comco-slide-panel__toggle button.is-active {
    background: #f59e0b;
    color: #111827;
  }
  .comco-slide-panel__meta {
    font-size: 0.85rem;
    color: #64748b;
  }
  .comco-slide-panel__stage {
    flex: 1;
    display: flex;
    align-items: stretch;
    justify-content: center;
    background: #e8edf3;
    border: 1px solid #d7dde5;
    border-radius: 0.75rem;
    padding: 1rem;
    min-height: 28rem;
  }
  .comco-slide-panel__pc {
    width: 100%;
    max-width: 100%;
  }
  .comco-slide-panel__pc .comco-slide-preview__frame {
    min-height: max({{ $desktopH }}, 28rem) !important;
    border-radius: 0.5rem;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.16);
  }
  .comco-slide-panel__pc .comco-slide-preview__inner {
    padding: 2rem 2.5rem;
  }
  .comco-slide-panel__pc .comco-slide-preview__title {
    font-size: clamp(1.75rem, 3vw, 2.65rem);
  }
  .comco-slide-panel__pc .comco-slide-preview__text {
    font-size: clamp(1.05rem, 1.6vw, 1.45rem);
  }
  .comco-slide-panel__mobile {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 0.5rem 0 1rem;
  }
  .comco-slide-panel__phone {
    width: min(100%, 390px);
    border: 12px solid #0f172a;
    border-radius: 1.6rem;
    overflow: hidden;
    background: #0f172a;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.28);
  }
  .comco-slide-panel__phone .comco-slide-preview__frame {
    min-height: max({{ $mobileH }}, 34rem) !important;
    border-radius: 0.65rem;
  }
  .comco-slide-panel__phone .comco-slide-preview__inner {
    padding: 1rem;
  }
  .comco-slide-panel__phone .comco-slide-preview__title {
    font-size: 1.2rem;
  }
  .comco-slide-panel__phone .comco-slide-preview__text {
    font-size: 0.9rem;
  }
  .comco-slide-panel__phone .comco-slide-preview__btn {
    font-size: 0.78rem;
    padding: 0.4rem 0.7rem;
  }
  .comco-slide-preview__frame {
    position: relative;
    width: 100%;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    overflow: hidden;
  }
  .comco-slide-preview__frame::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(15, 23, 42, 0.55), rgba(15, 23, 42, 0.2));
  }
  .comco-slide-preview__inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-sizing: border-box;
  }
  .comco-slide-preview__content {
    display: flex;
    flex-direction: column;
    width: min(100%, 42rem);
    max-width: 100%;
  }
  .comco-slide-preview__title {
    margin: 0;
    line-height: 1.2;
    font-weight: 700;
  }
  .comco-slide-preview__text {
    margin: 0.85rem 0 0;
    line-height: 1.35;
  }
  .comco-slide-preview__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.55rem;
    margin-top: 1rem;
  }
  .comco-slide-preview__btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.95rem;
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid transparent;
    white-space: nowrap;
  }
</style>

<div
  class="comco-slide-panel"
  x-data="{ device: 'pc' }"
  wire:key="slide-panel-{{ md5(json_encode($preview)) }}"
>
  <div class="comco-slide-panel__toolbar">
    <div class="comco-slide-panel__toggle" role="group" aria-label="Choix d’appareil">
      <button
        type="button"
        @click="device = 'pc'"
        :class="{ 'is-active': device === 'pc' }"
      >PC</button>
      <button
        type="button"
        @click="device = 'mobile'"
        :class="{ 'is-active': device === 'mobile' }"
      >Mobile</button>
    </div>
    <div class="comco-slide-panel__meta">
      Alignement texte : {{ $hAlign }} · boutons : {{ $btnAlign }} · emplacement : {{ $btnPlacement }}
    </div>
  </div>

  <div class="comco-slide-panel__stage">
    <div class="comco-slide-panel__pc" x-show="device === 'pc'" x-cloak>
      @include('filament.site-blocks.partials.slide-preview-frame', array_merge($frameData, [
        'frameHeight' => $desktopH,
        'narrow' => false,
      ]))
    </div>

    <div class="comco-slide-panel__mobile" x-show="device === 'mobile'" x-cloak>
      <div class="comco-slide-panel__phone">
        @include('filament.site-blocks.partials.slide-preview-frame', array_merge($frameData, [
          'frameHeight' => $mobileH,
          'narrow' => true,
        ]))
      </div>
    </div>
  </div>
</div>
