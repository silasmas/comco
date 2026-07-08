<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Paramètre clé-valeur du site institutionnel COMCO.
 */
class SiteSetting extends Model
{
    /**
     * Attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Retourne la valeur typée d'un paramètre.
     *
     * @param  string  $key  Clé du paramètre
     * @param  mixed  $default  Valeur par défaut
     * @return mixed Valeur enregistrée ou défaut
     */
    public static function value(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        if ($setting === null || $setting->value === null) {
            return $default;
        }

        return $setting->value;
    }

    /**
     * Enregistre ou met à jour un paramètre.
     *
     * @param  string  $key  Clé du paramètre
     * @param  mixed  $value  Valeur à persister
     * @return SiteSetting Enregistrement créé ou mis à jour
     */
    public static function store(string $key, mixed $value): self
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_scalar($value) || $value === null ? (string) $value : json_encode($value)],
        );
    }
}
