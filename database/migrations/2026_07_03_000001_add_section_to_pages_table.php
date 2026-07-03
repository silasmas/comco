<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration ajoutant la section de navigation aux pages.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::table('pages', function (Blueprint $table): void {
      $table->string('section')->nullable()->after('slug');
      $table->unique(['section', 'slug']);
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::table('pages', function (Blueprint $table): void {
      $table->dropUnique(['section', 'slug']);
      $table->dropColumn('section');
    });
  }
};
