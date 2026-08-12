<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Élément de menu ou lien de navigation du site public.
 */
class NavigationItem extends Model
{
    public const MENU_MAIN = 'main';

    public const MENU_FOOTER_NAVIGATION = 'footer_navigation';

    public const MENU_FOOTER_ESERVICES = 'footer_eservices';

    public const MENU_FOOTER_QUICK = 'footer_quick';

    public const LINK_ROUTE = 'route';

    public const LINK_SECTION = 'section';

    public const LINK_EXTERNAL = 'external';

    public const LINK_GROUP = 'group';

    /**
     * Attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'menu',
        'parent_id',
        'label',
        'link_type',
        'route',
        'section',
        'slug',
        'url',
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
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Libellés des menus gérés en administration.
     *
     * @return array<string, string>
     */
    public static function menuLabels(): array
    {
        return [
            self::MENU_MAIN => 'Menu principal',
            self::MENU_FOOTER_NAVIGATION => 'Pied de page — navigation',
            self::MENU_FOOTER_ESERVICES => 'Pied de page — e-services',
            self::MENU_FOOTER_QUICK => 'Pied de page — liens rapides',
        ];
    }

    /**
     * Libellés des types de liens disponibles.
     *
     * @return array<string, string>
     */
    public static function linkTypeLabels(): array
    {
        return [
            self::LINK_ROUTE => 'Route interne',
            self::LINK_SECTION => 'Page CMS (section + slug)',
            self::LINK_EXTERNAL => 'URL externe',
            self::LINK_GROUP => 'Groupe (menu déroulant)',
        ];
    }

    /**
     * Retourne l'élément parent du menu.
     *
     * @return BelongsTo<NavigationItem, $this> Relation parente
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Retourne les éléments enfants du menu.
     *
     * @return HasMany<NavigationItem, $this> Relation enfants
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Scope pour ne retourner que les éléments actifs.
     *
     * @param  Builder<NavigationItem>  $query  Requête Eloquent
     * @return Builder<NavigationItem> Requête filtrée
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Convertit l'élément en structure compatible avec NavigationUrl.
     *
     * @return array<string, mixed> Élément de navigation
     */
    public function toNavArray(): array
    {
        if ($this->link_type === self::LINK_ROUTE && filled($this->route)) {
            return [
                'label' => $this->label,
                'route' => $this->route,
            ];
        }

        if ($this->link_type === self::LINK_EXTERNAL && filled($this->url)) {
            return [
                'label' => $this->label,
                'url' => $this->url,
            ];
        }

        if ($this->link_type === self::LINK_SECTION && filled($this->section) && filled($this->slug)) {
            return [
                'label' => $this->label,
                'section' => $this->section,
                'slug' => $this->slug,
            ];
        }

        if ($this->link_type === self::LINK_GROUP && filled($this->section)) {
            return [
                'label' => $this->label,
                'section' => $this->section,
                'children' => $this->children->map(fn (self $child): array => $child->toChildNavArray())->all(),
            ];
        }

        return [
            'label' => $this->label,
        ];
    }

    /**
     * Convertit un enfant de menu en structure de lien.
     *
     * @return array<string, mixed> Élément enfant
     */
    public function toChildNavArray(): array
    {
        return [
            'label' => $this->label,
            'slug' => $this->slug,
        ];
    }
}
