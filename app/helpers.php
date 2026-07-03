<?php

/**
 * Retourne l'URL d'un asset du thème Elixir.
 *
 * @param string $path Chemin relatif depuis public/theme/
 * @return string URL publique de l'asset
 */
function themeAsset(string $path): string
{
  return asset('theme/' . ltrim($path, '/'));
}

/**
 * Retourne l'URL d'un asset institutionnel COMCO.
 *
 * @param string $path Chemin relatif depuis public/assets/
 * @return string URL publique de l'asset
 */
function comcoAsset(string $path): string
{
  return asset('assets/' . ltrim($path, '/'));
}

/**
 * Retourne le gabarit Elixir associé à une page CMS.
 *
 * @param string $section Section de navigation
 * @param string $slug Identifiant URL de la page
 * @return string Nom du gabarit Blade
 */
function pageTemplate(string $section, string $slug): string
{
  return config("page-templates.{$section}.{$slug}")
    ?? config('page-templates.default', 'blank');
}

/**
 * Retourne l'URL publique d'un document juridique PDF.
 *
 * @param string $filename Nom du fichier dans public/assets/documents/
 * @return string URL publique du document
 */
function legalDocumentUrl(string $filename): string
{
  return comcoAsset('documents/' . ltrim($filename, '/'));
}

/**
 * Retourne l'URL de l'image d'un article (thème Elixir ou stockage).
 *
 * @param string|null $path Chemin relatif enregistré sur le post
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

  return asset('storage/' . ltrim($path, '/'));
}

/**
 * Retourne l'URL absolue du logo COMCO pour les emails.
 *
 * @return string URL publique du logo
 */
function comcoMailLogoUrl(): string
{
  return rtrim((string) config('app.url'), '/') . '/assets/logo01.png';
}
