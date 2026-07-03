<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration de création de la table posts.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('posts', function (Blueprint $table): void {
      $table->id();
      $table->string('title');
      $table->string('slug')->unique();
      $table->string('category')->nullable();
      $table->string('author')->nullable();
      $table->text('excerpt')->nullable();
      $table->longText('body')->nullable();
      $table->string('featured_image')->nullable();
      $table->string('meta_title')->nullable();
      $table->text('meta_description')->nullable();
      $table->boolean('is_published')->default(false);
      $table->timestamp('published_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('posts');
  }
};
