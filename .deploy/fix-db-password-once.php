<?php

/**
 * Met à jour DB_PASSWORD dans .env (usage one-shot).
 * Usage: php fix-db-password-once.php /chemin/projet MOT_DE_PASSE
 */

declare(strict_types=1);

$root = $argv[1] ?? '';
$pass = $argv[2] ?? '';
if ($root === '' || $pass === '' || ! is_file($root.'/.env')) {
  fwrite(STDERR, "USAGE: php fix-db-password-once.php /path/to/laravel PASSWORD\n");
  exit(1);
}

$envPath = $root.'/.env';
$env = file_get_contents($envPath);
$env = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD='.$pass, $env);
$env = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST=127.0.0.1', $env);
$env = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=u911414181_comco', $env);
$env = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME=u911414181_comco', $env);
file_put_contents($envPath, $env);
echo "ENVOK\n";
