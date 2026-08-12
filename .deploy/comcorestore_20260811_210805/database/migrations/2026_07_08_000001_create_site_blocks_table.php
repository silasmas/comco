<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration des blocs de contenu dynamique du site public.
 */
return new class extends Migration
{
    /**
     * Crée la table site_blocks.
     */
    public function up(): void
    {
        Schema::create('site_blocks', function (Blueprint $table): void {
            $table->id();
            $table->string('page');
            $table->string('block_type');
            $table->string('block_key')->nullable();
            $table->string('label')->nullable();
            $table->json('payload');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['page', 'block_type', 'is_active']);
            $table->unique(['page', 'block_key']);
        });
    }

    /**
     * Supprime la table site_blocks.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_blocks');
    }
};
