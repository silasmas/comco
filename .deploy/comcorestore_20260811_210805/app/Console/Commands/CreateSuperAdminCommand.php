<?php

namespace App\Console\Commands;

use App\Support\SiteInstaller;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Crée un super administrateur pour l'accès au panneau Filament.
 */
class CreateSuperAdminCommand extends Command
{
  protected $signature = 'comco:super-admin
                            {--name=Super Admin : Nom de l\'administrateur}
                            {--email=superadmin@comco.gouv.cd : Adresse email}
                            {--password= : Mot de passe (généré automatiquement si absent)}';

  protected $description = 'Crée ou met à jour un super administrateur COMCO';

  /**
   * Exécute la création du super administrateur.
   */
  public function handle(): int
  {
    $name = (string) $this->option('name');
    $email = (string) $this->option('email');
    $password = (string) ($this->option('password') ?: Str::password(16));

    SiteInstaller::createSuperAdmin($name, $email, $password);

    $this->info('Super administrateur créé ou mis à jour avec succès.');
    $this->line('Email : ' . $email);
    $this->line('Mot de passe : ' . $password);

    return self::SUCCESS;
  }
}
