<?php

namespace App\Http\Controllers\Admin;

use App\Filament\Pages\SiteInstallation;
use App\Http\Controllers\Controller;
use App\Support\SiteDeploymentState;
use App\Support\SiteInstaller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Exécute les actions d'installation hors requêtes Livewire (config:cache incompatible).
 */
class InstallationActionController extends Controller
{
    /**
     * Exécute les migrations de base de données.
     */
    public function migrate(): RedirectResponse
    {
        $this->ensureAccess();

        try {
            $message = SiteInstaller::runMigrations();
        } catch (\Throwable $exception) {
            return $this->backToInstallation('error', 'Migrations impossible', $exception->getMessage());
        }

        return $this->backToInstallation('success', 'Migrations exécutées', $message);
    }

    /**
     * Crée le lien symbolique public/storage.
     */
    public function storageLink(): RedirectResponse
    {
        $this->ensureAccess();

        try {
            $message = SiteInstaller::createStorageLink();
        } catch (\Throwable $exception) {
            return $this->backToInstallation('error', 'Lien storage impossible', $exception->getMessage());
        }

        return $this->backToInstallation('success', 'Lien storage', $message);
    }

    /**
     * Met en cache la configuration pour la production.
     */
    public function optimize(): RedirectResponse
    {
        $this->ensureAccess();

        try {
            $message = SiteInstaller::optimizeApplication(full: false);
        } catch (\Throwable $exception) {
            return $this->backToInstallation('error', 'Optimisation impossible', $exception->getMessage());
        }

        return $this->backToInstallation('success', 'Optimisation terminée', $message);
    }

    /**
     * Exécute migrations, lien storage et optimisation légère.
     */
    public function runAll(): RedirectResponse
    {
        $this->ensureAccess();

        try {
            $messages = [
                SiteInstaller::runMigrations(),
                SiteInstaller::createStorageLink(),
                SiteInstaller::runContentSeeders(),
                SiteInstaller::optimizeApplication(full: false),
            ];
        } catch (\Throwable $exception) {
            return $this->backToInstallation('error', 'Installation impossible', $exception->getMessage());
        }

        return $this->backToInstallation('success', 'Installation terminée', implode(' | ', $messages));
    }

    /**
     * Importe les articles de démonstration via le seeder.
     */
    public function seedPosts(): RedirectResponse
    {
        $this->ensureAccess();

        try {
            $message = SiteInstaller::runPostsSeeder();
        } catch (\Throwable $exception) {
            return $this->backToInstallation('error', 'Seeder impossible', $exception->getMessage());
        }

        return $this->backToInstallation('success', 'Articles importés', $message);
    }

    /**
     * Importe l'ensemble des contenus institutionnels dynamiques.
     */
    public function seedContent(): RedirectResponse
    {
        $this->ensureAccess();

        try {
            $message = SiteInstaller::runContentSeeders();
        } catch (\Throwable $exception) {
            return $this->backToInstallation('error', 'Import des contenus impossible', $exception->getMessage());
        }

        return $this->backToInstallation('success', 'Contenus importés', $message);
    }

    /**
     * Finalise le déploiement et redirige vers le tableau de bord.
     */
    public function launch(): RedirectResponse
    {
        $this->ensureAccess();

        try {
            SiteInstaller::finalizeDeployment();
        } catch (\Throwable $exception) {
            return $this->backToInstallation('error', 'Mise en ligne impossible', $exception->getMessage());
        }

        return redirect()
            ->to(SiteDeploymentState::adminPathPrefix())
            ->with('installation_notice', [
                'type' => 'success',
                'title' => 'Site mis en ligne',
                'body' => 'Le site public est maintenant accessible. Connectez-vous au panneau d\'administration.',
            ]);
    }

    /**
     * Vérifie que l'action d'installation est autorisée.
     */
    private function ensureAccess(): void
    {
        if (SiteDeploymentState::requiresInstallation()) {
            return;
        }

        $user = Auth::user();

        if ($user !== null && $user->is_super_admin) {
            return;
        }

        abort(403);
    }

    /**
     * Redirige vers la page d'installation avec un message flash.
     *
     * @param  string  $type  Type de notification (success|error)
     * @param  string  $title  Titre affiché
     * @param  string  $body  Détail du message
     */
    private function backToInstallation(string $type, string $title, string $body): RedirectResponse
    {
        $url = $this->installationReturnUrl();

        return redirect()
            ->to($url)
            ->with('installation_notice', compact('type', 'title', 'body'));
    }

    /**
     * URL de retour après une action (Filament si connecté, page standalone sinon).
     */
    private function installationReturnUrl(): string
    {
        if (Auth::check() && ! SiteDeploymentState::requiresInstallation()) {
            return SiteInstallation::getUrl();
        }

        return SiteDeploymentState::installationPath();
    }
}
