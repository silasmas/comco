<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée les tables du forum public COMCO.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('forum_topics', function (Blueprint $table): void {
      $table->id();
      $table->string('title');
      $table->string('slug')->unique();
      $table->string('category', 64);
      $table->text('body');
      $table->string('author_name');
      $table->string('author_email');
      $table->string('status', 32)->default('pending');
      $table->unsignedInteger('views')->default(0);
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->timestamps();
    });

    Schema::create('forum_replies', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('forum_topic_id')->constrained()->cascadeOnDelete();
      $table->text('body');
      $table->string('author_name');
      $table->string('author_email');
      $table->string('status', 32)->default('pending');
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('forum_replies');
    Schema::dropIfExists('forum_topics');
  }
};
