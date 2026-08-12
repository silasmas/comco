<?php

namespace App\Livewire\Concerns;

/**
 * Affiche des retours utilisateur instantanés dans les formulaires Livewire.
 */
trait WithUserFeedback
{
  public ?string $feedbackMessage = null;

  public string $feedbackType = 'success';

  /**
   * Affiche un message de succès après soumission.
   *
   * @param string $message Texte affiché à l'utilisateur
   */
  protected function notifySuccess(string $message): void
  {
    $this->pushFeedback($message, 'success');
  }

  /**
   * Affiche un message d'erreur après soumission.
   *
   * @param string $message Texte affiché à l'utilisateur
   */
  protected function notifyError(string $message): void
  {
    $this->pushFeedback($message, 'danger');
  }

  /**
   * Affiche un message informatif.
   *
   * @param string $message Texte affiché à l'utilisateur
   */
  protected function notifyInfo(string $message): void
  {
    $this->pushFeedback($message, 'info');
  }

  /**
   * Affiche le retour dans le formulaire et en notification toast.
   *
   * @param string $message Texte affiché à l'utilisateur
   * @param string $type success|danger|info|warning
   */
  protected function pushFeedback(string $message, string $type): void
  {
    $this->feedbackMessage = $message;
    $this->feedbackType = $type;
    $this->dispatch('show-toast', message: $message, type: $type);
  }
}
