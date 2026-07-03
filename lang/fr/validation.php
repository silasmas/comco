<?php

return [
  'required' => 'Le champ :attribute est obligatoire.',
  'email' => 'Le champ :attribute doit être une adresse email valide.',
  'string' => 'Le champ :attribute doit être une chaîne de caractères.',
  'max' => [
    'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    'numeric' => 'Le champ :attribute ne peut pas être supérieur à :max.',
  ],
  'min' => [
    'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    'numeric' => 'Le champ :attribute doit être au moins :min.',
  ],
  'in' => 'La valeur sélectionnée pour :attribute est invalide.',
  'boolean' => 'Le champ :attribute doit être vrai ou faux.',
  'nullable' => '',

  'attributes' => [
    'name' => 'nom complet',
    'email' => 'adresse email',
    'phone' => 'téléphone',
    'message' => 'message',
    'title' => 'titre',
    'category' => 'catégorie',
    'authorName' => 'nom',
    'authorEmail' => 'email',
    'body' => 'message',
    'description' => 'description détaillée',
    'companyName' => 'entreprise',
    'operationType' => 'type d\'opération',
    'parties' => 'parties concernées',
    'agreementSubject' => 'objet de l\'accord',
    'reportedEntity' => 'entité visée',
    'practiceType' => 'nature de la pratique',
    'merchantName' => 'commerçant',
    'productService' => 'produit ou service',
    'productName' => 'nom du produit',
    'location' => 'lieu',
    'dangerDescription' => 'description du danger',
  ],
];
