<?php

namespace App\Support;

use App\Models\EServiceDefinition;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\SiteBlock;
use App\Models\SiteSetting;

/**
 * Synthétise l'état d'avancement de l'installation et des seeders COMCO.
 */
class InstallationStatus
{
    /**
     * Retourne l'état complet affiché sur la page d'installation.
     *
     * @return array<string, mixed> Indicateurs et compteurs
     */
    public static function forView(): array
    {
        $databaseReady = SiteDeploymentState::databaseIsReady();

        return [
            'databaseReady' => $databaseReady,
            'storageLinked' => file_exists(public_path('storage')),
            'superAdminExists' => SiteDeploymentState::hasSuperAdmin(),
            'contentSeeded' => $databaseReady && self::isContentSeeded(),
            'postsSeeded' => $databaseReady && self::isPostsSeeded(),
            'counts' => $databaseReady ? self::counts() : self::emptyCounts(),
        ];
    }

    /**
     * Indique si les contenus institutionnels ont déjà été importés.
     */
    public static function isContentSeeded(): bool
    {
        return (bool) SiteDeploymentState::whenDatabaseReady(function (): bool {
            return Page::query()->exists()
                && SiteBlock::query()->forPage(SiteBlock::PAGE_HOME)->exists()
                && SiteBlock::query()->forPage(SiteBlock::PAGE_CONTACT)->exists()
                && NavigationItem::query()->exists()
                && EServiceDefinition::query()->exists();
        }, false);
    }

    /**
     * Indique si les articles de démonstration ont déjà été importés.
     */
    public static function isPostsSeeded(): bool
    {
        return (bool) SiteDeploymentState::whenDatabaseReady(
            fn (): bool => Post::query()->exists(),
            false,
        );
    }

    /**
     * Retourne les compteurs de contenu importé en base.
     *
     * @return array<string, int> Totaux par type
     */
    public static function counts(): array
    {
        return SiteDeploymentState::whenDatabaseReady(
            fn (): array => [
                'pages' => Page::query()->count(),
                'homeBlocks' => SiteBlock::query()->forPage(SiteBlock::PAGE_HOME)->count(),
                'contactBlocks' => SiteBlock::query()->forPage(SiteBlock::PAGE_CONTACT)->count(),
                'navigationItems' => NavigationItem::query()->count(),
                'eServices' => EServiceDefinition::query()->count(),
                'settings' => SiteSetting::query()->count(),
                'posts' => Post::query()->count(),
            ],
            self::emptyCounts(),
        );
    }

    /**
     * Retourne des compteurs vides lorsque la base n'est pas prête.
     *
     * @return array<string, int> Compteurs à zéro
     */
    private static function emptyCounts(): array
    {
        return [
            'pages' => 0,
            'homeBlocks' => 0,
            'contactBlocks' => 0,
            'navigationItems' => 0,
            'eServices' => 0,
            'settings' => 0,
            'posts' => 0,
        ];
    }
}
