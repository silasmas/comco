<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Image de galerie attachée à une page CMS.
 */
class PageGalleryItem extends Model
{
    /**
     * Attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'page_id',
        'image',
        'image_source',
        'caption',
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
     * Retourne la page CMS parente.
     *
     * @return BelongsTo<Page, $this> Relation vers la page
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Scope pour ne retourner que les images actives.
     *
     * @param  Builder<PageGalleryItem>  $query  Requête Eloquent
     * @return Builder<PageGalleryItem> Requête filtrée
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
