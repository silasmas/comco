<?php

namespace App\Filament\Pages;

use App\Models\SiteBlock;
use App\Support\HomePageContent;
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
 * Page Filament des blocs promotionnels restants de la page d'accueil.
 */
class ManageHomePromos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $navigationLabel = 'Blocs promotionnels';

    protected static ?string $title = 'Blocs promotionnels de l\'accueil';

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'accueil-promotions';

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
        return 'Alertes, bandeaux, promotions législatives et visuels complémentaires de la page d\'accueil.';
    }

    /**
     * Initialise le formulaire avec le contenu actuel.
     */
    public function mount(): void
    {
        $content = HomePageContent::resolve();

        $this->form->fill([
            'alertTitle' => $content->alertSignalement()['title'],
            'alertText' => $content->alertSignalement()['text'],
            'alertButtonLabel' => $content->alertSignalement()['button_label'],
            'contactCtaTitle' => $content->contactCta()['title'],
            'contactCtaButtonLabel' => $content->contactCta()['button_label'],
            'legislationSectionTitle' => $content->legislationPromo()['section_title'],
            'legislationLawTitle' => $content->legislationPromo()['law_title'],
            'legislationLawText' => $content->legislationPromo()['law_text'],
            'taloTitle' => $content->taloPromo()['title'],
            'taloText' => $content->taloPromo()['text'],
            'funFactLineOne' => $content->funFactHeader()['line_one'],
            'funFactLineTwo' => $content->funFactHeader()['line_two'],
            'whyChooseImage' => $content->whyChooseImage()['image'],
        ]);
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
                    ->id('homePromosForm')
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
     * Configure les champs éditoriaux des blocs promotionnels.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Alerte signalement')
                    ->schema([
                        TextInput::make('alertTitle')->label('Titre')->required(),
                        Textarea::make('alertText')->label('Texte')->rows(3)->required()->columnSpanFull(),
                        TextInput::make('alertButtonLabel')->label('Libellé du bouton')->required(),
                    ]),
                Section::make('Bandeau contact')
                    ->schema([
                        Textarea::make('contactCtaTitle')->label('Texte')->rows(2)->required()->columnSpanFull(),
                        TextInput::make('contactCtaButtonLabel')->label('Libellé du bouton')->required(),
                    ]),
                Section::make('Promotion cadre juridique')
                    ->schema([
                        TextInput::make('legislationSectionTitle')->label('Titre de section')->required(),
                        TextInput::make('legislationLawTitle')->label('Titre du texte de loi')->required(),
                        TextInput::make('legislationLawText')->label('Sous-titre du texte')->required(),
                    ]),
                Section::make('Promotion TALO')
                    ->schema([
                        TextInput::make('taloTitle')->label('Titre')->required(),
                        Textarea::make('taloText')->label('Texte')->rows(3)->required()->columnSpanFull(),
                    ]),
                Section::make('Section chiffres clés')
                    ->schema([
                        TextInput::make('funFactLineOne')->label('Première ligne')->required(),
                        TextInput::make('funFactLineTwo')->label('Deuxième ligne')->required(),
                    ]),
                Section::make('Image « Pourquoi la COMCO »')
                    ->schema([
                        TextInput::make('whyChooseImage')
                            ->label('Chemin de l\'image')
                            ->helperText('Ex. img4.jpg dans /assets/')
                            ->required(),
                    ]),
            ]);
    }

    /**
     * Enregistre les blocs promotionnels de la page d'accueil.
     */
    public function save(): void
    {
        $data = $this->form->getState();

        $this->savePromo('alert_signalement', 'Alerte signalement', [
            'title' => $data['alertTitle'],
            'text' => $data['alertText'],
            'button_label' => $data['alertButtonLabel'],
            'button_section' => 'e-services',
            'button_slug' => 'signaler-pratique',
        ]);

        $this->savePromo('contact_cta', 'Bandeau contact', [
            'title' => $data['contactCtaTitle'],
            'button_label' => $data['contactCtaButtonLabel'],
            'button_route' => 'contact',
        ]);

        $this->savePromo('legislation_promo', 'Promotion cadre juridique', [
            'section_title' => $data['legislationSectionTitle'],
            'law_title' => $data['legislationLawTitle'],
            'law_text' => $data['legislationLawText'],
            'link_section' => 'centre-information',
            'link_slug' => 'cadre-juridique',
        ]);

        $this->savePromo('talo_promo', 'Promotion TALO', [
            'title' => $data['taloTitle'],
            'text' => $data['taloText'],
            'image' => 'talo.jpg',
        ]);

        $this->savePromo('fun_fact_header', 'En-tête chiffres clés', [
            'line_one' => $data['funFactLineOne'],
            'line_two' => $data['funFactLineTwo'],
        ]);

        $this->savePromo('why_choose_image', 'Image Pourquoi la COMCO', [
            'image' => $data['whyChooseImage'],
            'image_source' => 'comco',
        ]);

        Notification::make()
            ->title('Blocs promotionnels enregistrés')
            ->success()
            ->send();
    }

    /**
     * Crée ou met à jour un bloc promotionnel.
     *
     * @param  string  $blockKey  Clé métier du bloc
     * @param  string  $label  Libellé interne
     * @param  array<string, string>  $payload  Contenu du bloc
     */
    private function savePromo(string $blockKey, string $label, array $payload): void
    {
        SiteBlock::query()->updateOrCreate(
            [
                'page' => SiteBlock::PAGE_HOME,
                'block_key' => $blockKey,
            ],
            [
                'block_type' => SiteBlock::TYPE_PROMO,
                'label' => $label,
                'payload' => $payload,
                'sort_order' => 0,
                'is_active' => true,
            ],
        );
    }
}
