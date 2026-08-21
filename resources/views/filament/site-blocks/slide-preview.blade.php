{{--
  Aperçu live d'un slide d'accueil (PC + mobile) dans Filament.
  @var array $preview Données normalisées via HomeSlideStyle::previewFromForm()
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
  .comco-slide-preview {
    display: grid;
    gap: 1.25rem;
    grid-template-columns: 1fr;
  }
  @media (min-width: 1100px) {
    .comco-slide-preview {
      grid-template-columns: minmax(0, 1fr) 280px;
      align-items: start;
    }
  }
  .comco-slide-preview__device {
    border: 1px solid #d7dde5;
    border-radius: 0.75rem;
    background: #f8fafc;
    overflow: hidden;
  }
  .comco-slide-preview__label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.55rem 0.85rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
    background: #eef2f7;
    border-bottom: 1px solid #d7dde5;
  }
  .comco-slide-preview__hint {
    font-weight: 500;
    color: #64748b;
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
    padding: 1.25rem 1.5rem;
    box-sizing: border-box;
  }
  .comco-slide-preview__content {
    display: flex;
    flex-direction: column;
    width: min(100%, 34rem);
    max-width: 100%;
  }
  .comco-slide-preview__title {
    margin: 0;
    font-size: clamp(1.35rem, 2.4vw, 2.1rem);
    line-height: 1.2;
    font-weight: 700;
  }
  .comco-slide-preview__text {
    margin: 0.85rem 0 0;
    font-size: clamp(0.95rem, 1.4vw, 1.25rem);
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
    padding: 0.45rem 0.85rem;
    font-size: 0.82rem;
    font-weight: 600;
    border: 1px solid transparent;
    white-space: nowrap;
  }
  .comco-slide-preview__mobile-shell {
    margin: 0.75rem auto 1rem;
    width: 240px;
    border: 10px solid #0f172a;
    border-radius: 1.4rem;
    overflow: hidden;
    background: #0f172a;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
  }
  .comco-slide-preview__mobile-shell .comco-slide-preview__frame {
    border-radius: 0.5rem;
  }
  .comco-slide-preview__mobile-shell .comco-slide-preview__inner {
    padding: 0.85rem;
  }
  .comco-slide-preview__mobile-shell .comco-slide-preview__title {
    font-size: 1.05rem;
  }
  .comco-slide-preview__mobile-shell .comco-slide-preview__text {
    font-size: 0.82rem;
    margin-top: 0.55rem;
  }
  .comco-slide-preview__mobile-shell .comco-slide-preview__btn {
    font-size: 0.72rem;
    padding: 0.35rem 0.65rem;
  }
  .comco-slide-preview__note {
    margin: 0;
    padding: 0.5rem 0.85rem 0.75rem;
    font-size: 0.78rem;
    color: #64748b;
  }
</style>

<div class="comco-slide-preview" wire:key="slide-preview-{{ md5(json_encode($preview)) }}">
  <div class="comco-slide-preview__device">
    <div class="comco-slide-preview__label">
      <span>Aperçu PC</span>
      <span class="comco-slide-preview__hint">~1280px</span>
    </div>
    @include('filament.site-blocks.partials.slide-preview-frame', array_merge($frameData, [
      'frameHeight' => $desktopH,
      'narrow' => false,
    ]))
    <p class="comco-slide-preview__note">
      Alignement texte : {{ $hAlign }} · boutons : {{ $btnAlign }} · emplacement : {{ $btnPlacement }}
    </p>
  </div>

  <div class="comco-slide-preview__device">
    <div class="comco-slide-preview__label">
      <span>Aperçu mobile</span>
      <span class="comco-slide-preview__hint">~375px</span>
    </div>
    <div class="comco-slide-preview__mobile-shell">
      @include('filament.site-blocks.partials.slide-preview-frame', array_merge($frameData, [
        'frameHeight' => $mobileH,
        'narrow' => true,
      ]))
    </div>
    <p class="comco-slide-preview__note">
      Vérifiez surtout titres longs et boutons sur petit écran.
    </p>
  </div>
</div>
