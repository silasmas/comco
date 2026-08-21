<?php

namespace App\Support;

/**
 * Helpers d'apparence pour les diapositives du slider d'accueil.
 */
class HomeSlideStyle
{
    /**
     * Polices disponibles pour le titre et la description.
     *
     * @return array<string, string>
     */
    public static function fontOptions(): array
    {
        return [
            'inherit' => 'Par défaut (thème)',
            'Montserrat, sans-serif' => 'Montserrat',
            '"Open Sans", sans-serif' => 'Open Sans',
            'Georgia, serif' => 'Georgia (serif)',
            'Arial, Helvetica, sans-serif' => 'Arial',
        ];
    }

    /**
     * Alignements horizontaux du bloc texte.
     *
     * @return array<string, string>
     */
    public static function horizontalAlignOptions(): array
    {
        return [
            'start' => 'Gauche',
            'center' => 'Centre',
            'end' => 'Droite',
        ];
    }

    /**
     * Alignements verticaux du contenu dans le slide.
     *
     * @return array<string, string>
     */
    public static function verticalAlignOptions(): array
    {
        return [
            'start' => 'Haut',
            'center' => 'Milieu',
            'end' => 'Bas',
        ];
    }

    /**
     * Tailles de texte pour le titre / la description.
     *
     * @return array<string, string>
     */
    public static function textSizeOptions(): array
    {
        return [
            'default' => 'Par défaut (thème)',
            'sm' => 'Petit',
            'md' => 'Moyen',
            'lg' => 'Grand',
            'xl' => 'Très grand',
            '2xl' => 'Énorme',
            'custom' => 'Personnalisée (valeur CSS)',
        ];
    }

    /**
     * CSS font-size du titre selon l'option choisie.
     *
     * @param  string|null  $size  Option taille
     * @param  string|null  $custom  Valeur CSS libre (ex. 2.5rem)
     * @return string|null Valeur CSS ou null (classes thème)
     */
    public static function titleSizeCss(?string $size, ?string $custom = null): ?string
    {
        if ($size === 'custom' && filled($custom)) {
            return self::sanitizeCssSize($custom);
        }

        return match ($size) {
            'sm' => 'clamp(1.25rem, 2vw, 1.75rem)',
            'md' => 'clamp(1.5rem, 2.5vw, 2.25rem)',
            'lg' => 'clamp(1.85rem, 3vw, 2.75rem)',
            'xl' => 'clamp(2.1rem, 3.5vw, 3.25rem)',
            '2xl' => 'clamp(2.4rem, 4vw, 3.75rem)',
            default => null,
        };
    }

    /**
     * CSS font-size de la description selon l'option choisie.
     *
     * @param  string|null  $size  Option taille
     * @param  string|null  $custom  Valeur CSS libre (ex. 1.25rem)
     * @return string|null Valeur CSS ou null (classes thème)
     */
    public static function textSizeCss(?string $size, ?string $custom = null): ?string
    {
        if ($size === 'custom' && filled($custom)) {
            return self::sanitizeCssSize($custom);
        }

        return match ($size) {
            'sm' => 'clamp(0.9rem, 1.2vw, 1.05rem)',
            'md' => 'clamp(1rem, 1.4vw, 1.25rem)',
            'lg' => 'clamp(1.15rem, 1.7vw, 1.5rem)',
            'xl' => 'clamp(1.3rem, 2vw, 1.75rem)',
            '2xl' => 'clamp(1.45rem, 2.3vw, 2rem)',
            default => null,
        };
    }

    /**
     * Tailles de boutons disponibles.
     *
     * @return array<string, string>
     */
    public static function buttonSizeOptions(): array
    {
        return [
            'sm' => 'Petit',
            'md' => 'Moyen (défaut)',
            'lg' => 'Grand',
            'custom' => 'Personnalisée (valeur CSS)',
        ];
    }

    /**
     * Classe Bootstrap de taille de bouton.
     *
     * @param  string|null  $size  Option taille
     * @return string Classe CSS
     */
    public static function buttonSizeClass(?string $size): string
    {
        return match ($size) {
            'sm' => 'btn-sm',
            'lg' => 'btn-lg',
            default => '',
        };
    }

    /**
     * Style inline pour une taille de bouton personnalisée.
     *
     * @param  string|null  $size  Option taille
     * @param  string|null  $custom  Valeur CSS (ex. 1.1rem)
     * @return string Attribut style partiel
     */
    public static function buttonSizeStyle(?string $size, ?string $custom = null): string
    {
        if ($size !== 'custom') {
            return '';
        }

        $value = self::sanitizeCssSize($custom);

        return $value !== null ? 'font-size: '.$value.';' : '';
    }

    /**
     * Nettoie une taille CSS saisie (rem, px, em, %).
     *
     * @param  string|null  $value  Saisie utilisateur
     * @return string|null Valeur sûre ou null
     */
    public static function sanitizeCssSize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/i', $value) !== 1) {
            return null;
        }

        return $value;
    }

    /**
     * Liste des pages CMS pour les liens de boutons du slide.
     *
     * @return array<string, string> Clé section/slug => libellé
     */
    public static function cmsPageOptions(): array
    {
        return \App\Models\Page::query()
            ->orderBy('section')
            ->orderBy('title')
            ->get(['title', 'section', 'slug'])
            ->mapWithKeys(static function ($page): array {
                $key = $page->section.'/'.$page->slug;

                return [$key => $page->title.' ('.$key.')'];
            })
            ->all();
    }

    /**
     * Synchronise la clé page (section/slug) vers section + slug séparés.
     *
     * @param  array<string, mixed>  $payload  Payload formulaire
     * @param  string  $prefix  btn_primary|btn_secondary
     * @return array<string, mixed> Payload mis à jour
     */
    public static function syncButtonPageFields(array $payload, string $prefix): array
    {
        $pageKey = $payload[$prefix.'_page'] ?? null;

        if (is_string($pageKey) && str_contains($pageKey, '/')) {
            [$section, $slug] = explode('/', $pageKey, 2);
            $payload[$prefix.'_section'] = $section;
            $payload[$prefix.'_slug'] = $slug;
        }

        return $payload;
    }

    /**
     * Remplit la clé page à partir de section/slug existants (édition).
     *
     * @param  array<string, mixed>  $payload  Payload enregistré
     * @param  string  $prefix  btn_primary|btn_secondary
     * @return array<string, mixed> Payload hydraté
     */
    public static function hydrateButtonPageField(array $payload, string $prefix): array
    {
        if (filled($payload[$prefix.'_page'] ?? null)) {
            return $payload;
        }

        $section = $payload[$prefix.'_section'] ?? null;
        $slug = $payload[$prefix.'_slug'] ?? null;

        if (filled($section) && filled($slug)) {
            $payload[$prefix.'_page'] = $section.'/'.$slug;
        }

        return $payload;
    }

    /**
     * Résout l'apparence d'un bouton (style Bootstrap + couleurs custom optionnelles).
     *
     * @param  array<string, mixed>  $slide  Payload slide
     * @param  string  $prefix  btn_primary|btn_secondary
     * @param  string  $defaultStyle  Style Bootstrap par défaut
     * @return array{useCustom: bool, class: string, style: string, bg: string, color: string, border: string}
     */
    public static function buttonAppearance(array $slide, string $prefix, string $defaultStyle): array
    {
        $style = (string) ($slide[$prefix.'_style'] ?? $defaultStyle);
        $defaults = self::previewButtonColors($style !== '' ? $style : $defaultStyle);
        $customBg = filled($slide[$prefix.'_bg'] ?? null) ? (string) $slide[$prefix.'_bg'] : null;
        $customText = filled($slide[$prefix.'_text_color'] ?? null) ? (string) $slide[$prefix.'_text_color'] : null;

        if ($customBg !== null || $customText !== null) {
            $bg = $customBg ?? $defaults['bg'];
            $color = $customText ?? $defaults['color'];

            return [
                'useCustom' => true,
                'class' => '',
                'style' => 'background-color: '.$bg.'; color: '.$color.'; border-color: '.$bg.';',
                'bg' => $bg,
                'color' => $color,
                'border' => $bg,
            ];
        }

        return [
            'useCustom' => false,
            'class' => 'btn-'.$style,
            'style' => '',
            'bg' => $defaults['bg'],
            'color' => $defaults['color'],
            'border' => $defaults['border'],
        ];
    }

    /**
     * Formes de boutons.
     *
     * @return array<string, string>
     */
    public static function buttonShapeOptions(): array
    {
        return [
            'rounded' => 'Coins arrondis',
            'pill' => 'Pilule (très arrondi)',
            'square' => 'Carré (angles droits)',
        ];
    }

    /**
     * Alignement horizontal du groupe de boutons (indépendant du texte).
     *
     * @return array<string, string>
     */
    public static function buttonAlignOptions(): array
    {
        return [
            'inherit' => 'Comme le texte',
            'start' => 'Gauche',
            'center' => 'Centre',
            'end' => 'Droite',
        ];
    }

    /**
     * Emplacement des boutons par rapport au titre / texte.
     *
     * @return array<string, string>
     */
    public static function buttonPlacementOptions(): array
    {
        return [
            'after_text' => 'Sous la description (défaut)',
            'after_title' => 'Juste sous le titre',
            'before_title' => 'Au-dessus du titre',
            'bottom' => 'En bas du bloc texte',
        ];
    }

    /**
     * Classes CSS pour l'alignement du groupe de boutons.
     *
     * @param  string|null  $align  Alignement choisi
     * @param  string|null  $contentAlign  Alignement du texte (si inherit)
     * @return string Classes CSS
     */
    public static function buttonAlignClass(?string $align, ?string $contentAlign = 'start'): string
    {
        $resolved = ($align === null || $align === '' || $align === 'inherit')
            ? ($contentAlign ?: 'start')
            : $align;

        return match ($resolved) {
            'center' => 'comco-slide__actions--center justify-content-center text-center',
            'end' => 'comco-slide__actions--end justify-content-end text-end',
            default => 'comco-slide__actions--start justify-content-start text-start',
        };
    }

    /**
     * Styles Bootstrap des boutons.
     *
     * @return array<string, string>
     */
    public static function buttonStyleOptions(): array
    {
        return [
            'warning' => 'Jaune (warning)',
            'primary' => 'Bleu (primary)',
            'danger' => 'Rouge (danger)',
            'success' => 'Vert (success)',
            'light' => 'Clair',
            'outline-light' => 'Contour clair',
            'outline-warning' => 'Contour jaune',
        ];
    }

    /**
     * Hauteurs minimales proposées pour un slide.
     *
     * @return array<string, string>
     */
    public static function heightOptions(): array
    {
        return [
            'default' => 'Hauteur site (plus haute)',
            '28rem' => 'Moyenne (28rem)',
            '36rem' => 'Haute (36rem)',
            '42rem' => 'Très haute (42rem)',
            '50vh' => '50 % de l’écran',
            '70vh' => '70 % de l’écran',
        ];
    }

    /**
     * Classe CSS Bootstrap pour la forme du bouton.
     *
     * @param  string|null  $shape  Forme choisie
     * @return string Classe CSS
     */
    public static function buttonShapeClass(?string $shape): string
    {
        return match ($shape) {
            'pill' => 'rounded-pill',
            'square' => 'rounded-0',
            default => 'rounded',
        };
    }

    /**
     * Classe d'alignement flex verticale.
     *
     * @param  string|null  $align  Alignement
     * @return string Classe Bootstrap
     */
    public static function verticalAlignClass(?string $align): string
    {
        return match ($align) {
            'start' => 'align-items-start',
            'end' => 'align-items-end',
            default => 'align-items-center',
        };
    }

    /**
     * Classes texte + colonne pour l'alignement horizontal.
     *
     * @param  string|null  $align  Alignement
     * @return array{text: string, col: string}
     */
    public static function horizontalAlignClasses(?string $align): array
    {
        return match ($align) {
            'center' => ['text' => 'text-center', 'col' => 'mx-auto'],
            'end' => ['text' => 'text-end', 'col' => 'ms-auto'],
            default => ['text' => 'text-start', 'col' => ''],
        };
    }

    /**
     * Résout l'URL d'un bouton de slide depuis le payload.
     *
     * @param  array<string, mixed>  $slide  Payload slide
     * @param  string  $prefix  Préfixe payload (btn_primary|btn_secondary)
     * @param  string|null  $fallbackRoute  Route Laravel de repli
     * @return string URL
     */
    public static function buttonUrl(array $slide, string $prefix, ?string $fallbackRoute = null): string
    {
        if (filled($slide[$prefix.'_url'] ?? null)) {
            return (string) $slide[$prefix.'_url'];
        }

        $pageKey = $slide[$prefix.'_page'] ?? null;
        if (is_string($pageKey) && str_contains($pageKey, '/')) {
            [$section, $slug] = explode('/', $pageKey, 2);
            if ($section !== '' && $slug !== '') {
                return route('sections.show', [
                    'section' => $section,
                    'slug' => $slug,
                ]);
            }
        }

        $section = $slide[$prefix.'_section'] ?? null;
        $slug = $slide[$prefix.'_slug'] ?? null;

        if (filled($section) && filled($slug)) {
            return route('sections.show', [
                'section' => $section,
                'slug' => $slug,
            ]);
        }

        if ($fallbackRoute !== null) {
            return route($fallbackRoute);
        }

        return '#';
    }

    /**
     * Construit les données d'aperçu admin à partir d'un tableau d'état formulaire.
     *
     * @param  array<string, mixed>  $data  État Livewire / formulaire
     * @return array<string, mixed> Données d'aperçu
     */
    public static function previewFromArray(array $data): array
    {
        return self::previewFromForm(
            static fn (string $key): mixed => data_get($data, $key)
        );
    }

    /**
     * Construit les données d'aperçu admin à partir de l'état du formulaire.
     *
     * @param  callable(string): mixed  $get  Accesseur d'état Filament
     * @return array{
     *   title: string,
     *   text: string,
     *   imageUrl: string,
     *   titleColor: string,
     *   textColor: string,
     *   titleFont: string|null,
     *   textFont: string|null,
     *   hAlign: string,
     *   vAlign: string,
     *   minHeight: string,
     *   btnShape: string,
     *   btnAlign: string,
     *   btnPlacement: string,
     *   primaryLabel: string|null,
     *   primaryStyle: string,
     *   secondaryLabel: string|null,
     *   secondaryStyle: string
     * }
     */
    public static function previewFromForm(callable $get): array
    {
        $contentHAlign = (string) ($get('payload.content_h_align') ?? 'start');
        $hasCustomPrimary = filled($get('payload.btn_primary_label'))
            || filled($get('payload.btn_primary_url'))
            || filled($get('payload.btn_primary_section'));

        return [
            'title' => (string) ($get('payload.title') ?: 'Titre du slide'),
            'text' => (string) ($get('payload.text') ?: 'Description du slide affichée sous le titre.'),
            'imageUrl' => self::previewImageUrl($get),
            'titleColor' => (string) ($get('payload.title_color') ?: '#ffffff'),
            'textColor' => (string) ($get('payload.text_color') ?: '#ffc107'),
            'titleFont' => self::previewFont($get('payload.title_font')),
            'textFont' => self::previewFont($get('payload.text_font')),
            'titleSize' => (string) ($get('payload.title_size') ?: 'default'),
            'titleSizeCustom' => (string) ($get('payload.title_size_custom') ?: ''),
            'textSize' => (string) ($get('payload.text_size') ?: 'default'),
            'textSizeCustom' => (string) ($get('payload.text_size_custom') ?: ''),
            'hAlign' => $contentHAlign,
            'vAlign' => (string) ($get('payload.content_v_align') ?: 'center'),
            'minHeight' => (string) ($get('payload.min_height') ?: 'default'),
            'btnShape' => (string) ($get('payload.btn_shape') ?: 'rounded'),
            'btnSize' => (string) ($get('payload.btn_size') ?: 'md'),
            'btnSizeCustom' => (string) ($get('payload.btn_size_custom') ?: ''),
            'btnAlign' => (string) ($get('payload.btn_h_align') ?: 'inherit'),
            'btnPlacement' => (string) ($get('payload.btn_placement') ?: 'after_text'),
            'primaryLabel' => $hasCustomPrimary
                ? (($get('payload.btn_primary_label') !== null && $get('payload.btn_primary_label') !== '')
                    ? (string) $get('payload.btn_primary_label')
                    : null)
                : 'En savoir plus',
            'primaryStyle' => (string) ($get('payload.btn_primary_style') ?: 'warning'),
            'primaryBg' => (string) ($get('payload.btn_primary_bg') ?: ''),
            'primaryTextColor' => (string) ($get('payload.btn_primary_text_color') ?: ''),
            'secondaryLabel' => filled($get('payload.btn_secondary_label'))
                ? (string) $get('payload.btn_secondary_label')
                : null,
            'secondaryStyle' => (string) ($get('payload.btn_secondary_style') ?: 'danger'),
            'secondaryBg' => (string) ($get('payload.btn_secondary_bg') ?: ''),
            'secondaryTextColor' => (string) ($get('payload.btn_secondary_text_color') ?: ''),
        ];
    }

    /**
     * Résout l'URL d'image pour l'aperçu (upload temporaire ou chemin stocké).
     *
     * @param  callable(string): mixed  $get  Accesseur d'état Filament
     * @return string URL publique ou temporaire
     */
    public static function previewImageUrl(callable $get): string
    {
        $uploaded = $get('payload.uploaded_image');

        if (is_array($uploaded)) {
            $uploaded = $uploaded[0] ?? null;
        }

        if (is_object($uploaded) && method_exists($uploaded, 'temporaryUrl')) {
            try {
                return (string) $uploaded->temporaryUrl();
            } catch (\Throwable) {
                // Continuer vers le chemin stocké.
            }
        }

        if (is_string($uploaded) && $uploaded !== '') {
            return asset('storage/'.ltrim($uploaded, '/'));
        }

        $image = (string) ($get('payload.image') ?? '');
        $source = (string) ($get('payload.image_source') ?? 'comco');

        return blockAsset([
            'image' => $image,
            'image_source' => $source,
        ]);
    }

    /**
     * Normalise une police pour le rendu d'aperçu.
     *
     * @param  mixed  $font  Valeur formulaire
     * @return string|null Police CSS ou null
     */
    public static function previewFont(mixed $font): ?string
    {
        if (! is_string($font) || $font === '' || $font === 'inherit') {
            return null;
        }

        return $font;
    }

    /**
     * Couleurs approximatives des styles de boutons Bootstrap pour l'admin.
     *
     * @param  string  $style  Clé style (warning, danger…)
     * @return array{bg: string, color: string, border: string}
     */
    public static function previewButtonColors(string $style): array
    {
        return match ($style) {
            'primary' => ['bg' => '#0d6efd', 'color' => '#ffffff', 'border' => '#0d6efd'],
            'danger' => ['bg' => '#dc3545', 'color' => '#ffffff', 'border' => '#dc3545'],
            'success' => ['bg' => '#198754', 'color' => '#ffffff', 'border' => '#198754'],
            'light' => ['bg' => '#f8f9fa', 'color' => '#212529', 'border' => '#f8f9fa'],
            'outline-light' => ['bg' => 'transparent', 'color' => '#ffffff', 'border' => '#ffffff'],
            'outline-warning' => ['bg' => 'transparent', 'color' => '#ffc107', 'border' => '#ffc107'],
            default => ['bg' => '#ffc107', 'color' => '#212529', 'border' => '#ffc107'],
        };
    }

    /**
     * Rayon de bordure pour la forme de bouton en aperçu.
     *
     * @param  string  $shape  Forme
     * @return string Valeur CSS
     */
    public static function previewButtonRadius(string $shape): string
    {
        return match ($shape) {
            'pill' => '999px',
            'square' => '0',
            default => '0.375rem',
        };
    }

    /**
     * Hauteur CSS de l'aperçu selon le réglage slide (alignée sur le CSS public).
     *
     * Mobile réel : 28rem par défaut. PC réel : 42rem (≥1200px).
     * Les options rem/vh du formulaire sont respectées telles quelles.
     *
     * @param  string  $minHeight  Option formulaire
     * @param  string  $device  desktop|mobile
     * @return string Valeur CSS
     */
    public static function previewFrameHeight(string $minHeight, string $device = 'desktop'): string
    {
        if ($minHeight !== 'default' && $minHeight !== '') {
            if (str_ends_with($minHeight, 'vh')) {
                $vh = max(1.0, (float) $minHeight);
                // Viewport de référence réaliste (px) pour convertir vh → rem.
                $refPx = $device === 'mobile' ? 667.0 : 900.0;

                return round(($vh / 100.0) * $refPx / 16.0, 2).'rem';
            }

            return $minHeight;
        }

        return $device === 'mobile' ? '28rem' : '42rem';
    }

    /**
     * Hauteur totale du cadre téléphone (viewport mobile simulé).
     *
     * @return string Valeur CSS
     */
    public static function previewPhoneShellHeight(): string
    {
        return '41.7rem'; // ~667px — iPhone classique
    }

    /**
     * Prépare toutes les variables Blade d'aperçu (même scope que la vue parente).
     *
     * @param  array<string, mixed>  $preview  Données previewFromForm/previewFromArray
     * @return array<string, mixed> Variables à extraire dans la vue
     */
    public static function previewViewData(array $preview): array
    {
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
        $primaryColors = self::buttonAppearance([
            'btn_primary_style' => $preview['primaryStyle'] ?? 'warning',
            'btn_primary_bg' => $preview['primaryBg'] ?? null,
            'btn_primary_text_color' => $preview['primaryTextColor'] ?? null,
        ], 'btn_primary', 'warning');
        $secondaryColors = self::buttonAppearance([
            'btn_secondary_style' => $preview['secondaryStyle'] ?? 'danger',
            'btn_secondary_bg' => $preview['secondaryBg'] ?? null,
            'btn_secondary_text_color' => $preview['secondaryTextColor'] ?? null,
        ], 'btn_secondary', 'danger');
        $btnRadius = self::previewButtonRadius($preview['btnShape'] ?? 'rounded');
        $btnSize = (string) ($preview['btnSize'] ?? 'md');
        $btnSizeCustom = (string) ($preview['btnSizeCustom'] ?? '');
        $btnPadding = match ($btnSize) {
            'sm' => '0.3rem 0.65rem',
            'lg' => '0.7rem 1.2rem',
            'custom' => '0.5rem 1rem',
            default => '0.45rem 0.85rem',
        };
        $btnFontSize = match ($btnSize) {
            'sm' => '0.75rem',
            'lg' => '1.05rem',
            'custom' => (self::sanitizeCssSize($btnSizeCustom) ?? '0.95rem'),
            default => '0.88rem',
        };
        $titleSizeCss = self::titleSizeCss(
            $preview['titleSize'] ?? 'default',
            $preview['titleSizeCustom'] ?? null
        ) ?? 'clamp(1.35rem, 2.4vw, 2.1rem)';
        $textSizeCss = self::textSizeCss(
            $preview['textSize'] ?? 'default',
            $preview['textSizeCustom'] ?? null
        ) ?? 'clamp(0.95rem, 1.4vw, 1.25rem)';
        $desktopH = self::previewFrameHeight($preview['minHeight'] ?? 'default', 'desktop');
        $mobileH = self::previewFrameHeight($preview['minHeight'] ?? 'default', 'mobile');
        $phoneShellH = self::previewPhoneShellHeight();
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
            'titleSizeCss',
            'textSizeCss',
            'showPrimary',
            'showSecondary',
            'primaryColors',
            'secondaryColors',
            'btnRadius',
            'btnPadding',
            'btnFontSize'
        );

        return compact(
            'preview',
            'hAlign',
            'vAlign',
            'btnAlign',
            'btnPlacement',
            'contentTextAlign',
            'contentMargin',
            'vJustify',
            'btnJustify',
            'primaryColors',
            'secondaryColors',
            'btnRadius',
            'btnPadding',
            'btnFontSize',
            'titleSizeCss',
            'textSizeCss',
            'desktopH',
            'mobileH',
            'phoneShellH',
            'titleFont',
            'textFont',
            'showPrimary',
            'showSecondary',
            'frameData'
        );
    }
}
