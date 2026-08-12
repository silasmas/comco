<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration des paramètres dynamiques du site institutionnel.
 */
return new class extends Migration
{
    /**
     * Crée la table site_settings.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Supprime la table site_settings.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
