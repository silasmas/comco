<?php

namespace App\Livewire\Public;

use App\Livewire\Concerns\WithUserFeedback;
use App\Models\EServiceSubmission;
use App\Support\EServiceRegistry;
use App\Support\InstitutionNotifier;
use App\Support\SubmitterNotifier;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

/**
 * Formulaire dynamique pour les e-services COMCO.
 */
class EServiceForm extends Component
{
    use WithUserFeedback;

    public string $serviceSlug = '';

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $description = '';

    /** @var array<string, mixed> */
    public array $fields = [];

    /**
     * Initialise le formulaire selon le slug e-service.
     *
     * @param  string  $serviceSlug  Identifiant du service (ex. deposer-fusion)
     */
    public function mount(string $serviceSlug): void
    {
        $this->serviceSlug = $serviceSlug;
        $fields = EServiceRegistry::get($serviceSlug)['fields'] ?? [];

        foreach ($fields as $field) {
            $name = $field['name'];
            $this->fields[$name] = ($field['type'] ?? 'text') === 'checkbox' ? false : '';
        }
    }

    /**
     * Valide et enregistre la soumission e-service.
     */
    public function submit(): void
    {
        $serviceConfig = EServiceRegistry::get($this->serviceSlug);

        if ($serviceConfig === []) {
            return;
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'description' => ['required', 'string', 'min:20'],
        ];

        foreach ($serviceConfig['fields'] as $field) {
            $fieldName = 'fields.'.$field['name'];
            $fieldRules = [];

            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if (($field['type'] ?? 'text') === 'checkbox') {
                $fieldRules[] = 'boolean';
            } else {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:2000';
            }

            $rules[$fieldName] = $fieldRules;
        }

        $attributes = [
            'name' => 'nom complet',
            'email' => 'adresse email',
            'phone' => 'téléphone',
            'description' => 'description détaillée',
        ];

        foreach ($serviceConfig['fields'] as $field) {
            $attributes['fields.'.$field['name']] = $field['label'];
        }

        $validated = Validator::make(
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'description' => $this->description,
                'fields' => $this->fields,
            ],
            $rules,
            [],
            $attributes,
        )->validate();

        EServiceSubmission::create([
            'service_slug' => $this->serviceSlug,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'description' => $validated['description'],
            'payload' => $validated['fields'],
        ]);

        $mailLines = [
            'Service' => $serviceConfig['label'] ?? $this->serviceSlug,
            'Nom' => $validated['name'],
            'Email' => $validated['email'],
            'Téléphone' => $validated['phone'] ?? '—',
            'Description' => $validated['description'],
        ];

        foreach ($serviceConfig['fields'] as $field) {
            $value = $validated['fields'][$field['name']] ?? '';
            $mailLines[$field['label']] = is_bool($value) ? ($value ? 'Oui' : 'Non') : $value;
        }

        InstitutionNotifier::notify('Nouvelle soumission e-service COMCO', $mailLines);

        SubmitterNotifier::confirm(
            email: $validated['email'],
            recipientName: $validated['name'],
            subject: 'Confirmation de votre dossier e-service — COMCO',
            intro: 'Votre dossier a bien été transmis à la COMCO. Il sera examiné par nos services compétents. Vous serez recontacté si des informations complémentaires sont nécessaires.',
            details: $mailLines,
        );

        $this->reset(['name', 'email', 'phone', 'description']);
        $this->mount($this->serviceSlug);
        $this->notifySuccess('Votre dossier a bien été transmis à la COMCO. Vous recevrez une confirmation par email.');
    }

    /**
     * Rendu du formulaire e-service.
     *
     * @return View Vue Blade du formulaire
     */
    public function render(): View
    {
        return view('livewire.public.e-service-form', [
            'serviceConfig' => EServiceRegistry::get($this->serviceSlug),
        ]);
    }
}
