<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration ajoutant le gabarit d'affichage aux pages CMS.
 */
return new class extends Migration
{
    /**
     * Ajoute la colonne template à la table pages.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->string('template')->nullable()->after('body');
        });
    }

    /**
     * Supprime la colonne template de la table pages.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('template');
        });
    }
};
