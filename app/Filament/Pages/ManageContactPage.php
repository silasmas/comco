<?php

namespace App\Filament\Pages;

use App\Models\SiteBlock;
use App\Support\ContactPageContent;
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
 * Page Filament de gestion du contenu éditorial de la page contact.
 */
class ManageContactPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?string $navigationLabel = 'Page contact';

    protected static ?string $title = 'Page contact';

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'page-contact';

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
        return 'Modifiez les textes des blocs éditoriaux affichés sur la page contact publique.';
    }

    /**
     * Initialise le formulaire avec le contenu actuel.
     */
    public function mount(): void
    {
        $content = ContactPageContent::resolve();
        $provincial = $content->provincialOffices();
        $cta = $content->eServicesCta();

        $this->form->fill([
            'provincialTitle' => $provincial['title'],
            'provincialText' => $provincial['text'],
            'ctaTitle' => $cta['title'],
            'ctaText' => $cta['text'],
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
                    ->id('contactPageForm')
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
     * Configure les champs éditoriaux de la page contact.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Représentations provinciales')
                    ->schema([
                        TextInput::make('provincialTitle')
                            ->label('Titre')
                            ->required(),
                        Textarea::make('provincialText')
                            ->label('Texte')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Section::make('Bloc e-services')
                    ->schema([
                        TextInput::make('ctaTitle')
                            ->label('Titre')
                            ->required(),
                        Textarea::make('ctaText')
                            ->label('Texte')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Enregistre le contenu éditorial de la page contact.
     */
    public function save(): void
    {
        $data = $this->form->getState();

        SiteBlock::query()->updateOrCreate(
            [
                'page' => SiteBlock::PAGE_CONTACT,
                'block_key' => 'provincial_offices',
            ],
            [
                'block_type' => SiteBlock::TYPE_INFO_CARD,
                'label' => 'Représentations provinciales',
                'payload' => [
                    'title' => $data['provincialTitle'],
                    'text' => $data['provincialText'],
                ],
                'sort_order' => 0,
                'is_active' => true,
            ],
        );

        SiteBlock::query()->updateOrCreate(
            [
                'page' => SiteBlock::PAGE_CONTACT,
                'block_key' => 'eservices_cta',
            ],
            [
                'block_type' => SiteBlock::TYPE_CTA,
                'label' => 'Bloc e-services',
                'payload' => [
                    'title' => $data['ctaTitle'],
                    'text' => $data['ctaText'],
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        Notification::make()
            ->title('Page contact enregistrée')
            ->success()
            ->send();
    }
}
