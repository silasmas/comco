{{--
  Variables communes d'aperçu slide.
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
  $phoneShellH = HomeSlideStyle::previewPhoneShellHeight();
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
