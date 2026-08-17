<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute la mise en avant (modale d'entrée) aux posts.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table->boolean('is_spotlight')->default(false)->after('is_published');
      $table->json('spotlight_images')->nullable()->after('featured_video');
      $table->string('spotlight_video_mode', 16)->default('normal')->after('spotlight_images');
      $table->text('spotlight_text')->nullable()->after('spotlight_video_mode');
      $table->index('is_spotlight');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table->dropIndex(['is_spotlight']);
      $table->dropColumn([
        'is_spotlight',
        'spotlight_images',
        'spotlight_video_mode',
        'spotlight_text',
      ]);
    });
  }
};
