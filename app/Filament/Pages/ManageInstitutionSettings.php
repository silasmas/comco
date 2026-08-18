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
                    ->description('Informations affichées dans l’en-tête, le pied de page et les mentions institutionnelles.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom court')
                            ->required()
                            ->helperText('Nom usuel de l’institution (ex. COMCO).'),
                        TextInput::make('fullName')
                            ->label('Nom complet')
                            ->required()
                            ->helperText('Dénomination officielle complète.'),
                        TextInput::make('shortName')
                            ->label('Sigle')
                            ->required()
                            ->helperText('Abréviation affichée là où l’espace est limité.'),
                        Textarea::make('tagline')
                            ->label('Slogan')
                            ->rows(3)
                            ->helperText('Phrase d’accroche institutionnelle (optionnelle selon les pages).')
                            ->columnSpanFull(),
                    ]),
                Section::make('Contact')
                    ->description('Coordonnées reprises sur la page Contact et dans le pied de page.')
                    ->schema([
                        TextInput::make('contactEmail')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->helperText('Adresse de contact principale affichée au public.'),
                        TextInput::make('contactPhone')
                            ->label('Téléphone')
                            ->required()
                            ->helperText('Numéro affiché sur le site.'),
                        Textarea::make('contactAddress')
                            ->label('Adresse')
                            ->rows(3)
                            ->helperText('Adresse postale complète (une ligne ou plusieurs).')
                            ->columnSpanFull(),
                        TextInput::make('contactMapEmbedUrl')
                            ->label('URL embed Google Maps')
                            ->url()
                            ->helperText('Lien « Intégrer une carte » (iframe) fourni par Google Maps.')
                            ->columnSpanFull(),
                        TextInput::make('contactMapLinkUrl')
                            ->label('Lien Google Maps')
                            ->url()
                            ->helperText('Lien « Ouvrir dans Google Maps » pour le bouton public.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Réseaux sociaux')
                    ->description('Laissez vide un réseau pour masquer son icône sur le site.')
                    ->schema([
                        TextInput::make('socialLinkedin')
                            ->label('LinkedIn')
                            ->url()
                            ->helperText('URL complète du profil ou de la page LinkedIn.'),
                        TextInput::make('socialTwitter')
                            ->label('Twitter / X')
                            ->url()
                            ->helperText('URL complète du compte X (Twitter).'),
                        TextInput::make('socialFacebook')
                            ->label('Facebook')
                            ->url()
                            ->helperText('URL complète de la page Facebook.'),
                        TextInput::make('socialYoutube')
                            ->label('YouTube')
                            ->url()
                            ->helperText('URL complète de la chaîne YouTube.'),
                    ]),
                Section::make('SEO')
                    ->description('Paramètres par défaut pour les titres et descriptions des pages.')
                    ->schema([
                        TextInput::make('seoTitleSuffix')
                            ->label('Suffixe des titres')
                            ->helperText('Ajouté après le titre de chaque page (ex. « | COMCO »).')
                            ->columnSpanFull(),
                        Textarea::make('seoDefaultDescription')
                            ->label('Description par défaut')
                            ->rows(3)
                            ->helperText('Utilisée quand une page n’a pas de description SEO propre.')
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
