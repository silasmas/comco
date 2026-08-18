<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute le mode d'affichage du contenu des pages CMS (texte, PDF ou les deux).
 */
return new class extends Migration
{
    /**
     * Exécute la migration.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->string('content_display', 20)
                ->default('content')
                ->after('template');
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('content_display');
        });
    }
};
