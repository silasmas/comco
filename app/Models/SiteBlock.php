<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Bloc de contenu dynamique d'une page publique (accueil, contact, etc.).
 */
class SiteBlock extends Model
{
    public const PAGE_HOME = 'home';

    public const PAGE_CONTACT = 'contact';

    public const TYPE_SLIDER = 'slider';

    public const TYPE_WELCOME_ITEM = 'welcome_item';

    public const TYPE_STORY_ITEM = 'story_item';

    public const TYPE_SERVICE = 'service';

    public const TYPE_WHY_CHOOSE = 'why_choose';

    public const TYPE_FEATURE = 'feature';

    public const TYPE_FUN_FACT = 'fun_fact';

    public const TYPE_FEATURED = 'featured';

    public const TYPE_ACTIVITY = 'activity';

    public const TYPE_TESTIMONIAL = 'testimonial';

    public const TYPE_PARTNER = 'partner';

    public const TYPE_LATEST_VIDEO = 'latest_video';

    public const TYPE_SETTING = 'setting';

    public const TYPE_INFO_CARD = 'info_card';

    public const TYPE_CTA = 'cta';

    public const TYPE_PROMO = 'promo';

    /**
     * Attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'page',
        'block_type',
        'block_key',
        'label',
        'payload',
        'sort_order',
        'is_active',
    ];

    /**
     * Cast des attributs du modèle.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Libellés des types de blocs pour l'administration.
     *
     * @return array<string, string>
     */
    public static function blockTypeLabels(): array
    {
        return [
            self::TYPE_SLIDER => 'Diapositive du slider',
            self::TYPE_WELCOME_ITEM => 'Carte « Bienvenue »',
            self::TYPE_STORY_ITEM => 'Bloc « Notre histoire »',
            self::TYPE_SERVICE => 'Mission / service',
            self::TYPE_WHY_CHOOSE => 'Bloc « Pourquoi la COMCO »',
            self::TYPE_FEATURE => 'Ressource',
            self::TYPE_FUN_FACT => 'Chiffre clé',
            self::TYPE_FEATURED => 'Contenu « À la une »',
            self::TYPE_ACTIVITY => 'Activité',
            self::TYPE_TESTIMONIAL => 'Témoignage',
            self::TYPE_PARTNER => 'Logo partenaire',
            self::TYPE_LATEST_VIDEO => 'Vidéo mise en avant',
            self::TYPE_SETTING => 'Paramètre de page',
            self::TYPE_INFO_CARD => 'Carte d\'information',
            self::TYPE_CTA => 'Bloc d\'appel à l\'action',
            self::TYPE_PROMO => 'Bloc promotionnel',
        ];
    }

    /**
     * Libellés des pages gérées par blocs.
     *
     * @return array<string, string>
     */
    public static function pageLabels(): array
    {
        return [
            self::PAGE_HOME => 'Page d\'accueil',
            self::PAGE_CONTACT => 'Page contact',
        ];
    }

    /**
     * Scope pour ne retourner que les blocs actifs.
     *
     * @param  Builder<SiteBlock>  $query  Requête Eloquent
     * @return Builder<SiteBlock> Requête filtrée
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par page publique.
     *
     * Ne pas nommer ce scope « forPage » : Laravel utilise déjà forPage()
     * en interne pour paginer les résultats des tableaux Filament.
     *
     * @param  Builder<SiteBlock>  $query  Requête Eloquent
     * @param  string  $page  Identifiant de page
     * @return Builder<SiteBlock> Requête filtrée
     */
    public function scopeWherePublicPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    /**
     * Scope pour filtrer par type de bloc.
     *
     * @param  Builder<SiteBlock>  $query  Requête Eloquent
     * @param  string  $blockType  Type de bloc
     * @return Builder<SiteBlock> Requête filtrée
     */
    public function scopeOfType(Builder $query, string $blockType): Builder
    {
        return $query->where('block_type', $blockType);
    }
}
