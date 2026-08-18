<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Support\MaintenanceMode;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
 * Page Filament pour activer le mode maintenance et prévisualiser la page publique.
 */
class ManageMaintenanceMode extends Page
{
    use HasComcoResourceMeta;

    protected static string $resourceDescription = 'Masquez temporairement le site public derrière une page de maintenance brandée. L\'administration reste accessible ; le bouton Prévisualiser ouvre le vrai site (bypass admin).';

    protected static ?string $tourStepId = 'maintenance-mode';

    protected static int $tourStepSort = 92;

    protected static array $tourStepFeatures = [
        'Activer ou désactiver le mode maintenance en un clic',
        'Personnaliser le titre et le message affichés aux visiteurs',
        'Prévisualiser le vrai site public même lorsque la maintenance est active',
        'Conserver l\'accès au tableau de bord administrateur pendant la maintenance',
    ];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'Mode maintenance';

    protected static ?string $title = 'Mode maintenance';

    protected static string|\UnitEnum|null $navigationGroup = 'Système';

    protected static ?int $navigationSort = 98;

    protected static ?string $slug = 'mode-maintenance';

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
     * Initialise le formulaire avec l'état actuel du mode maintenance.
     */
    public function mount(): void
    {
        $this->form->fill(MaintenanceMode::forForm());
    }

    /**
     * Actions d'en-tête (prévisualisation).
     *
     * @return array<Action> Actions Filament
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Prévisualiser le site')
                ->icon(Heroicon::OutlinedEye)
                ->url(route('maintenance.preview'))
                ->openUrlInNewTab()
                ->tooltip('Ouvre le vrai site public (bypass admin) dans un nouvel onglet pendant 2 heures.'),
            Action::make('previewMaintenance')
                ->label('Voir la page maintenance')
                ->icon(Heroicon::OutlinedWrenchScrewdriver)
                ->url(url('/'))
                ->openUrlInNewTab()
                ->tooltip('Ouvre le site public tel que vu par les visiteurs (page maintenance si active).'),
            Action::make('exitPreview')
                ->label('Quitter la prévisualisation')
                ->icon(Heroicon::OutlinedXMark)
                ->url(route('maintenance.preview.exit'))
                ->color('gray')
                ->tooltip('Désactive le bypass de prévisualisation du site public.'),
        ];
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
                    ->id('maintenanceModeForm')
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
        return $schema
            ->statePath('data')
            ->columns(1);
    }

    /**
     * Configure les champs du formulaire de maintenance.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Activation')
                    ->description('Lorsque le mode est actif, les visiteurs voient uniquement la page de maintenance. Le tableau de bord admin reste accessible.')
                    ->schema([
                        Toggle::make('enabled')
                            ->label('Activer le mode maintenance')
                            ->helperText('Le site public est masqué immédiatement après enregistrement.'),
                    ]),
                Section::make('Contenu affiché')
                    ->description('Textes présentés aux visiteurs pendant la maintenance.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->maxLength(120)
                            ->helperText('Titre principal de la page de maintenance.'),
                        Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->helperText('Message d’information destiné au public.'),
                    ]),
            ]);
    }

    /**
     * Enregistre l'état du mode maintenance.
     */
    public function save(): void
    {
        $data = $this->form->getState();
        MaintenanceMode::update($data);

        Notification::make()
            ->title($data['enabled'] ?? false
                ? 'Mode maintenance activé'
                : 'Mode maintenance désactivé')
            ->success()
            ->send();
    }
}
