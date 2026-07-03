<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Ajoute le flag super administrateur sur les utilisateurs.
   */
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->boolean('is_super_admin')->default(false)->after('password');
    });
  }

  /**
   * Supprime le flag super administrateur.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('is_super_admin');
    });
  }
};
