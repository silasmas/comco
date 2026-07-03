<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Models\User;
use App\Support\SiteDeploymentState;
use App\Support\SiteInstaller;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use App\Filament\Pages\Dashboard;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanAuthorizeAccess;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

/**
 * Page d'administration pour l'installation et la mise en production du site.
 */
class SiteInstallation extends Page
{
  use CanAuthorizeAccess;
  use HasComcoResourceMeta;

  protected static string $resourceDescription = 'Préparez le site pour la production : migrations, lien storage, configuration .env et super administrateur.';

  protected static ?string $tourStepId = 'site-installation';

  protected static int $tourStepSort = 95;

  protected static array $tourStepFeatures = [
    'Exécuter les migrations et le lien storage',
    'Configurer les variables essentielles',
    'Créer le compte super administrateur',
  ];

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

  protected static ?string $navigationLabel = 'Installation production';

  protected static ?string $title = 'Installation & production';

  protected static string|\UnitEnum|null $navigationGroup = 'Système';

  protected static ?int $navigationSort = 99;

  /**
   * @var array<string, mixed>|null
   */
  public ?array $configData = [];

  /**
   * @var array<string, mixed>|null
   */
  public ?array $adminData = [];

  /**
   * Vérifie l'accès à la page d'installation ou aux super administrateurs une fois le site en ligne.
   */
  public static function canAccess(): bool
  {
    if (SiteDeploymentState::requiresInstallation()) {
      return true;
    }

    $user = Auth::user();

    if (! $user instanceof User) {
      return false;
    }

    if ($user->is_super_admin) {
      return true;
    }

    return ! SiteDeploymentState::hasSuperAdmin();
  }

  /**
   * Masque le menu latéral pendant le déploiement initial.
   */
  public static function shouldRegisterNavigation(): bool
  {
    if (SiteDeploymentState::requiresInstallation()) {
      return false;
    }

    $user = Auth::user();

    return $user instanceof User && $user->is_super_admin;
  }

  /**
   * Préremplit les formulaires avec la configuration actuelle.
   */
  public function mount(): void
  {
    $this->configForm->fill([
      'app_url' => config('app.url'),
      'app_env' => config('app.env'),
      'app_debug' => config('app.debug') ? 'true' : 'false',
      'institution_email' => config('institution.contact.email'),
      'institution_full_name' => config('institution.fullName'),
      'institution_name' => config('institution.name'),
      'mail_from_address' => config('mail.from.address'),
      'mail_from_name' => config('mail.from.name'),
    ]);

    $this->adminForm->fill([
      'name' => 'Super Admin',
      'email' => 'superadmin@comco.gouv.cd',
    ]);
  }

  /**
   * Définit le schéma de configuration de base.
   */
  public function configForm(Schema $schema): Schema
  {
    return $schema
      ->components([
        TextInput::make('app_url')
          ->label('URL du site (APP_URL)')
          ->url()
          ->required(),
        TextInput::make('app_env')
          ->label('Environnement (APP_ENV)')
          ->default('production')
          ->required(),
        TextInput::make('app_debug')
          ->label('Mode debug (APP_DEBUG)')
          ->helperText('Utilisez false en production.')
          ->default('false')
          ->required(),
        TextInput::make('institution_full_name')
          ->label('Nom complet de l\'institution')
          ->required(),
        TextInput::make('institution_name')
          ->label('Sigle institution (INSTITUTION_NAME)')
          ->required(),
        TextInput::make('institution_email')
          ->label('Email institutionnel')
          ->email()
          ->required(),
        TextInput::make('mail_from_address')
          ->label('Email expéditeur (MAIL_FROM_ADDRESS)')
          ->email()
          ->required(),
        TextInput::make('mail_from_name')
          ->label('Nom expéditeur (MAIL_FROM_NAME)')
          ->required(),
      ])
      ->statePath('configData');
  }

  /**
   * Définit le schéma de création du super administrateur.
   */
  public function adminForm(Schema $schema): Schema
  {
    return $schema
      ->components([
        TextInput::make('name')
          ->label('Nom complet')
          ->required()
          ->maxLength(255),
        TextInput::make('email')
          ->label('Email de connexion')
          ->email()
          ->required()
          ->maxLength(255),
        TextInput::make('password')
          ->label('Mot de passe')
          ->password()
          ->revealable()
          ->required()
          ->rule(Password::defaults()),
        TextInput::make('password_confirmation')
          ->label('Confirmer le mot de passe')
          ->password()
          ->revealable()
          ->same('password')
          ->required(),
        Toggle::make('promote_current_user')
          ->label('Promouvoir mon compte actuel en super admin')
          ->default(false),
      ])
      ->statePath('adminData');
  }

  /**
   * Exécute les migrations de la base de données.
   */
  public function runMigrations(): void
  {
    try {
      $output = SiteInstaller::runMigrations();
    } catch (\Throwable $exception) {
      Notification::make()
        ->danger()
        ->title('Migrations impossible')
        ->body($exception->getMessage())
        ->send();

      return;
    }

    Notification::make()
      ->success()
      ->title('Migrations exécutées')
      ->body($output)
      ->send();
  }

  /**
   * Crée le lien symbolique storage.
   */
  public function runStorageLink(): void
  {
    $output = SiteInstaller::createStorageLink();

    Notification::make()
      ->success()
      ->title('Lien storage')
      ->body($output)
      ->send();
  }

  /**
   * Optimise l'application pour la production.
   */
  public function runOptimize(): void
  {
    try {
      $output = SiteInstaller::optimizeApplication(full: false);
    } catch (\Throwable $exception) {
      Notification::make()
        ->danger()
        ->title('Optimisation impossible')
        ->body($exception->getMessage())
        ->send();

      return;
    }

    Notification::make()
      ->success()
      ->title('Optimisation terminée')
      ->body($output)
      ->send();

    $this->redirect(static::getUrl(), navigate: false);
  }

  /**
   * Enregistre la configuration de base dans le fichier .env.
   */
  public function saveConfiguration(): void
  {
    $data = $this->configForm->getState();
    $updated = SiteInstaller::applyBaseConfiguration($data);

    Notification::make()
      ->success()
      ->title('Configuration enregistrée')
      ->body(count($updated) . ' variable(s) mise(s) à jour dans le fichier .env.')
      ->send();
  }

  /**
   * Crée ou met à jour un super administrateur.
   */
  public function createSuperAdmin(): void
  {
    $data = $this->adminForm->getState();

    try {
      if ($data['promote_current_user'] ?? false) {
        $user = Auth::user();

        if ($user instanceof User) {
          $user->update(['is_super_admin' => true]);
        }
      }

      SiteInstaller::createSuperAdmin(
        name: $data['name'],
        email: $data['email'],
        password: $data['password'],
      );
    } catch (\Throwable $exception) {
      Notification::make()
        ->danger()
        ->title('Création impossible')
        ->body($exception->getMessage())
        ->send();

      return;
    }

    Notification::make()
      ->success()
      ->title('Super administrateur créé')
      ->body('Le compte ' . $data['email'] . ' peut accéder au panneau d\'administration.')
      ->send();

    $this->adminForm->fill([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => '',
      'password_confirmation' => '',
      'promote_current_user' => false,
    ]);
  }

  /**
   * @return array<Action>
   */
  protected function getHeaderActions(): array
  {
    $actions = [];

    if (SiteDeploymentState::requiresInstallation()) {
      $actions[] = Action::make('launchSite')
        ->label('Mettre le site en ligne')
        ->icon(Heroicon::OutlinedGlobeAlt)
        ->color('success')
        ->requiresConfirmation()
        ->modalHeading('Mettre le site en ligne')
        ->modalDescription('Le site public sera accessible. Assurez-vous d\'avoir exécuté les migrations et créé le super administrateur.')
        ->action('launchSite');
    }

    $actions[] = Action::make('runAll')
        ->label('Tout installer')
        ->icon(Heroicon::OutlinedPlay)
        ->requiresConfirmation()
        ->modalHeading('Installation complète')
        ->modalDescription('Exécute les migrations, crée le lien storage et met en cache la configuration.')
        ->action(function (): void {
          try {
            $messages = [
              SiteInstaller::runMigrations(),
              SiteInstaller::createStorageLink(),
              SiteInstaller::optimizeApplication(full: false),
            ];
          } catch (\Throwable $exception) {
            Notification::make()
              ->danger()
              ->title('Installation impossible')
              ->body($exception->getMessage())
              ->send();

            return;
          }

          Notification::make()
            ->success()
            ->title('Installation terminée')
            ->body(implode(' | ', $messages))
            ->send();

          $this->redirect(static::getUrl(), navigate: false);
        });

    return $actions;
  }

  /**
   * Contenu principal de la page d'installation.
   */
  public function content(Schema $schema): Schema
  {
    return $schema
      ->components([
        Section::make('Étapes système')
          ->description('Actions à exécuter lors du déploiement en production.')
          ->schema([
            Actions::make([
              Action::make('migrate')
                ->label('Exécuter les migrations')
                ->icon(Heroicon::OutlinedCircleStack)
                ->requiresConfirmation()
                ->action('runMigrations'),
              Action::make('storageLink')
                ->label('Créer le lien storage')
                ->icon(Heroicon::OutlinedLink)
                ->requiresConfirmation()
                ->action('runStorageLink'),
              Action::make('optimize')
                ->label('Optimiser pour la production')
                ->icon(Heroicon::OutlinedBolt)
                ->requiresConfirmation()
                ->action('runOptimize'),
            ]),
          ]),
        Section::make('Configuration de base')
          ->description('Met à jour les variables essentielles dans le fichier .env.')
          ->schema([
            Form::make([EmbeddedSchema::make('configForm')])
              ->id('configForm')
              ->livewireSubmitHandler('saveConfiguration')
              ->footer([
                Actions::make([
                  Action::make('saveConfiguration')
                    ->label('Enregistrer la configuration')
                    ->submit('saveConfiguration'),
                ]),
              ]),
          ]),
        Section::make('Super administrateur')
          ->description('Crée le compte principal d\'accès au panneau d\'administration.')
          ->schema([
            Form::make([EmbeddedSchema::make('adminForm')])
              ->id('adminForm')
              ->livewireSubmitHandler('createSuperAdmin')
              ->footer([
                Actions::make([
                  Action::make('createSuperAdmin')
                    ->label('Créer le super admin')
                    ->submit('createSuperAdmin'),
                ]),
              ]),
          ]),
      ]);
  }

  /**
   * @return string|Htmlable
   */
  public function getSubheading(): string|Htmlable|null
  {
    if (SiteDeploymentState::requiresInstallation()) {
      return 'Première mise en production : configurez le site, créez le super administrateur, puis mettez le site en ligne.';
    }

    return static::getResourceDescription();
  }

  /**
   * Met le site en ligne et désactive la page publique de déploiement.
   */
  public function launchSite(): void
  {
    try {
      SiteInstaller::finalizeDeployment();
    } catch (\Throwable $exception) {
      Notification::make()
        ->danger()
        ->title('Mise en ligne impossible')
        ->body($exception->getMessage())
        ->send();

      return;
    }

    Notification::make()
      ->success()
      ->title('Site mis en ligne')
      ->body('Le site public est maintenant accessible. Vous pouvez accéder au tableau de bord.')
      ->send();

    $this->redirect(Dashboard::getUrl(), navigate: true);
  }
}
