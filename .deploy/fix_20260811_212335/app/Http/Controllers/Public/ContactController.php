<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\ContactPageContent;
use Illuminate\View\View;

/**
 * Contrôleur de la page contact publique.
 */
class ContactController extends Controller
{
    /**
     * Affiche la page contact institutionnelle.
     *
     * @return View Vue Blade de contact
     */
    public function show(): View
    {
        return view('public.pages.contact', [
            'metaTitle' => 'Contact | '.config('institution.name'),
            'metaDescription' => 'Contactez la Commission de la Concurrence (COMCO) — République Démocratique du Congo.',
            'contactContent' => ContactPageContent::resolve(),
        ]);
    }
}
