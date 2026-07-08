<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Support\InstitutionSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
 * Page Filament de gestion des paramètres institutionnels COMCO.
 */
class ManageInstitutionSettings extends Page
{
    use HasComcoResourceMeta;

    protected static string $resourceDescription = 'Centralisez l\'identité COMCO et les coordonnées affichées sur tout le site : en-tête, pied de page, page contact, e-mails automatiques et SEO par défaut.';

    protected static ?string $tourStepId = 'institution-settings';

    protected static int $tourStepSort = 3;

    protected static array $tourStepFeatures = [
        'Modifier le nom institutionnel, le sigle et le slogan institutionnel',
        'Mettre à jour l\'e-mail, le téléphone et l\'adresse postale de contact',
        'Configurer la carte Google Maps (lien et code embed) de la page contact',
        'Renseigner les URL des réseaux sociaux (LinkedIn, X, Facebook, YouTube)',
        'Ajuster le suffixe des titres SEO et la description meta par défaut du site',
        'Appliquer immédiatement les changements sur l\'ensemble du site public',
    ];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $navigationLabel = 'Paramètres institution';

    protected static ?string $title = 'Paramètres institutionnels';

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'parametres-institution';

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
     * Initialise le formulaire avec les paramètres actuels.
     */
    public function mount(): void
    {
        $this->form->fill(InstitutionSettings::forForm());
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
                    ->id('institutionSettingsForm')
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
            ->columns(2);
    }

    /**
     * Configure les champs du formulaire institutionnel.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identité')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom court')
                            ->required(),
                        TextInput::make('fullName')
                            ->label('Nom complet')
                            ->required(),
                        TextInput::make('shortName')
                            ->label('Sigle')
                            ->required(),
                        Textarea::make('tagline')
                            ->label('Slogan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Contact')
                    ->schema([
                        TextInput::make('contactEmail')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('contactPhone')
                            ->label('Téléphone')
                            ->required(),
                        Textarea::make('contactAddress')
                            ->label('Adresse')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('contactMapEmbedUrl')
                            ->label('URL embed Google Maps')
                            ->url()
                            ->columnSpanFull(),
                        TextInput::make('contactMapLinkUrl')
                            ->label('Lien Google Maps')
                            ->url()
                            ->columnSpanFull(),
                    ]),
                Section::make('Réseaux sociaux')
                    ->schema([
                        TextInput::make('socialLinkedin')
                            ->label('LinkedIn')
                            ->url(),
                        TextInput::make('socialTwitter')
                            ->label('Twitter / X')
                            ->url(),
                        TextInput::make('socialFacebook')
                            ->label('Facebook')
                            ->url(),
                        TextInput::make('socialYoutube')
                            ->label('YouTube')
                            ->url(),
                    ]),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seoTitleSuffix')
                            ->label('Suffixe des titres')
                            ->columnSpanFull(),
                        Textarea::make('seoDefaultDescription')
                            ->label('Description par défaut')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Enregistre les paramètres institutionnels.
     */
    public function save(): void
    {
        $data = $this->form->getState();
        InstitutionSettings::persist($data);
        InstitutionSettings::applyToConfig();

        Notification::make()
            ->title('Paramètres enregistrés')
            ->success()
            ->send();
    }
}
