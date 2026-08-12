<?php

namespace Database\Seeders;

use App\Models\SiteBlock;
use Illuminate\Database\Seeder;

/**
 * Seeder du contenu dynamique de la page d'accueil COMCO.
 */
class HomeContentSeeder extends Seeder
{
    /**
     * Importe le contenu initial depuis config/institution.php.
     */
    public function run(): void
    {
        $this->seedSettings();
        $this->seedList(SiteBlock::TYPE_SLIDER, config('institution.slider', []), 'Diapositive');
        $this->seedList(SiteBlock::TYPE_WELCOME_ITEM, config('institution.welcomeItems', []), 'Bienvenue');
        $this->seedList(SiteBlock::TYPE_STORY_ITEM, config('institution.storyItems', []), 'Histoire');
        $this->seedList(SiteBlock::TYPE_SERVICE, config('institution.services', []), 'Mission');
        $this->seedList(SiteBlock::TYPE_WHY_CHOOSE, config('institution.whyChoose', []), 'Pourquoi');
        $this->seedList(SiteBlock::TYPE_FEATURE, config('institution.features', []), 'Ressource');
        $this->seedList(SiteBlock::TYPE_FUN_FACT, config('institution.funFacts', []), 'Chiffre');
        $this->seedList(SiteBlock::TYPE_ACTIVITY, config('institution.activities', []), 'Activité');
        $this->seedList(SiteBlock::TYPE_TESTIMONIAL, config('institution.testimonials', []), 'Témoignage');
        $this->seedList(SiteBlock::TYPE_PARTNER, config('institution.partners', []), 'Partenaire');

        $this->seedSingleton(
            SiteBlock::TYPE_FEATURED,
            config('institution.featured', []),
            'Contenu à la une',
        );

        $this->seedSingleton(
            SiteBlock::TYPE_LATEST_VIDEO,
            config('institution.latestVideo', []),
            'Vidéo institutionnelle',
        );

        $this->seedPromos();
    }

    /**
     * Crée ou met à jour les blocs promotionnels de la page d'accueil.
     */
    private function seedPromos(): void
    {
        $promos = [
            'alert_signalement' => [
                'label' => 'Alerte signalement',
                'payload' => [
                    'title' => 'Signaler une pratique abusive',
                    'text' => 'Toute personne peut signaler à la COMCO les ententes, abus de position dominante, fixation des prix ou pratiques trompeuses. Les signalements sont traités confidentiellement.',
                    'button_label' => 'Signaler maintenant',
                    'button_section' => 'e-services',
                    'button_slug' => 'signaler-pratique',
                ],
            ],
            'contact_cta' => [
                'label' => 'Bandeau contact',
                'payload' => [
                    'title' => 'Une question sur la concurrence ou les prix ? La COMCO est à votre écoute.',
                    'button_label' => 'Nous contacter',
                    'button_route' => 'contact',
                ],
            ],
            'legislation_promo' => [
                'label' => 'Promotion cadre juridique',
                'payload' => [
                    'section_title' => 'Législation congolaise en matière de concurrence',
                    'law_title' => 'LOI N°18/020 DU 09 JUILLET 2018',
                    'law_text' => 'Relative à la liberté des prix et à la concurrence',
                    'link_section' => 'centre-information',
                    'link_slug' => 'cadre-juridique',
                ],
            ],
            'talo_promo' => [
                'label' => 'Promotion TALO',
                'payload' => [
                    'title' => 'Bientôt disponible',
                    'text' => 'L\'Application TALO pour la surveillance des prix sur les marchés.',
                    'image' => 'talo.jpg',
                ],
            ],
            'fun_fact_header' => [
                'label' => 'En-tête chiffres clés',
                'payload' => [
                    'line_one' => 'Agir pour une concurrence loyale,',
                    'line_two' => 'au service de l\'économie congolaise.',
                ],
            ],
            'why_choose_image' => [
                'label' => 'Image Pourquoi la COMCO',
                'payload' => [
                    'image' => 'img4.jpg',
                    'image_source' => 'comco',
                ],
            ],
        ];

        foreach ($promos as $blockKey => $promo) {
            SiteBlock::query()->updateOrCreate(
                [
                    'page' => SiteBlock::PAGE_HOME,
                    'block_key' => $blockKey,
                ],
                [
                    'block_type' => SiteBlock::TYPE_PROMO,
                    'label' => $promo['label'],
                    'payload' => $promo['payload'],
                    'sort_order' => 0,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Crée ou met à jour les paramètres singleton de la page d'accueil.
     */
    private function seedSettings(): void
    {
        $settings = [
            'tagline' => [
                'label' => 'Slogan institutionnel',
                'value' => config('institution.tagline'),
            ],
            'home_tabs' => [
                'label' => 'Libellés des onglets actualités',
                'value' => config('institution.homeTabs'),
            ],
        ];

        foreach ($settings as $blockKey => $setting) {
            SiteBlock::query()->updateOrCreate(
                [
                    'page' => SiteBlock::PAGE_HOME,
                    'block_key' => $blockKey,
                ],
                [
                    'block_type' => SiteBlock::TYPE_SETTING,
                    'label' => $setting['label'],
                    'payload' => ['value' => $setting['value']],
                    'sort_order' => 0,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Crée ou met à jour une liste ordonnée de blocs.
     *
     * @param  string  $blockType  Type de bloc
     * @param  list<array<string, mixed>>  $items  Données source
     * @param  string  $labelPrefix  Préfixe du libellé admin
     */
    private function seedList(string $blockType, array $items, string $labelPrefix): void
    {
        foreach ($items as $index => $payload) {
            SiteBlock::query()->updateOrCreate(
                [
                    'page' => SiteBlock::PAGE_HOME,
                    'block_type' => $blockType,
                    'block_key' => $blockType.'-'.($index + 1),
                ],
                [
                    'label' => $labelPrefix.' '.($index + 1),
                    'payload' => $payload,
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Crée ou met à jour un bloc singleton sans clé métier.
     *
     * @param  string  $blockType  Type de bloc
     * @param  array<string, mixed>  $payload  Données source
     * @param  string  $label  Libellé admin
     */
    private function seedSingleton(string $blockType, array $payload, string $label): void
    {
        SiteBlock::query()->updateOrCreate(
            [
                'page' => SiteBlock::PAGE_HOME,
                'block_type' => $blockType,
                'block_key' => $blockType,
            ],
            [
                'label' => $label,
                'payload' => $payload,
                'sort_order' => 0,
                'is_active' => true,
            ],
        );
    }
}
