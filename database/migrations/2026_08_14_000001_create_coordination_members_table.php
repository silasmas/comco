<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table des membres de la coordination COMCO.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('coordination_members', function (Blueprint $table): void {
      $table->id();
      $table->string('title');
      $table->text('summary')->nullable();
      $table->string('image')->nullable();
      $table->string('image_source')->default('storage');
      $table->string('link_url')->nullable();
      $table->string('link_label')->nullable();
      $table->unsignedInteger('sort_order')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });

    Schema::table('page_team_members', function (Blueprint $table): void {
      $table->string('link_url')->nullable()->after('image_source');
      $table->string('link_label')->nullable()->after('link_url');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::table('page_team_members', function (Blueprint $table): void {
      $table->dropColumn(['link_url', 'link_label']);
    });

    Schema::dropIfExists('coordination_members');
  }
};
