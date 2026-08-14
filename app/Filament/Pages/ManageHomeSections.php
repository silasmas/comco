<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Models\SiteBlock;
use App\Support\HomePageContent;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Page Filament pour activer ou désactiver les rubriques de l'accueil.
 */
class ManageHomeSections extends Page
{
  use HasComcoResourceMeta;

  protected static string $resourceDescription = 'Activez ou désactivez chaque rubrique de la page d\'accueil (slider, missions, actualités, partenaires, etc.) sans supprimer le contenu.';

  protected static ?string $tourStepId = 'home-sections';

  protected static int $tourStepSort = 1;

  protected static array $tourStepFeatures = [
    'Activer ou masquer le slider, la bienvenue et l\'alerte signalement',
    'Contrôler l\'affichage des missions, ressources et chiffres clés',
    'Gérer la visibilité des actualités, témoignages et partenaires',
  ];

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEye;

  protected static ?string $navigationLabel = 'Rubriques accueil';

  protected static ?string $title = 'Rubriques de la page d\'accueil';

  protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

  protected static ?int $navigationSort = 1;

  protected static ?string $slug = 'accueil-rubriques';

  /**
   * Données du formulaire Livewire.
   *
   * @var array<string, mixed>|null
   */
  public ?array $data = [];

  /**
   * Affiche le sous-titre pédagogique de la page.
   *
   * @return string|Htmlable|null Description affichée
   */
  public function getSubheading(): string|Htmlable|null
  {
    return static::getResourceDescription();
  }

  /**
   * Initialise le formulaire avec la visibilité actuelle.
   */
  public function mount(): void
  {
    $this->form->fill(HomePageContent::resolve()->sectionVisibility());
  }

  /**
   * Configure le schéma principal de la page.
   *
   * @param  Schema  $schema  Schéma Filament
   * @return Schema Schéma configuré
   */
  public function content(Schema $schema): Schema
  {
    return $schema
      ->components([
        Form::make([EmbeddedSchema::make('form')])
          ->id('homeSectionsForm')
          ->livewireSubmitHandler('save')
          ->footer([
            Actions::make([
              Action::make('save')
                ->label('Enregistrer')
                ->submit('save')
                ->keyBindings(['mod+s']),
            ]),
          ]),
      ]);
  }

  /**
   * Configure les options par défaut du formulaire.
   *
   * @param  Schema  $schema  Schéma Filament
   * @return Schema Schéma configuré
   */
  public function defaultForm(Schema $schema): Schema
  {
    return $schema->statePath('data');
  }

  /**
   * Configure les interrupteurs de visibilité des rubriques.
   *
   * @param  Schema  $schema  Schéma Filament
   * @return Schema Schéma configuré
   */
  public function form(Schema $schema): Schema
  {
    $toggles = [];

    foreach (HomePageContent::sectionLabels() as $key => $label) {
      $toggles[] = Toggle::make($key)
        ->label($label)
        ->inline(false)
        ->onColor('success')
        ->offColor('danger');
    }

    return $schema
      ->components([
        Section::make('Visibilité des rubriques')
          ->description('Désactiver une rubrique la masque sur le site public sans supprimer son contenu.')
          ->columns(2)
          ->schema($toggles),
      ]);
  }

  /**
   * Enregistre la carte de visibilité des rubriques d'accueil.
   */
  public function save(): void
  {
    $state = $this->form->getState();
    $visibility = HomePageContent::defaultSectionVisibility();

    foreach (array_keys($visibility) as $key) {
      $visibility[$key] = (bool) ($state[$key] ?? false);
    }

    SiteBlock::query()->updateOrCreate(
      [
        'page' => SiteBlock::PAGE_HOME,
        'block_type' => SiteBlock::TYPE_SETTING,
        'block_key' => HomePageContent::SECTION_VISIBILITY_KEY,
      ],
      [
        'label' => 'Visibilité des rubriques',
        'payload' => ['value' => $visibility],
        'sort_order' => 0,
        'is_active' => true,
      ],
    );

    Notification::make()
      ->title('Rubriques mises à jour')
      ->success()
      ->send();
  }
}
