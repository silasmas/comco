<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute le champ vidéo aux actualités.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table->string('featured_video')->nullable()->after('featured_image');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table->dropColumn('featured_video');
    });
  }
};
