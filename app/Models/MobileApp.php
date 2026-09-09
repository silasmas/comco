<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Application Android (APK) publiée via le back-office pour partage lien / QR code.
 */
class MobileApp extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'title',
    'slug',
    'version',
    'file_path',
    'original_name',
    'file_size',
    'download_count',
    'is_active',
    'notes',
  ];

  /**
   * Cast des attributs.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'is_active' => 'boolean',
      'file_size' => 'integer',
      'download_count' => 'integer',
    ];
  }

  /**
   * Scope des applications actives.
   *
   * @param  Builder<MobileApp>  $query  Requête
   * @return Builder<MobileApp>
   */
  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }

  /**
   * URL publique de la page de partage (lien + QR).
   *
   * @return string URL absolue
   */
  public function shareUrl(): string
  {
    return route('mobile-apps.show', ['slug' => $this->slug]);
  }

  /**
   * URL de téléchargement forcé de l'APK.
   *
   * @return string URL absolue
   */
  public function downloadUrl(): string
  {
    return route('mobile-apps.download', ['slug' => $this->slug]);
  }

  /**
   * URL d'image QR code pointant vers le lien de partage.
   *
   * @param  int  $size  Taille en pixels
   * @return string URL du QR
   */
  public function qrCodeImageUrl(int $size = 280): string
  {
    $size = max(120, min(800, $size));

    return 'https://api.qrserver.com/v1/create-qr-code/?size='.$size.'x'.$size.'&data='.rawurlencode($this->shareUrl());
  }

  /**
   * Indique si le fichier APK est présent sur le disque public.
   *
   * @return bool true si le fichier existe
   */
  public function fileExists(): bool
  {
    return filled($this->file_path) && Storage::disk('public')->exists($this->file_path);
  }

  /**
   * Chemin absolu du fichier APK sur le serveur.
   *
   * @return string|null Chemin ou null
   */
  public function absoluteFilePath(): ?string
  {
    if (! $this->fileExists()) {
      return null;
    }

    return Storage::disk('public')->path($this->file_path);
  }

  /**
   * Nom de fichier proposé au téléchargement.
   *
   * @return string Nom .apk
   */
  public function downloadFileName(): string
  {
    if (filled($this->original_name) && str_ends_with(strtolower($this->original_name), '.apk')) {
      return $this->original_name;
    }

    $base = Str::slug($this->title) ?: 'application';
    $version = filled($this->version) ? '-'.Str::slug($this->version) : '';

    return $base.$version.'.apk';
  }

  /**
   * Taille humaine du fichier.
   *
   * @return string|null Ex. "12,4 Mo"
   */
  public function humanFileSize(): ?string
  {
    if (! $this->file_size) {
      return null;
    }

    $bytes = (float) $this->file_size;
    $units = ['o', 'Ko', 'Mo', 'Go'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
      $bytes /= 1024;
      $i++;
    }

    return number_format($bytes, $i === 0 ? 0 : 1, ',', ' ').' '.$units[$i];
  }
}
