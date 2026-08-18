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
}
