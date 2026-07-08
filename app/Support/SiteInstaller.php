<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

/**
 * Opérations d'installation et de mise en production du site COMCO.
 */
class SiteInstaller
{
    /**
     * Exécute les migrations de base de données.
     *
     * @return string Sortie console de la commande migrate
     */
    public static function runMigrations(): string
    {
        Artisan::call('migrate', ['--force' => true]);

        return trim(Artisan::output()) ?: 'Migrations exécutées avec succès.';
    }

    /**
     * Crée le lien symbolique public/storage.
     *
     * @return string Message décrivant le résultat
     */
    public static function createStorageLink(): string
    {
        if (file_exists(public_path('storage'))) {
            return 'Le lien symbolique public/storage existe déjà.';
        }

        Artisan::call('storage:link');

        return trim(Artisan::output()) ?: 'Lien storage créé avec succès.';
    }

    /**
     * Met en cache la configuration pour la production.
     *
     * @param  bool  $full  Si true, met aussi routes et vues en cache (à la fin de l'installation)
     * @return string Message de confirmation
     */
    public static function optimizeApplication(bool $full = false): string
    {
        if (SiteDeploymentState::isLocalHostRequest()) {
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return 'En local : caches vidés. Le cache production complet sera appliqué automatiquement à la mise en ligne sur le serveur.';
        }

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        if (! $full) {
            return 'Configuration mise en cache. Routes et vues : cache appliqué à la mise en ligne du site.';
        }

        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return 'Configuration, routes et vues mises en cache pour la production.';
    }

    /**
     * Crée ou met à jour un super administrateur Filament.
     *
     * @param  string  $name  Nom affiché
     * @param  string  $email  Adresse email de connexion
     * @param  string  $password  Mot de passe en clair (sera hashé)
     * @return User Utilisateur créé ou mis à jour
     */
    public static function createSuperAdmin(string $name, string $email, string $password): User
    {
        return User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ],
        );
    }

    /**
     * Applique la configuration de base dans le fichier .env.
     *
     * @param  array<string, string>  $values  Variables à enregistrer
     * @return array<string, string> Clés mises à jour
     */
    public static function applyBaseConfiguration(array $values): array
    {
        $envValues = [
            'APP_URL' => $values['app_url'] ?? null,
            'APP_ENV' => $values['app_env'] ?? null,
            'APP_DEBUG' => $values['app_debug'] ?? null,
            'INSTITUTION_EMAIL' => $values['institution_email'] ?? null,
            'INSTITUTION_FULL_NAME' => $values['institution_full_name'] ?? null,
            'INSTITUTION_NAME' => $values['institution_name'] ?? null,
            'MAIL_FROM_ADDRESS' => $values['mail_from_address'] ?? null,
            'MAIL_FROM_NAME' => $values['mail_from_name'] ?? null,
        ];

        $updated = EnvConfigurator::set(array_filter($envValues, fn ($value) => $value !== null && $value !== ''));

        if ($updated !== []) {
            Artisan::call('config:clear');
        }

        return $updated;
    }

    /**
     * Exécute le seeder des contenus institutionnels complets.
     *
     * @return string Message de confirmation
     */
    public static function runContentSeeders(): string
    {
        if (! SiteDeploymentState::databaseIsReady()) {
            throw new \RuntimeException('Exécutez d\'abord les migrations avant d\'importer les contenus.');
        }

        $seeders = [
            'Database\\Seeders\\InstitutionSeeder',
            'Database\\Seeders\\InstitutionSettingsSeeder',
            'Database\\Seeders\\NavigationSeeder',
            'Database\\Seeders\\HomeContentSeeder',
            'Database\\Seeders\\ContactContentSeeder',
            'Database\\Seeders\\PageAttachmentsSeeder',
            'Database\\Seeders\\EServiceDefinitionSeeder',
        ];

        foreach ($seeders as $seederClass) {
            Artisan::call('db:seed', [
                '--class' => $seederClass,
                '--force' => true,
            ]);
        }

        return trim(Artisan::output()) ?: 'Contenus institutionnels importés avec succès.';
    }

    /**
     * Exécute le seeder des articles d'actualité.
     *
     * @return string Message de confirmation
     */
    public static function runPostsSeeder(): string
    {
        if (! SiteDeploymentState::databaseIsReady()) {
            throw new \RuntimeException('Exécutez d\'abord les migrations avant d\'importer les articles.');
        }

        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\PostsSeeder',
            '--force' => true,
        ]);

        return trim(Artisan::output()) ?: '15 articles d\'actualité importés ou mis à jour avec succès.';
    }

    /**
     * Finalise le déploiement et rend le site public accessible.
     *
     * @throws \RuntimeException Si aucun super administrateur n'existe
     */
    public static function finalizeDeployment(): void
    {
        if (! SiteDeploymentState::hasSuperAdmin()) {
            throw new \RuntimeException('Créez d\'abord un super administrateur avant de mettre le site en ligne.');
        }

        SiteDeploymentState::markAsInstalled();

        if (! SiteDeploymentState::isLocalHostRequest()) {
            self::optimizeApplication(full: true);
        }
    }
}
