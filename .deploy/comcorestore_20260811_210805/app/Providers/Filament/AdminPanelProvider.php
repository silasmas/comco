<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\ContactMessagesChartWidget;
use App\Filament\Widgets\EServiceSubmissionsChartWidget;
use App\Filament\Widgets\ForumActivityChartWidget;
use App\Filament\Widgets\NewsletterChartWidget;
use App\Filament\Widgets\PostsChartWidget;
use App\Filament\Widgets\SubmissionStatsWidget;
use App\Http\Middleware\FilamentInstallAccess;
use App\Http\Middleware\RedirectAdminToInstallation;
use App\Support\SiteDeploymentState;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use YacoubAlhaidari\FilamentTour\FilamentTourPlugin;

class AdminPanelProvider extends PanelProvider
{
    /**
     * Configure le panneau Filament admin COMCO.
     */
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('assets/logo01.png'))
            ->brandLogoHeight('2.75rem')
            ->favicon(asset('assets/ico.png'))
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::hex('#003DA5'),
                'warning' => Color::hex('#fdd428'),
                'danger' => Color::hex('#b33641'),
                'success' => Color::hex('#36b36a'),
                'info' => Color::hex('#3680b3'),
                'gray' => Color::hex('#2a3855'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                RedirectAdminToInstallation::class,
            ])
            ->authMiddleware([
                FilamentInstallAccess::class,
            ]);

        if (SiteDeploymentState::requiresInstallation()) {
            return $panel
                ->pages([])
                ->widgets([]);
        }

        $tour = config('comco-filament.tour', []);

        return $panel
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): HtmlString => new HtmlString(
                    '<style id="comco-tour-overrides">'
                    .file_get_contents(resource_path('css/filament-tour-overrides.css'))
                    .'</style>'
                ),
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                SubmissionStatsWidget::class,
                PostsChartWidget::class,
                ContactMessagesChartWidget::class,
                EServiceSubmissionsChartWidget::class,
                ForumActivityChartWidget::class,
                NewsletterChartWidget::class,
                AccountWidget::class,
            ])
            ->plugins([
                FilamentTourPlugin::make()
                    ->showTourButton(true)
                    ->tourButtonIcon('heroicon-o-academic-cap')
                    ->tourButtonColor('warning')
                    ->tourButtonTooltip($tour['tooltip'] ?? 'Visite guidée')
                    ->headerColor($tour['header_color'] ?? '#003DA5')
                    ->textColor($tour['text_color'] ?? '#2a3855')
                    ->backgroundColor($tour['background_color'] ?? '#ffffff')
                    ->contentBackgroundColor($tour['content_background_color'] ?? '#ffffff')
                    ->footerBackgroundColor($tour['footer_background_color'] ?? '#f7f9fc')
                    ->footerBorderColor($tour['footer_border_color'] ?? 'rgba(0, 61, 165, 0.12)')
                    ->primaryButtonColor($tour['primary_button_color'] ?? '#fdd428')
                    ->primaryButtonTextColor($tour['primary_button_text_color'] ?? '#2a3855')
                    ->primaryButtonHoverColor($tour['primary_button_hover_color'] ?? '#e6c022')
                    ->secondaryButtonColor($tour['secondary_button_color'] ?? '#2a3855')
                    ->secondaryButtonTextColor($tour['secondary_button_text_color'] ?? '#ffffff')
                    ->secondaryButtonHoverColor($tour['secondary_button_hover_color'] ?? '#1f2a40')
                    ->welcomeStep([
                        'id' => 'welcome',
                        'title' => 'Bienvenue sur le panneau COMCO',
                        'text' => '<p><strong>Cette visite guidée présente chaque entrée du menu d\'administration.</strong></p>'
                          .'<p>Elle couvre le contenu du site (accueil, pages, navigation, e-services), la modération des soumissions, le forum et les outils système.</p>'
                          .'<p>Durée estimée : 5 à 8 minutes. Vous pouvez l\'interrompre à tout moment.</p>',
                        'buttons' => [
                            ['text' => 'Passer', 'action' => 'cancel', 'secondary' => true],
                            ['text' => 'Commencer', 'action' => 'next', 'secondary' => false],
                        ],
                    ])
                    ->finishStep([
                        'id' => 'finish',
                        'title' => 'Visite terminée',
                        'text' => '<p><strong>Vous connaissez maintenant l\'organisation du panneau COMCO.</strong></p>'
                          .'<p>Rappel des grandes zones :</p>'
                          .'<ul>'
                          .'<li><strong>Contenu du site</strong> — accueil, institution, contact, navigation, formulaires e-services</li>'
                          .'<li><strong>Contenu</strong> — actualités et pages CMS institutionnelles</li>'
                          .'<li><strong>Soumissions</strong> — dossiers e-services, messages contact, newsletter</li>'
                          .'<li><strong>Forum</strong> — sujets et réponses à modérer</li>'
                          .'<li><strong>Système</strong> — utilisateurs et installation production</li>'
                          .'</ul>'
                          .'<p>Relancez cette visite via l\'icône casquette dans le menu utilisateur (en haut à droite).</p>',
                        'buttons' => [
                            ['text' => 'Retour', 'action' => 'back', 'secondary' => true],
                            ['text' => 'Terminer', 'action' => 'complete', 'secondary' => false],
                        ],
                    ]),
            ]);
    }
}
