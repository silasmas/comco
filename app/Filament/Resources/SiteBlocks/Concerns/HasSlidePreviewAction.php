<?php

namespace App\Filament\Resources\SiteBlocks\Concerns;

use App\Models\SiteBlock;
use App\Support\HomeSlideStyle;
use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View as ViewContract;

/**
 * Action d'aperçu slide (panneau latéral PC / Mobile).
 */
trait HasSlidePreviewAction
{
    /**
     * Construit l'action Filament d'ouverture de l'aperçu latéral.
     *
     * @return Action Action configurée
     */
    protected static function makeSlidePreviewAction(): Action
    {
        return Action::make('previewSlide')
            ->label('Ouvrir l’aperçu PC / Mobile')
            ->icon(Heroicon::OutlinedEye)
            ->color('warning')
            ->slideOver()
            ->modalWidth(Width::Screen)
            ->modalHeading('Aperçu du slide')
            ->modalDescription('Basculez entre PC et mobile pour vérifier le rendu avant publication.')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Fermer')
            ->visible(fn (Get $get): bool => $get('block_type') === SiteBlock::TYPE_SLIDER)
            ->modalContent(function (Get $get): ViewContract {
                return view('filament.site-blocks.slide-preview-panel', [
                    'preview' => HomeSlideStyle::previewFromForm($get),
                ]);
            });
    }

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
