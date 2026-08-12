<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Vérifie que les assets publics critiques du thème sont présents.
 */
class PublicThemeAssetsTest extends TestCase
{
  /**
   * Empêche un déploiement sans le CSS Elixir (site sans style).
   */
  public function test_theme_min_css_exists(): void
  {
    $this->assertFileExists(public_path('theme/assets/css/theme.min.css'));
  }

  /**
   * Vérifie la présence du CSS utilisateur COMCO.
   */
  public function test_user_min_css_exists(): void
  {
    $this->assertFileExists(public_path('theme/assets/css/user.min.css'));
  }
}
