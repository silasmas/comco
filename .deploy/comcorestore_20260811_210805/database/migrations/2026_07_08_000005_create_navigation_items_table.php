<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration des éléments de navigation dynamiques.
 */
return new class extends Migration
{
    /**
     * Crée la table navigation_items.
     */
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table): void {
            $table->id();
            $table->string('menu');
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->cascadeOnDelete();
            $table->string('label');
            $table->string('link_type')->default('route');
            $table->string('route')->nullable();
            $table->string('section')->nullable();
            $table->string('slug')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['menu', 'parent_id', 'is_active']);
        });
    }

    /**
     * Supprime la table navigation_items.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_items');
    }
};
