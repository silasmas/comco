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
    public const DISPLAY_CONTENT = 'content';

    public const DISPLAY_PDF = 'pdf';

    public const DISPLAY_BOTH = 'both';

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
        'content_display',
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
     * Libellés des modes d'affichage du contenu de page.
     *
     * @return array<string, string> Modes disponibles
     */
    public static function contentDisplayLabels(): array
    {
        return [
            self::DISPLAY_CONTENT => 'Contenu texte uniquement',
            self::DISPLAY_PDF => 'Fichier(s) PDF uniquement',
            self::DISPLAY_BOTH => 'Contenu texte et PDF',
        ];
    }

    /**
     * Indique si la page doit afficher le contenu texte (chapô / body).
     *
     * @return bool True si le texte est affiché
     */
    public function showsContent(): bool
    {
        $mode = $this->content_display ?: self::DISPLAY_CONTENT;

        return in_array($mode, [self::DISPLAY_CONTENT, self::DISPLAY_BOTH], true);
    }

    /**
     * Indique si la page doit afficher les documents PDF.
     *
     * @return bool True si les PDF sont affichés
     */
    public function showsPdf(): bool
    {
        $mode = $this->content_display ?: self::DISPLAY_CONTENT;

        return in_array($mode, [self::DISPLAY_PDF, self::DISPLAY_BOTH], true);
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
