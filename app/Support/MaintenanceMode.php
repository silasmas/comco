<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

/**
 * Gère l'état et le contenu du mode maintenance du site public.
 */
class MaintenanceMode
{
    public const KEY_ENABLED = 'maintenance.enabled';

    public const KEY_TITLE = 'maintenance.title';

    public const KEY_MESSAGE = 'maintenance.message';

    /**
     * Indique si le mode maintenance est actif.
     *
     * @return bool True si le site public doit être masqué
     */
    public static function isEnabled(): bool
    {
        if (! self::tableReady()) {
            return false;
        }

        return SiteSetting::value(self::KEY_ENABLED, '0') === '1';
    }

    /**
     * Retourne le titre affiché sur la page de maintenance.
     *
     * @return string Titre de la page
     */
    public static function title(): string
    {
        $default = 'Site en maintenance';

        if (! self::tableReady()) {
            return $default;
        }

        $title = SiteSetting::value(self::KEY_TITLE, $default);

        return filled($title) ? (string) $title : $default;
    }

    /**
     * Retourne le message affiché sur la page de maintenance.
     *
     * @return string Message destiné aux visiteurs
     */
    public static function message(): string
    {
        $default = 'La '.config('institution.fullName', 'Commission de la Concurrence')
            .' effectue actuellement des opérations de maintenance. Merci de revenir prochainement.';

        if (! self::tableReady()) {
            return $default;
        }

        $message = SiteSetting::value(self::KEY_MESSAGE, $default);

        return filled($message) ? (string) $message : $default;
    }

    /**
     * Active le mode maintenance.
     */
    public static function enable(): void
    {
        if (! self::tableReady()) {
            return;
        }

        SiteSetting::store(self::KEY_ENABLED, '1');
    }

    /**
     * Désactive le mode maintenance.
     */
    public static function disable(): void
    {
        if (! self::tableReady()) {
            return;
        }

        SiteSetting::store(self::KEY_ENABLED, '0');
    }

    /**
     * Met à jour l'état et le contenu du mode maintenance.
     *
     * @param  array{enabled?: bool, title?: string, message?: string}  $data  Données du formulaire
     */
    public static function update(array $data): void
    {
        if (! self::tableReady()) {
            return;
        }

        if (array_key_exists('enabled', $data)) {
            $enabled = filter_var($data['enabled'], FILTER_VALIDATE_BOOLEAN);
            SiteSetting::store(self::KEY_ENABLED, $enabled ? '1' : '0');
        }

        if (array_key_exists('title', $data)) {
            SiteSetting::store(self::KEY_TITLE, (string) ($data['title'] ?? ''));
        }

        if (array_key_exists('message', $data)) {
            SiteSetting::store(self::KEY_MESSAGE, (string) ($data['message'] ?? ''));
        }
    }

    /**
     * Retourne l'état initial du formulaire Filament.
     *
     * @return array{enabled: bool, title: string, message: string} Données du formulaire
     */
    public static function forForm(): array
    {
        return [
            'enabled' => self::isEnabled(),
            'title' => self::title(),
            'message' => self::message(),
        ];
    }

    /**
     * Vérifie que la table des paramètres est disponible.
     *
     * @return bool True si les lectures/écritures sont possibles
     */
    private static function tableReady(): bool
    {
        try {
            return Schema::hasTable('site_settings');
        } catch (\Throwable) {
            return false;
        }
    }
}
