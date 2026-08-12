<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\EServiceDefinitions\EServiceDefinitionResource;
use App\Filament\Resources\Pages\ComcoEditRecord;
use App\Filament\Resources\Pages\PageResource;
use App\Support\EServiceRegistry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;

/**
 * Page d'édition d'une page CMS institutionnelle.
 */
class EditPage extends ComcoEditRecord
{
    protected static string $resource = PageResource::class;

    /**
     * Retourne les actions disponibles dans l'en-tête de la page.
     *
     * @return list<Action|DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return array_merge(
            $this->eServiceFormActions(),
            [
                DeleteAction::make(),
            ],
        );
    }

    /**
     * Ajoute les actions de gestion du formulaire en ligne pour les pages e-services.
     *
     * @return list<Action>
     */
    private function eServiceFormActions(): array
    {
        $record = $this->getRecord();

        if ($record->section !== 'e-services') {
            return [];
        }

        $definition = EServiceRegistry::findDefinition($record->slug);

        $actions = [
            Action::make('viewPublicPage')
                ->label('Voir la page publique')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(route('sections.show', ['section' => 'e-services', 'slug' => $record->slug]))
                ->openUrlInNewTab(),
        ];

        if ($definition !== null) {
            $actions[] = Action::make('editForm')
                ->label('Configurer le formulaire')
                ->icon(Heroicon::OutlinedClipboardDocumentList)
                ->url(EServiceDefinitionResource::getUrl('edit', ['record' => $definition]));
        } else {
            $actions[] = Action::make('createForm')
                ->label('Ajouter un formulaire en ligne')
                ->icon(Heroicon::OutlinedPlus)
                ->url(EServiceDefinitionResource::getUrl('create').'?slug='.urlencode($record->slug));
        }

        return $actions;
    }
}
