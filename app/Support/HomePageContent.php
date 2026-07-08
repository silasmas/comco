<?php

namespace App\Support;

use App\Models\SiteBlock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Agrège le contenu dynamique de la page d'accueil avec repli sur la configuration.
 */
class HomePageContent
{
    /**
     * @param  Collection<int, SiteBlock>  $blocks  Blocs actifs de la page d'accueil
     */
    public function __construct(
        private readonly Collection $blocks,
    ) {}

    /**
     * Charge le contenu de la page d'accueil depuis la base ou la configuration.
     *
     * @return self Instance prête pour la vue publique
     */
    public static function resolve(): self
    {
        if (! Schema::hasTable('site_blocks')) {
            return self::fromConfig();
        }

        $blocks = SiteBlock::query()
            ->forPage(SiteBlock::PAGE_HOME)
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($blocks->isEmpty()) {
            return self::fromConfig();
        }

        return new self($blocks);
    }

    /**
     * Construit le contenu exclusivement depuis config/institution.php.
     *
     * @return self Instance basée sur la configuration
     */
    public static function fromConfig(): self
    {
        return new self(collect());
    }

    /**
     * Retourne les diapositives du slider hero.
     *
     * @return list<array<string, mixed>>
     */
    public function slider(): array
    {
        return $this->items(SiteBlock::TYPE_SLIDER, config('institution.slider', []));
    }

    /**
     * Retourne le slogan institutionnel affiché sous le titre de bienvenue.
     *
     * @return string Texte du slogan
     */
    public function tagline(): string
    {
        return (string) $this->setting('tagline', config('institution.tagline', ''));
    }

    /**
     * Retourne les cartes de la section « Bienvenue ».
     *
     * @return list<array<string, mixed>>
     */
    public function welcomeItems(): array
    {
        return $this->items(SiteBlock::TYPE_WELCOME_ITEM, config('institution.welcomeItems', []));
    }

    /**
     * Retourne les blocs « Notre histoire ».
     *
     * @return list<array<string, mixed>>
     */
    public function storyItems(): array
    {
        return $this->items(SiteBlock::TYPE_STORY_ITEM, config('institution.storyItems', []));
    }

    /**
     * Retourne les missions / services mis en avant.
     *
     * @return list<array<string, mixed>>
     */
    public function services(): array
    {
        return $this->items(SiteBlock::TYPE_SERVICE, config('institution.services', []));
    }

    /**
     * Retourne les arguments « Pourquoi la COMCO ».
     *
     * @return list<array<string, mixed>>
     */
    public function whyChoose(): array
    {
        return $this->items(SiteBlock::TYPE_WHY_CHOOSE, config('institution.whyChoose', []));
    }

    /**
     * Retourne les ressources institutionnelles.
     *
     * @return list<array<string, mixed>>
     */
    public function features(): array
    {
        return $this->items(SiteBlock::TYPE_FEATURE, config('institution.features', []));
    }

    /**
     * Retourne les chiffres clés animés.
     *
     * @return list<array<string, mixed>>
     */
    public function funFacts(): array
    {
        return $this->items(SiteBlock::TYPE_FUN_FACT, config('institution.funFacts', []));
    }

    /**
     * Retourne le contenu « À la une ».
     *
     * @return array<string, mixed> Données du bloc mis en avant
     */
    public function featured(): array
    {
        $items = $this->items(SiteBlock::TYPE_FEATURED, [config('institution.featured', [])]);

        return $items[0] ?? [];
    }

    /**
     * Retourne les activités institutionnelles.
     *
     * @return list<array<string, mixed>>
     */
    public function activities(): array
    {
        return $this->items(SiteBlock::TYPE_ACTIVITY, config('institution.activities', []));
    }

    /**
     * Retourne les libellés des onglets actualités / activités.
     *
     * @return array<string, string> Clés et libellés des onglets
     */
    public function homeTabs(): array
    {
        $tabs = $this->setting('home_tabs', config('institution.homeTabs', []));

        return is_array($tabs) ? $tabs : config('institution.homeTabs', []);
    }

    /**
     * Retourne les témoignages du carousel.
     *
     * @return list<array<string, mixed>>
     */
    public function testimonials(): array
    {
        return $this->items(SiteBlock::TYPE_TESTIMONIAL, config('institution.testimonials', []));
    }

    /**
     * Retourne les logos partenaires.
     *
     * @return list<array<string, mixed>>
     */
    public function partners(): array
    {
        return $this->items(SiteBlock::TYPE_PARTNER, config('institution.partners', []));
    }

    /**
     * Retourne la vidéo institutionnelle mise en avant.
     *
     * @return array<string, mixed> Données de la vidéo
     */
    public function latestVideo(): array
    {
        $items = $this->items(SiteBlock::TYPE_LATEST_VIDEO, [config('institution.latestVideo', [])]);

        return $items[0] ?? config('institution.latestVideo', []);
    }

    /**
     * Retourne le bloc d'alerte « Signaler une pratique ».
     *
     * @return array<string, string> Contenu du bloc
     */
    public function alertSignalement(): array
    {
        return $this->promo('alert_signalement', [
            'title' => 'Signaler une pratique abusive',
            'text' => 'Toute personne peut signaler à la COMCO les ententes, abus de position dominante, fixation des prix ou pratiques trompeuses. Les signalements sont traités confidentiellement.',
            'button_label' => 'Signaler maintenant',
            'button_section' => 'e-services',
            'button_slug' => 'signaler-pratique',
        ]);
    }

    /**
     * Retourne le bandeau d'appel au contact.
     *
     * @return array<string, string> Contenu du bandeau
     */
    public function contactCta(): array
    {
        return $this->promo('contact_cta', [
            'title' => 'Une question sur la concurrence ou les prix ? La COMCO est à votre écoute.',
            'button_label' => 'Nous contacter',
            'button_route' => 'contact',
        ]);
    }

    /**
     * Retourne le bloc promotionnel du cadre juridique.
     *
     * @return array<string, string> Contenu du bloc
     */
    public function legislationPromo(): array
    {
        return $this->promo('legislation_promo', [
            'section_title' => 'Législation congolaise en matière de concurrence',
            'law_title' => 'LOI N°18/020 DU 09 JUILLET 2018',
            'law_text' => 'Relative à la liberté des prix et à la concurrence',
            'link_section' => 'centre-information',
            'link_slug' => 'cadre-juridique',
        ]);
    }

    /**
     * Retourne le bloc promotionnel de l'application TALO.
     *
     * @return array<string, string> Contenu du bloc
     */
    public function taloPromo(): array
    {
        return $this->promo('talo_promo', [
            'title' => 'Bientôt disponible',
            'text' => 'L\'Application TALO pour la surveillance des prix sur les marchés.',
            'image' => 'talo.jpg',
        ]);
    }

    /**
     * Retourne l'en-tête de la section chiffres clés.
     *
     * @return array<string, string> Titres affichés
     */
    public function funFactHeader(): array
    {
        return $this->promo('fun_fact_header', [
            'line_one' => 'Agir pour une concurrence loyale,',
            'line_two' => 'au service de l\'économie congolaise.',
        ]);
    }

    /**
     * Retourne l'image de la section « Pourquoi la COMCO ».
     *
     * @return array<string, string> Chemin de l'image
     */
    public function whyChooseImage(): array
    {
        return $this->promo('why_choose_image', [
            'image' => 'img4.jpg',
            'image_source' => 'comco',
        ]);
    }

    /**
     * Retourne les éléments d'un type donné depuis la base ou la configuration.
     *
     * @param  string  $blockType  Type de bloc recherché
     * @param  list<array<string, mixed>>  $fallback  Valeurs par défaut
     * @return list<array<string, mixed>> Éléments normalisés pour la vue
     */
    private function items(string $blockType, array $fallback): array
    {
        $blocks = $this->blocks
            ->where('block_type', $blockType)
            ->values();

        if ($blocks->isEmpty()) {
            return $fallback;
        }

        return $blocks
            ->map(fn (SiteBlock $block): array => $block->payload ?? [])
            ->all();
    }

    /**
     * Retourne la valeur d'un paramètre singleton de la page d'accueil.
     *
     * @param  string  $blockKey  Clé du paramètre
     * @param  mixed  $fallback  Valeur par défaut
     * @return mixed Valeur du paramètre
     */
    private function setting(string $blockKey, mixed $fallback): mixed
    {
        if ($this->blocks->isEmpty()) {
            return $fallback;
        }

        $block = $this->blocks->first(
            fn (SiteBlock $item): bool => $item->block_type === SiteBlock::TYPE_SETTING
              && $item->block_key === $blockKey
        );

        if ($block === null) {
            return $fallback;
        }

        return $block->payload['value'] ?? $fallback;
    }

    /**
     * Retourne un bloc promotionnel de la page d'accueil.
     *
     * @param  string  $blockKey  Clé du bloc
     * @param  array<string, string>  $fallback  Valeurs par défaut
     * @return array<string, string> Contenu du bloc
     */
    private function promo(string $blockKey, array $fallback): array
    {
        if ($this->blocks->isEmpty()) {
            return $fallback;
        }

        $block = $this->blocks->first(
            fn (SiteBlock $item): bool => $item->block_type === SiteBlock::TYPE_PROMO
              && $item->block_key === $blockKey
        );

        if ($block === null || ! is_array($block->payload)) {
            return $fallback;
        }

        return array_merge($fallback, $block->payload);
    }
}
