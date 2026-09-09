<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table des applications mobiles (APK) à partager via lien et QR code.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('mobile_apps', function (Blueprint $table): void {
      $table->id();
      $table->string('title');
      $table->string('slug')->unique();
      $table->string('version')->nullable();
      $table->string('file_path');
      $table->string('original_name')->nullable();
      $table->unsignedBigInteger('file_size')->nullable();
      $table->unsignedInteger('download_count')->default(0);
      $table->boolean('is_active')->default(true);
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('mobile_apps');
  }
};
