<?php

namespace App\Filament\Resources\SiteBlocks\Concerns;

use App\Models\SiteBlock;
use App\Support\HomeSlideStyle;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View as ViewContract;

/**
 * Action d'aperçu slide (panneau latéral PC / Mobile) pour les pages Create/Edit.
 */
trait HasSlidePreviewAction
{
    /**
     * Action d'en-tête basée sur l'état brut du formulaire Livewire.
     *
     * @return Action Action configurée
     */
    protected function makeHeaderSlidePreviewAction(): Action
    {
        return Action::make('previewSlideHeader')
            ->label('Aperçu du slide')
            ->icon(Heroicon::OutlinedEye)
            ->color('warning')
            ->slideOver()
            ->modalWidth(Width::Screen)
            ->modalHeading('Aperçu du slide')
            ->modalDescription('Basculez entre PC et mobile pour vérifier le rendu avant publication.')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Fermer')
            ->visible(fn (): bool => ($this->data['block_type'] ?? null) === SiteBlock::TYPE_SLIDER)
            ->modalContent(function (): ViewContract {
                return view('filament.site-blocks.slide-preview-panel', [
                    'preview' => HomeSlideStyle::previewFromArray($this->data ?? []),
                ]);
            });
    }
}
