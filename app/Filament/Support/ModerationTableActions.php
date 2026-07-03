<?php

namespace App\Filament\Support;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Actions de validation / rejet réutilisables dans les tableaux Filament.
 */
class ModerationTableActions
{
  /**
   * Action ligne pour approuver un enregistrement.
   *
   * @param string $status Valeur du statut approuvé
   */
  public static function approveRecord(string $status = 'approved'): Action
  {
    return Action::make('approve')
      ->label('Approuver')
      ->icon('heroicon-o-check-circle')
      ->color('success')
      ->requiresConfirmation()
      ->visible(fn (Model $record): bool => ($record->status ?? null) === 'pending')
      ->action(fn (Model $record) => $record->update(['status' => $status]));
  }

  /**
   * Action ligne pour rejeter un enregistrement.
   */
  public static function rejectRecord(): Action
  {
    return Action::make('reject')
      ->label('Rejeter')
      ->icon('heroicon-o-x-circle')
      ->color('danger')
      ->requiresConfirmation()
      ->visible(fn (Model $record): bool => ($record->status ?? null) === 'pending')
      ->action(fn (Model $record) => $record->update(['status' => 'rejected']));
  }

  /**
   * Action ligne pour marquer une soumission comme traitée.
   */
  public static function resolveRecord(): Action
  {
    return Action::make('resolve')
      ->label('Marquer traité')
      ->icon('heroicon-o-check-badge')
      ->color('success')
      ->requiresConfirmation()
      ->visible(fn (Model $record): bool => in_array($record->status ?? null, ['pending', 'in_progress'], true))
      ->action(fn (Model $record) => $record->update(['status' => 'resolved']));
  }

  /**
   * Actions groupées d'approbation / rejet pour le forum.
   *
   * @return list<BulkAction|DeleteBulkAction>
   */
  public static function forumBulkActions(): array
  {
    return [
      self::approveBulkAction('approved'),
      self::rejectBulkAction(),
      DeleteBulkAction::make(),
    ];
  }

  /**
   * Actions groupées pour les soumissions (contact, e-services).
   *
   * @return list<BulkAction|DeleteBulkAction>
   */
  public static function submissionBulkActions(): array
  {
    return [
      BulkAction::make('markResolved')
        ->label('Marquer traité')
        ->icon('heroicon-o-check-badge')
        ->color('success')
        ->requiresConfirmation()
        ->action(fn (Collection $records) => $records->each(
          fn (Model $record) => $record->update(['status' => 'resolved'])
        )),
      BulkAction::make('markRejected')
        ->label('Rejeter')
        ->icon('heroicon-o-x-circle')
        ->color('danger')
        ->requiresConfirmation()
        ->action(fn (Collection $records) => $records->each(
          fn (Model $record) => $record->update(['status' => 'rejected'])
        )),
      DeleteBulkAction::make(),
    ];
  }

  /**
   * Action groupée d'approbation.
   *
   * @param string $status Statut cible
   */
  public static function approveBulkAction(string $status = 'approved'): BulkAction
  {
    return BulkAction::make('approve')
      ->label('Approuver')
      ->icon('heroicon-o-check-circle')
      ->color('success')
      ->requiresConfirmation()
      ->action(fn (Collection $records) => $records->each(
        fn (Model $record) => $record->update(['status' => $status])
      ));
  }

  /**
   * Action groupée de rejet.
   */
  public static function rejectBulkAction(): BulkAction
  {
    return BulkAction::make('reject')
      ->label('Rejeter')
      ->icon('heroicon-o-x-circle')
      ->color('danger')
      ->requiresConfirmation()
      ->action(fn (Collection $records) => $records->each(
        fn (Model $record) => $record->update(['status' => 'rejected'])
      ));
  }
}
