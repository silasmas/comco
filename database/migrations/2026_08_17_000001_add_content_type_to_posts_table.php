<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute le type de contenu (actualité / activité) sur les posts.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table->string('content_type', 32)->default('news')->after('slug');
      $table->index('content_type');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table->dropIndex(['content_type']);
      $table->dropColumn('content_type');
    });
  }
};
