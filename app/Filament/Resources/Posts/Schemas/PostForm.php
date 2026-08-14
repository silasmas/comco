<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PostForm
{
    /**
     * Configure le formulaire d'un article.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                TextInput::make('category')
                    ->label('Catégorie'),
                TextInput::make('author')
                    ->label('Auteur'),
                Textarea::make('excerpt')
                    ->label('Chapô')
                    ->columnSpanFull(),
                RichEditor::make('body')
                    ->label('Texte de description')
                    ->helperText('Utilisez le bouton « Justifier » pour aligner le texte.')
                    ->columnSpanFull(),
                FileUpload::make('featured_image')
                    ->label('Image à la une')
                    ->image(),
                TextInput::make('meta_title')
                    ->label('Titre SEO'),
                Textarea::make('meta_description')
                    ->label('Description SEO')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Publié')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Date de publication'),
            ]);
    }
}
