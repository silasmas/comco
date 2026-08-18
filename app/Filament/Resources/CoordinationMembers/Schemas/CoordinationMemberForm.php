<?php

namespace App\Filament\Resources\CoordinationMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Support\CroppableImageUpload;

/**
 * Formulaire Filament d'une fiche Coordination.
 */
class CoordinationMemberForm
{
  /**
   * Configure le schéma de formulaire.
   *
   * @param  Schema  $schema  Schéma Filament
   * @return Schema Schéma configuré
   */
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        Section::make('Fiche')
          ->columns(2)
          ->schema([
            TextInput::make('title')
              ->label('Titre')
              ->required()
              ->maxLength(255)
              ->helperText('Nom de la structure / de la fiche affiché sur le site.')
              ->columnSpanFull(),
            Textarea::make('summary')
              ->label('Résumé')
              ->rows(3)
              ->helperText('Court texte affiché en aperçu (cartes / listes).')
              ->columnSpanFull(),
            RichEditor::make('body')
              ->label('Contenu détaillé / description')
              ->helperText('Affiché sur la page de détail. Utilisez « Justifier » dans la barre d\'outils pour aligner le texte.')
              ->columnSpanFull(),
            CroppableImageUpload::apply(
              FileUpload::make('image')
                ->label('Image')
                ->directory('coordination')
                ->disk('public')
                ->helperText('Rognez via l\'éditeur après l\'upload. Aucun agrandissement : la qualité d\'origine est conservée.')
                ->columnSpanFull(),
              [null, '4:5', '3:4', '1:1', '16:9']
            ),
            TextInput::make('link_url')
              ->label('Lien « En détail »')
              ->url()
              ->helperText('URL optionnelle vers une page externe ou interne complète (https://…).')
              ->maxLength(255),
            TextInput::make('link_label')
              ->label('Libellé du lien')
              ->placeholder('En détail')
              ->helperText('Texte du bouton (ex. En détail). Ignoré si aucune URL n’est renseignée.')
              ->maxLength(100),
            TextInput::make('sort_order')
              ->label('Ordre d\'affichage')
              ->numeric()
              ->default(0)
              ->required()
              ->helperText('Plus le nombre est petit, plus la fiche apparaît tôt dans la liste.'),
            Toggle::make('is_active')
              ->label('Actif')
              ->default(true)
              ->required()
              ->helperText('Désactivez pour masquer la fiche sans la supprimer.'),
          ]),
      ]);
  }
}
