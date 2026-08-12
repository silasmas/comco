<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant une page de contenu institutionnelle.
 */
class Page extends Model
{
    /**
     * Attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'section',
        'slug',
        'excerpt',
        'body',
        'template',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    /**
     * Cast des attributs du modèle.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Retourne les images de galerie associées à la page.
     *
     * @return HasMany<PageGalleryItem, $this> Relation vers les images
     */
    public function galleryItems(): HasMany
    {
        return $this->hasMany(PageGalleryItem::class)->orderBy('sort_order');
    }

    /**
     * Retourne les membres d'équipe associés à la page.
     *
     * @return HasMany<PageTeamMember, $this> Relation vers les profils
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(PageTeamMember::class)->orderBy('sort_order');
    }

    /**
     * Retourne les documents juridiques associés à la page.
     *
     * @return HasMany<PageLegalDocument, $this> Relation vers les PDF
     */
    public function legalDocuments(): HasMany
    {
        return $this->hasMany(PageLegalDocument::class)->orderBy('sort_order');
    }

    /**
     * Scope pour ne retourner que les pages publiées.
     *
     * @param  Builder<Page>  $query  Requête Eloquent
     * @return Builder<Page> Requête filtrée
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $builder): void {
                $builder
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
