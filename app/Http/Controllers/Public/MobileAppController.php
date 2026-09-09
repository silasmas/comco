<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MobileApp;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Pages publiques de partage et téléchargement d'applications Android (APK).
 */
class MobileAppController extends Controller
{
  /**
   * Affiche la page de partage (lien + QR + bouton de téléchargement).
   *
   * @param  string  $slug  Slug de l'application
   * @return View Vue publique
   */
  public function show(string $slug): View
  {
    $app = MobileApp::query()
      ->active()
      ->where('slug', $slug)
      ->firstOrFail();

    abort_unless($app->fileExists(), 404);

    return view('public.mobile-apps.show', [
      'app' => $app,
    ]);
  }

  /**
   * Force le téléchargement du fichier APK.
   *
   * @param  string  $slug  Slug de l'application
   * @return BinaryFileResponse Fichier APK
   */
  public function download(string $slug): BinaryFileResponse
  {
    $app = MobileApp::query()
      ->active()
      ->where('slug', $slug)
      ->firstOrFail();

    $path = $app->absoluteFilePath();
    abort_unless($path !== null, 404);

    $app->increment('download_count');

    return response()->download($path, $app->downloadFileName(), [
      'Content-Type' => 'application/vnd.android.package-archive',
    ]);
  }
}
