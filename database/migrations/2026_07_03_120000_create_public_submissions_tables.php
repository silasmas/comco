<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée les tables de soumissions publiques (e-services, contact, newsletter).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('e_service_submissions', function (Blueprint $table): void {
      $table->id();
      $table->string('service_slug', 64);
      $table->string('name');
      $table->string('email');
      $table->string('phone', 32)->nullable();
      $table->text('description');
      $table->json('payload')->nullable();
      $table->string('status', 32)->default('pending');
      $table->timestamps();
    });

    Schema::create('contact_messages', function (Blueprint $table): void {
      $table->id();
      $table->string('name');
      $table->string('email');
      $table->text('message');
      $table->string('status', 32)->default('pending');
      $table->timestamps();
    });

    Schema::create('newsletter_subscribers', function (Blueprint $table): void {
      $table->id();
      $table->string('email')->unique();
      $table->timestamp('subscribed_at')->useCurrent();
      $table->timestamps();
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('newsletter_subscribers');
    Schema::dropIfExists('contact_messages');
    Schema::dropIfExists('e_service_submissions');
  }
};
