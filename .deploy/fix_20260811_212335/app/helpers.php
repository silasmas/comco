<?php

use App\Support\SiteNavigation;

/**
 * Retourne l'URL d'un asset du thème Elixir.
 *
 * @param  string  $path  Chemin relatif depuis public/theme/
 * @return string URL publique de l'asset
 */
function themeAsset(string $path): string
{
    return asset('theme/'.ltrim($path, '/'));
}

/**
 * Retourne l'URL d'un asset institutionnel COMCO.
 *
 * @param  string  $path  Chemin relatif depuis public/assets/
 * @return string URL publique de l'asset
 */
function comcoAsset(string $path): string
{
    return asset('assets/'.ltrim($path, '/'));
}

/**
 * Retourne le gabarit Elixir associé à une page CMS.
 *
 * @param  string  $section  Section de navigation
 * @param  string  $slug  Identifiant URL de la page
 * @param  string|null  $template  Gabarit enregistré en base
 * @return string Nom du gabarit Blade
 */
function pageTemplate(string $section, string $slug, ?string $template = null): string
{
    if (filled($template)) {
        return $template;
    }

    return config("page-templates.{$section}.{$slug}")
      ?? config('page-templates.default', 'blank');
}

/**
 * Retourne l'URL publique d'un document juridique PDF.
 *
 * @param  string  $filename  Nom du fichier dans public/assets/documents/
 * @return string URL publique du document
 */
function legalDocumentUrl(string $filename): string
{
    return comcoAsset('documents/'.ltrim($filename, '/'));
}

/**
 * Retourne l'URL de l'image d'un article (thème Elixir ou stockage).
 *
 * @param  string|null  $path  Chemin relatif enregistré sur le post
 * @return string URL publique de l'image
 */
function postImage(?string $path): string
{
    if ($path === null || $path === '') {
        return themeAsset('assets/img/news-1.jpg');
    }

    if (str_starts_with($path, 'assets/')) {
        return themeAsset($path);
    }

    return asset('storage/'.ltrim($path, '/'));
}

/**
 * Retourne l'URL absolue du logo COMCO pour les emails.
 *
 * @return string URL publique du logo
 */
function comcoMailLogoUrl(): string
{
    return rtrim((string) config('app.url'), '/').'/assets/logo01.png';
}

/**
 * Retourne l'URL d'un visuel de bloc dynamique (thème Elixir ou assets COMCO).
 *
 * @param  array<string, mixed>  $item  Données du bloc
 * @param  string  $pathKey  Clé contenant le chemin relatif
 * @param  string  $defaultSource  Source par défaut (comco|theme)
 * @return string URL publique du visuel
 */
function blockAsset(array $item, string $pathKey = 'image', string $defaultSource = 'comco'): string
{
    $path = (string) ($item[$pathKey] ?? '');

    if ($path === '') {
        return comcoAsset('logo01.png');
    }

    if (str_starts_with($path, 'site-blocks/')) {
        return asset('storage/'.ltrim($path, '/'));
    }

    $source = (string) ($item['image_source'] ?? $defaultSource);

    if ($source === 'theme') {
        return themeAsset($path);
    }

    return comcoAsset($path);
}

/**
 * Retourne l'URL d'un visuel attaché à une page CMS.
 *
 * @param  string|null  $path  Chemin relatif de l'image
 * @param  string  $source  Source du visuel (comco|theme|storage)
 * @return string URL publique du visuel
 */
function pageAsset(?string $path, string $source = 'theme'): string
{
    if ($path === null || $path === '') {
        return themeAsset('assets/img/news-1.jpg');
    }

    if ($source === 'storage' || str_starts_with($path, 'pages/') || str_starts_with($path, 'site-blocks/')) {
        return asset('storage/'.ltrim($path, '/'));
    }

    if ($source === 'comco') {
        return comcoAsset($path);
    }

    return themeAsset(str_starts_with($path, 'assets/') ? $path : 'assets/img/'.ltrim($path, '/'));
}

/**
 * Retourne l'URL publique d'un document juridique PDF.
 *
 * @param  string  $filename  Nom du fichier
 * @return string URL publique du document
 */
function pageLegalDocumentUrl(string $filename): string
{
    if (str_starts_with($filename, 'pages/legal/') || str_starts_with($filename, 'legal/')) {
        return asset('storage/'.ltrim($filename, '/'));
    }

    return legalDocumentUrl($filename);
}

/**
 * Retourne les sections routables avec leurs libellés.
 *
 * @return array<string, string> Sections disponibles
 */
function navigationSections(): array
{
    return SiteNavigation::sections();
}
