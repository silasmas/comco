<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Définition dynamique d'un formulaire e-service public.
 */
class EServiceDefinition extends Model
{
    /**
     * Attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'label',
        'intro',
        'fields',
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
            'fields' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope pour ne retourner que les services actifs.
     *
     * @param  Builder<EServiceDefinition>  $query  Requête Eloquent
     * @return Builder<EServiceDefinition> Requête filtrée
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Convertit la définition au format attendu par les formulaires publics.
     *
     * @return array{label: string, intro: string, fields: list<array<string, mixed>>} Configuration du service
     */
    public function toServiceConfig(): array
    {
        return [
            'label' => $this->label,
            'intro' => $this->intro,
            'fields' => $this->fields ?? [],
        ];
    }
}
