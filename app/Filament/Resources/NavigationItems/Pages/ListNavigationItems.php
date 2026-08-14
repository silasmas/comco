<?php

namespace App\Filament\Resources\NavigationItems\Pages;

use App\Filament\Resources\NavigationItems\NavigationItemResource;
use App\Filament\Resources\Pages\ComcoListRecords;
use App\Models\NavigationItem;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

/**
 * Page de liste des éléments de navigation.
 */
class ListNavigationItems extends ComcoListRecords
{
  protected static string $resource = NavigationItemResource::class;

  /**
   * Onglets de regroupement au-dessus du tableau (menu, type, parent).
   *
   * @return array<string, Tab>
   */
  public function getTabs(): array
  {
    $tabs = [
      'all' => Tab::make('Tous')
        ->badge(fn (): int => NavigationItem::query()->count()),
    ];

    foreach (NavigationItem::menuLabels() as $menu => $label) {
      $tabs['menu_'.$menu] = Tab::make($label)
        ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('menu', $menu))
        ->badge(fn () => NavigationItem::query()->where('menu', $menu)->count());
    }

    foreach (NavigationItem::linkTypeLabels() as $type => $label) {
      $tabs['type_'.$type] = Tab::make($label)
        ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('link_type', $type))
        ->badge(fn () => NavigationItem::query()->where('link_type', $type)->count());
    }

    $tabs['roots'] = Tab::make('Sans parent')
      ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereNull('parent_id'))
      ->badge(fn (): int => NavigationItem::query()->whereNull('parent_id')->count());

    $parents = NavigationItem::query()
      ->whereIn('id', NavigationItem::query()->whereNotNull('parent_id')->select('parent_id'))
      ->orderBy('label')
      ->get(['id', 'label']);

    foreach ($parents as $parent) {
      $tabs['parent_'.$parent->id] = Tab::make('Sous « '.$parent->label.' »')
        ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('parent_id', $parent->id))
        ->badge(fn () => NavigationItem::query()->where('parent_id', $parent->id)->count());
    }

    return $tabs;
  }

  /**
   * Retourne les actions disponibles dans l'en-tête de la liste.
   *
   * @return list<Action>
   */
  protected function getHeaderActions(): array
  {
    return [
      CreateAction::make(),
    ];
  }
}
