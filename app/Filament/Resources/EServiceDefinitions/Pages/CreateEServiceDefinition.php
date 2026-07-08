<?php

namespace App\Filament\Resources\EServiceDefinitions\Pages;

use App\Filament\Resources\EServiceDefinitions\EServiceDefinitionResource;
use App\Models\Page;
use App\Support\EServiceRegistry;
use Filament\Resources\Pages\CreateRecord;

/**
 * Page de création d'une définition de formulaire e-service.
 */
class CreateEServiceDefinition extends CreateRecord
{
    protected static string $resource = EServiceDefinitionResource::class;

    /**
     * Préremplit le slug depuis la page CMS associée le cas échéant.
     */
    public function mount(): void
    {
        parent::mount();

        $slug = request()->string('slug')->toString();

        if ($slug === '') {
            return;
        }

        $page = Page::query()
            ->where('section', 'e-services')
            ->where('slug', $slug)
            ->first();

        $this->form->fill([
            'slug' => $slug,
            'label' => $page?->title ?? '',
            'intro' => $page?->excerpt ?? '',
        ]);
    }

    /**
     * Crée la page CMS manquante et rafraîchit le registre e-services.
     */
    protected function afterCreate(): void
    {
        EServiceRegistry::ensurePage($this->record);
        EServiceDefinitionResource::refreshRegistry();
    }
}
