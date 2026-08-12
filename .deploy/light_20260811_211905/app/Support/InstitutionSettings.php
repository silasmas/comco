<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

/**
 * Charge et persiste les paramètres institutionnels dynamiques.
 */
class InstitutionSettings
{
    /**
     * Retourne la configuration institutionnelle fusionnée (BDD + config).
     *
     * @return array<string, mixed> Configuration complète
     */
    public static function resolve(): array
    {
        return array_replace_recursive(config('institution', []), self::fromDatabase());
    }

    /**
     * Retourne les données du formulaire Filament.
     *
     * @return array<string, mixed> État initial du formulaire
     */
    public static function forForm(): array
    {
        $config = self::resolve();

        return [
            'name' => $config['name'] ?? '',
            'fullName' => $config['fullName'] ?? '',
            'shortName' => $config['shortName'] ?? '',
            'tagline' => $config['tagline'] ?? '',
            'contactEmail' => $config['contact']['email'] ?? '',
            'contactPhone' => $config['contact']['phone'] ?? '',
            'contactAddress' => $config['contact']['address'] ?? '',
            'contactMapEmbedUrl' => $config['contact']['mapEmbedUrl'] ?? '',
            'contactMapLinkUrl' => $config['contact']['mapLinkUrl'] ?? '',
            'socialTwitter' => $config['social']['twitter'] ?? '',
            'socialFacebook' => $config['social']['facebook'] ?? '',
            'socialLinkedin' => $config['social']['linkedin'] ?? '',
            'socialYoutube' => $config['social']['youtube'] ?? '',
            'seoTitleSuffix' => $config['seo']['titleSuffix'] ?? '',
            'seoDefaultDescription' => $config['seo']['defaultDescription'] ?? '',
        ];
    }

    /**
     * Persiste les paramètres saisis dans l'administration.
     *
     * @param  array<string, mixed>  $data  Données validées du formulaire
     */
    public static function persist(array $data): void
    {
        $map = [
            'institution.name' => $data['name'] ?? null,
            'institution.fullName' => $data['fullName'] ?? null,
            'institution.shortName' => $data['shortName'] ?? null,
            'institution.tagline' => $data['tagline'] ?? null,
            'institution.contact.email' => $data['contactEmail'] ?? null,
            'institution.contact.phone' => $data['contactPhone'] ?? null,
            'institution.contact.address' => $data['contactAddress'] ?? null,
            'institution.contact.mapEmbedUrl' => $data['contactMapEmbedUrl'] ?? null,
            'institution.contact.mapLinkUrl' => $data['contactMapLinkUrl'] ?? null,
            'institution.social.twitter' => $data['socialTwitter'] ?? null,
            'institution.social.facebook' => $data['socialFacebook'] ?? null,
            'institution.social.linkedin' => $data['socialLinkedin'] ?? null,
            'institution.social.youtube' => $data['socialYoutube'] ?? null,
            'institution.seo.titleSuffix' => $data['seoTitleSuffix'] ?? null,
            'institution.seo.defaultDescription' => $data['seoDefaultDescription'] ?? null,
        ];

        foreach ($map as $key => $value) {
            SiteSetting::store($key, $value);
        }
    }

    /**
     * Retourne les données initiales du formulaire depuis config/institution.php.
     *
     * @return array<string, mixed> Valeurs par défaut de l'institution
     */
    public static function defaultFormFromConfig(): array
    {
        $config = config('institution', []);

        return [
            'name' => $config['name'] ?? '',
            'fullName' => $config['fullName'] ?? '',
            'shortName' => $config['shortName'] ?? '',
            'tagline' => $config['tagline'] ?? '',
            'contactEmail' => $config['contact']['email'] ?? '',
            'contactPhone' => $config['contact']['phone'] ?? '',
            'contactAddress' => $config['contact']['address'] ?? '',
            'contactMapEmbedUrl' => $config['contact']['mapEmbedUrl'] ?? '',
            'contactMapLinkUrl' => $config['contact']['mapLinkUrl'] ?? '',
            'socialTwitter' => $config['social']['twitter'] ?? '',
            'socialFacebook' => $config['social']['facebook'] ?? '',
            'socialLinkedin' => $config['social']['linkedin'] ?? '',
            'socialYoutube' => $config['social']['youtube'] ?? '',
            'seoTitleSuffix' => $config['seo']['titleSuffix'] ?? '',
            'seoDefaultDescription' => $config['seo']['defaultDescription'] ?? '',
        ];
    }

    /**
     * Enregistre les paramètres institutionnels par défaut si la table est vide.
     */
    public static function seedDefaultsIfMissing(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        if (SiteSetting::query()->exists()) {
            return;
        }

        self::persist(self::defaultFormFromConfig());
    }

    /**
     * Applique les paramètres dynamiques à la configuration Laravel.
     */
    public static function applyToConfig(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        config(['institution' => self::resolve()]);
    }

    /**
     * Construit la structure institution depuis la base de données.
     *
     * @return array<string, mixed> Valeurs enregistrées
     */
    private static function fromDatabase(): array
    {
        if (! Schema::hasTable('site_settings')) {
            return [];
        }

        $settings = SiteSetting::query()->pluck('value', 'key');
        $nested = [];

        foreach ($settings as $key => $value) {
            if (! str_starts_with((string) $key, 'institution.')) {
                continue;
            }

            $path = str_replace('institution.', '', (string) $key);
            Arr::set($nested, $path, $value);
        }

        return $nested;
    }
}
