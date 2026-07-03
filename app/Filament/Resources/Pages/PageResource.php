<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PageResource extends Resource
{
  use HasComcoResourceMeta;

  protected static ?string $model = Page::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

  protected static ?string $navigationLabel = 'Pages CMS';

  protected static string|null|\UnitEnum $navigationGroup = 'Contenu';

  protected static ?int $navigationSort = 2;

  protected static string $resourceDescription = 'Gérez les pages institutionnelles du site (textes, gabarits et contenus par section).';

  protected static ?string $tourStepId = 'pages';

  protected static int $tourStepSort = 20;

  protected static array $tourStepFeatures = [
    'Modifier le contenu des pages publiques',
    'Choisir le gabarit d\'affichage',
    'Mettre à jour les extraits et métadonnées SEO',
  ];

  public static function form(Schema $schema): Schema
  {
    return PageForm::configure($schema);
  }

  public static function table(Table $table): Table
  {
    return PagesTable::configure($table);
  }

  public static function getPages(): array
  {
    return [
      'index' => ListPages::route('/'),
      'create' => CreatePage::route('/create'),
      'edit' => EditPage::route('/{record}/edit'),
    ];
  }
}
