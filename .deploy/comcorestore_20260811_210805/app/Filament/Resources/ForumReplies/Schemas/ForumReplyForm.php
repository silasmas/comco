<?php

namespace App\Filament\Resources\ForumReplies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ForumReplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('topic_title')
                    ->label('Sujet')
                    ->afterStateHydrated(function (TextInput $component, $record): void {
                        $component->state($record?->topic?->title);
                    })
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('author_name')
                    ->label('Auteur')
                    ->disabled(),
                TextInput::make('author_email')
                    ->label('Email')
                    ->disabled(),
                Textarea::make('body')
                    ->label('Réponse')
                    ->disabled()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options(config('forum.statuses.reply', []))
                    ->required(),
            ]);
    }
}
