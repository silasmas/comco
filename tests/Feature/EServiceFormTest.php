<?php

namespace Tests\Feature;

use App\Livewire\Public\EServiceForm;
use App\Models\EServiceDefinition;
use App\Models\Page;
use App\Support\EServiceRegistry;
use Database\Seeders\EServiceDefinitionSeeder;
use Database\Seeders\InstitutionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests des formulaires e-services publics et de leur administration.
 */
class EServiceFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie qu'une page e-service affiche le formulaire Livewire.
     */
    public function test_e_service_page_renders_online_form(): void
    {
        $this->seed([
            InstitutionSeeder::class,
            EServiceDefinitionSeeder::class,
        ]);

        EServiceRegistry::applyToConfig();

        $response = $this->get(route('sections.show', [
            'section' => 'e-services',
            'slug' => 'deposer-fusion',
        ]));

        $response->assertOk();
        $response->assertSee('Notification de fusion', false);
        $response->assertSeeLivewire(EServiceForm::class);
    }

    /**
     * Vérifie qu'une page e-service sans définition n'affiche pas de formulaire.
     */
    public function test_informational_e_service_page_has_no_form(): void
    {
        $this->seed(InstitutionSeeder::class);

        $response = $this->get(route('sections.show', [
            'section' => 'e-services',
            'slug' => 'manuels-utilisation',
        ]));

        $response->assertOk();
        $response->assertDontSeeLivewire(EServiceForm::class);
    }

    /**
     * Vérifie qu'une soumission e-service est enregistrée via Livewire.
     */
    public function test_e_service_form_submission_is_persisted(): void
    {
        $this->seed([
            InstitutionSeeder::class,
            EServiceDefinitionSeeder::class,
        ]);

        EServiceRegistry::applyToConfig();

        Livewire::test(EServiceForm::class, ['serviceSlug' => 'deposer-fusion'])
            ->set('name', 'Jean Dupont')
            ->set('email', 'jean.dupont@example.com')
            ->set('phone', '+243 000 000 000')
            ->set('description', 'Description détaillée du dossier de notification.')
            ->set('fields.companyName', 'Entreprise SA')
            ->set('fields.operationType', 'Fusion')
            ->set('fields.parties', 'Entreprise A et Entreprise B')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('e_service_submissions', [
            'service_slug' => 'deposer-fusion',
            'email' => 'jean.dupont@example.com',
        ]);
    }

    /**
     * Vérifie qu'une nouvelle définition crée la page CMS manquante.
     */
    public function test_new_e_service_definition_creates_missing_page(): void
    {
        $definition = EServiceDefinition::query()->create([
            'slug' => 'nouveau-service',
            'label' => 'Nouveau service',
            'intro' => 'Introduction du nouveau service en ligne.',
            'fields' => [
                [
                    'name' => 'reference',
                    'label' => 'Référence',
                    'type' => 'text',
                    'required' => true,
                ],
            ],
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $page = EServiceRegistry::ensurePage($definition);

        $this->assertSame('e-services', $page->section);
        $this->assertSame('nouveau-service', $page->slug);
        $this->assertSame('Nouveau service', $page->title);
    }

    /**
     * Vérifie que ensurePage ne remplace pas une page CMS existante.
     */
    public function test_ensure_page_keeps_existing_cms_content(): void
    {
        $this->seed(InstitutionSeeder::class);

        $existingPage = Page::query()
            ->where('section', 'e-services')
            ->where('slug', 'deposer-fusion')
            ->firstOrFail();

        $definition = EServiceDefinition::query()->create([
            'slug' => 'deposer-fusion',
            'label' => 'Libellé modifié',
            'intro' => 'Intro modifiée',
            'fields' => [],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $page = EServiceRegistry::ensurePage($definition);

        $this->assertTrue($existingPage->is($page));
        $this->assertSame('Déposer une fusion', $page->title);
    }
}
