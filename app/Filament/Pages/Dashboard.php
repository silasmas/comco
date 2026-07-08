<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Tableau de bord principal du panneau d'administration COMCO.
 */
class Dashboard extends BaseDashboard
{
    use HasComcoResourceMeta;

    protected static string $resourceDescription = 'Point d\'entrée du panneau : suivez en un coup d\'œil les soumissions en attente, l\'activité éditoriale et les tendances mensuelles du site public.';

    protected static ?string $tourStepId = 'dashboard';

    protected static int $tourStepSort = 0;

    protected static array $tourStepFeatures = [
        'Consulter les compteurs de dossiers e-services, messages contact et sujets forum en attente',
        'Analyser les graphiques mensuels (actualités, contact, e-services, forum, newsletter)',
        'Repérer rapidement les pics d\'activité ou les retards de traitement',
        'Accéder à chaque module via le menu latéral, regroupé par thème (contenu, soumissions, forum…)',
        'Relancer cette visite guidée à tout moment via l\'icône casquette dans le menu utilisateur',
    ];

    /**
     * Titre affiché dans la visite guidée.
     */
    public static function getTourStepTitle(): ?string
    {
        return 'Tableau de bord';
    }

    /**
     * Sous-titre pédagogique du tableau de bord.
     */
    public function getSubheading(): string|Htmlable|null
    {
        return static::getResourceDescription();
    }
}
