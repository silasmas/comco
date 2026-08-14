<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CoordinationMember;
use Illuminate\View\View;

/**
 * Affiche la fiche détaillée d'un élément de Coordination.
 */
class CoordinationController extends Controller
{
  /**
   * Affiche une fiche Coordination active.
   *
   * @param  CoordinationMember  $member  Fiche demandée
   * @return View Vue de détail
   */
  public function show(CoordinationMember $member): View
  {
    abort_unless($member->is_active, 404);

    $related = CoordinationMember::query()
      ->active()
      ->ordered()
      ->whereKeyNot($member->id)
      ->limit(3)
      ->get();

    return view('public.pages.coordination-show', [
      'member' => $member,
      'related' => $related,
      'metaTitle' => $member->title.' | Coordination | '.config('institution.name'),
      'metaDescription' => $member->summary ?: config('institution.seo.defaultDescription'),
    ]);
  }
}
