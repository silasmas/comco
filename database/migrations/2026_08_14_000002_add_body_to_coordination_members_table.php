<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute un contenu détaillé aux fiches Coordination.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::table('coordination_members', function (Blueprint $table): void {
      $table->longText('body')->nullable()->after('summary');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::table('coordination_members', function (Blueprint $table): void {
      $table->dropColumn('body');
    });
  }
};
