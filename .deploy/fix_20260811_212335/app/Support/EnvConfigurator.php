<?php

namespace App\Support;

/**
 * Met à jour les variables du fichier .env de l'application.
 */
class EnvConfigurator
{
  /**
   * Enregistre ou met à jour plusieurs clés dans le fichier .env.
   *
   * @param array<string, string|null> $values Paires clé / valeur
   * @return array<string, string> Clés effectivement mises à jour
   */
  public static function set(array $values): array
  {
    $envPath = base_path('.env');

    if (! is_file($envPath)) {
      return [];
    }

    $content = file_get_contents($envPath);
    $updated = [];

    foreach ($values as $key => $value) {
      if ($value === null) {
        continue;
      }

      $line = $key . '=' . self::formatValue($value);
      $pattern = '/^' . preg_quote($key, '/') . '=.*/m';

      if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, $line, $content);
      } else {
        $content = rtrim($content) . PHP_EOL . $line . PHP_EOL;
      }

      $updated[$key] = $value;
    }

    file_put_contents($envPath, $content);

    return $updated;
  }

  /**
   * Formate une valeur pour le fichier .env.
   *
   * @param string $value Valeur brute
   * @return string Valeur échappée si nécessaire
   */
  private static function formatValue(string $value): string
  {
    if ($value === '') {
      return '""';
    }

    if (preg_match('/[\s#="\'\\\\]/', $value)) {
      return '"' . str_replace('"', '\\"', $value) . '"';
    }

    return $value;
  }
}
