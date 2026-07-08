<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration des définitions dynamiques des e-services COMCO.
 */
return new class extends Migration
{
    /**
     * Crée la table e_service_definitions.
     */
    public function up(): void
    {
        Schema::create('e_service_definitions', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->text('intro');
            $table->json('fields');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Supprime la table e_service_definitions.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_service_definitions');
    }
};
