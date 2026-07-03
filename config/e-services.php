<?php

/**
 * Configuration des formulaires e-services COMCO.
 *
 * @return array<string, array{label: string, intro: string, fields: array<int, array<string, mixed>>}>
 */
return [
  'deposer-fusion' => [
    'label' => 'Notification de fusion',
    'intro' => 'Déposez votre notification d\'opération de concentration conformément à la Loi n° 18-020.',
    'fields' => [
      ['name' => 'companyName', 'label' => 'Entreprise notifiante', 'type' => 'text', 'required' => true],
      ['name' => 'operationType', 'label' => 'Type d\'opération', 'type' => 'select', 'required' => true, 'options' => ['Fusion', 'Acquisition', 'Création de joint-venture', 'Autre']],
      ['name' => 'parties', 'label' => 'Parties concernées', 'type' => 'textarea', 'required' => true, 'rows' => 3],
    ],
  ],
  'deposer-exemption' => [
    'label' => 'Demande d\'exemption',
    'intro' => 'Soumettez une demande d\'exemption relative à un accord ou une pratique commerciale.',
    'fields' => [
      ['name' => 'companyName', 'label' => 'Entreprise demandeur', 'type' => 'text', 'required' => true],
      ['name' => 'agreementSubject', 'label' => 'Objet de l\'accord ou de la pratique', 'type' => 'textarea', 'required' => true, 'rows' => 4],
    ],
  ],
  'plainte-service' => [
    'label' => 'Plainte de service',
    'intro' => 'Déposez une plainte relative à une pratique commerciale ou un service anticoncurrentiel.',
    'fields' => [
      ['name' => 'companyName', 'label' => 'Entreprise plaignante', 'type' => 'text', 'required' => true],
      ['name' => 'reportedEntity', 'label' => 'Entité visée', 'type' => 'text', 'required' => true],
      ['name' => 'practiceType', 'label' => 'Nature de la pratique', 'type' => 'select', 'required' => true, 'options' => ['Entente', 'Abus de position dominante', 'Fixation des prix', 'Autre']],
    ],
  ],
  'plainte-consommateur' => [
    'label' => 'Plainte consommateur',
    'intro' => 'Signalez une pratique abusive ou trompeuse affectant vos droits de consommateur.',
    'fields' => [
      ['name' => 'merchantName', 'label' => 'Commerçant ou entreprise visée', 'type' => 'text', 'required' => true],
      ['name' => 'productService', 'label' => 'Produit ou service concerné', 'type' => 'text', 'required' => true],
      ['name' => 'purchaseDate', 'label' => 'Date approximative des faits', 'type' => 'text', 'required' => false],
    ],
  ],
  'signaler-pratique' => [
    'label' => 'Signalement confidentiel',
    'intro' => 'Signalez une pratique anticoncurrentielle. Votre dossier sera traité de manière confidentielle.',
    'fields' => [
      ['name' => 'reportedEntity', 'label' => 'Entité ou personne visée', 'type' => 'text', 'required' => true],
      ['name' => 'practiceType', 'label' => 'Type de pratique', 'type' => 'select', 'required' => true, 'options' => ['Entente', 'Abus de position dominante', 'Fixation des prix', 'Pratique trompeuse', 'Autre']],
      ['name' => 'confidential', 'label' => 'Je souhaite un traitement confidentiel', 'type' => 'checkbox', 'required' => false],
    ],
  ],
  'produits-dangereux' => [
    'label' => 'Signalement produit dangereux',
    'intro' => 'Signalez un produit dangereux ou non conforme identifié sur le marché.',
    'fields' => [
      ['name' => 'productName', 'label' => 'Nom du produit', 'type' => 'text', 'required' => true],
      ['name' => 'location', 'label' => 'Lieu de constat', 'type' => 'text', 'required' => true],
      ['name' => 'dangerDescription', 'label' => 'Description du danger', 'type' => 'textarea', 'required' => true, 'rows' => 3],
    ],
  ],
];
