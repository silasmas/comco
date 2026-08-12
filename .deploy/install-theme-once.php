<?php

/**
 * Installe public/theme depuis GitHub sur le serveur Hostinger.
 * Placer à la racine Laravel (à côté de artisan) puis: php install-theme-once.php
 */

declare(strict_types=1);

$root = is_file(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);
$publicDir = $root . '/public';
$themeDir = $publicDir . '/theme';
$tmpZip = sys_get_temp_dir() . '/comco-main.zip';
$tmpExtract = sys_get_temp_dir() . '/comco-theme-extract';
$zipUrl = 'https://codeload.github.com/silasmas/comco/zip/refs/heads/main';

/**
 * Affiche un message et termine en erreur.
 *
 * @param  string  $message  Message d'erreur
 */
function fail(string $message): void
{
  fwrite(STDERR, $message . PHP_EOL);
  exit(1);
}

if (! is_dir($publicDir)) {
  fail('Dossier public introuvable: ' . $publicDir);
}

echo "Téléchargement du dépôt...\n";
$context = stream_context_create([
  'http' => [
    'follow_location' => 1,
    'timeout' => 300,
    'header' => "User-Agent: COMCO-Theme-Installer\r\n",
  ],
]);
$zipData = @file_get_contents($zipUrl, false, $context);
if ($zipData === false || strlen($zipData) < 1000) {
  fail('Téléchargement GitHub impossible.');
}
file_put_contents($tmpZip, $zipData);
echo 'ZIP: ' . strlen($zipData) . " octets\n";

if (is_dir($tmpExtract)) {
  passthru('rm -rf ' . escapeshellarg($tmpExtract));
}
mkdir($tmpExtract, 0755, true);

$zip = new ZipArchive();
if ($zip->open($tmpZip) !== true) {
  fail('Ouverture ZIP impossible.');
}

for ($i = 0; $i < $zip->numFiles; $i++) {
  $name = $zip->getNameIndex($i);
  if ($name === false || ! str_starts_with($name, 'comco-main/public/theme/')) {
    continue;
  }
  $zip->extractTo($tmpExtract, $name);
}
$zip->close();

$extractedTheme = $tmpExtract . '/comco-main/public/theme';
if (! is_dir($extractedTheme) || ! is_file($extractedTheme . '/assets/css/theme.min.css')) {
  fail('Thème extrait invalide.');
}

if (is_dir($themeDir)) {
  passthru('rm -rf ' . escapeshellarg($themeDir));
}
passthru('cp -a ' . escapeshellarg($extractedTheme) . ' ' . escapeshellarg($themeDir), $code);
if ($code !== 0) {
  fail('Copie du thème échouée.');
}

@unlink($tmpZip);
passthru('rm -rf ' . escapeshellarg($tmpExtract));

$css = $themeDir . '/assets/css/theme.min.css';
echo 'OK theme installé: ' . $css . ' (' . filesize($css) . " octets)\n";
echo "Supprimez install-theme-once.php manuellement après vérification.\n";
