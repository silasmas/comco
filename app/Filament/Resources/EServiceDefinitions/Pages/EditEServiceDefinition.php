<?php

namespace App\Filament\Resources\EServiceDefinitions\Pages;

use App\Filament\Resources\EServiceDefinitions\EServiceDefinitionResource;
use App\Filament\Resources\Pages\ComcoEditRecord;
use App\Filament\Resources\Pages\PageResource;
use App\Models\Page;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

/**
 * Page d'édition d'une définition e-service.
 */
class EditEServiceDefinition extends ComcoEditRecord
{
    protected static string $resource = EServiceDefinitionResource::class;

    /**
     * Retourne les actions disponibles dans l'en-tête de la page.
     *
     * @return list<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewPublicPage')
                ->label('Voir la page publique')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (): string => route('sections.show', [
                    'section' => 'e-services',
                    'slug' => $this->record->slug,
                ]))
                ->openUrlInNewTab(),
            Action::make('editCmsPage')
                ->label('Modifier la page CMS')
                ->icon(Heroicon::OutlinedDocumentText)
                ->url(function (): ?string {
                    $page = Page::query()
                        ->where('section', 'e-services')
                        ->where('slug', $this->record->slug)
                        ->first();

                    if ($page === null) {
                        return null;
                    }

                    return PageResource::getUrl('edit', ['record' => $page]);
                })
                ->visible(fn (): bool => Page::query()
                    ->where('section', 'e-services')
                    ->where('slug', $this->record->slug)
                    ->exists()),
        ];
    }

    /**
     * Rafraîchit la configuration e-services après sauvegarde.
     */
    protected function afterSave(): void
    {
        EServiceDefinitionResource::refreshRegistry();
    }
}
