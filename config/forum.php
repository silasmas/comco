<?php

/**
 * Configuration du forum public COMCO.
 */
return [
  'categories' => [
    'concurrence' => 'Concurrence',
    'consommateurs' => 'Consommateurs',
    'e-services' => 'E-services',
    'general' => 'Discussion générale',
  ],
  'statuses' => [
    'topic' => [
      'pending' => 'En attente',
      'approved' => 'Approuvé',
      'closed' => 'Fermé',
      'rejected' => 'Rejeté',
    ],
    'reply' => [
      'pending' => 'En attente',
      'approved' => 'Approuvé',
      'rejected' => 'Rejeté',
    ],
  ],
  'submissionStatuses' => [
    'pending' => 'En attente',
    'in_progress' => 'En cours',
    'resolved' => 'Traité',
    'rejected' => 'Rejeté',
  ],
];
