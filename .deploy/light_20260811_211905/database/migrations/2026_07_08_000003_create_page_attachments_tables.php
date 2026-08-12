<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration des contenus attachés aux pages CMS (galerie, équipe, documents).
 */
return new class extends Migration
{
    /**
     * Crée les tables de contenus attachés aux pages.
     */
    public function up(): void
    {
        Schema::create('page_gallery_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('image_source')->default('theme');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_team_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->string('image_source')->default('theme');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_legal_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filename');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Supprime les tables de contenus attachés aux pages.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_legal_documents');
        Schema::dropIfExists('page_team_members');
        Schema::dropIfExists('page_gallery_items');
    }
};
