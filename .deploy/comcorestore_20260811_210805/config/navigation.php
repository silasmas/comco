<?php

/**
 * Structure de navigation du site COMCO (documents client + réunion IT 2024).
 */
return [
  'main' => [
    [
      'label' => 'Accueil',
      'route' => 'home',
    ],
    [
      'label' => 'Qui sommes-nous',
      'section' => 'qui-sommes-nous',
      'children' => [
        ['label' => 'Présentation', 'slug' => 'presentation'],
        ['label' => 'Notre mandat', 'slug' => 'notre-mandat'],
        ['label' => 'Missions & Services', 'slug' => 'missions-services'],
        ['label' => 'Partenaires', 'slug' => 'partenaires'],
        ['label' => 'Coordination', 'slug' => 'coordination'],
      ],
    ],
    [
      'label' => 'Centre d\'information',
      'section' => 'centre-information',
      'children' => [
        ['label' => 'Actualités', 'slug' => 'actualites'],
        ['label' => 'Concurrence & consommateurs', 'slug' => 'concurrence-consommateurs'],
        ['label' => 'Cadre juridique', 'slug' => 'cadre-juridique'],
        ['label' => 'Documentation diverse', 'slug' => 'documentation-diverse'],
      ],
    ],
    [
      'label' => 'Médias',
      'section' => 'medias',
      'children' => [
        ['label' => 'Galerie Photo', 'slug' => 'galerie-photo'],
        ['label' => 'Vidéothèque', 'slug' => 'videotheque'],
      ],
    ],
    [
      'label' => 'E-services',
      'section' => 'e-services',
      'children' => [
        ['label' => 'Déposer une fusion', 'slug' => 'deposer-fusion'],
        ['label' => 'Déposer une exemption', 'slug' => 'deposer-exemption'],
        ['label' => 'Plainte de service', 'slug' => 'plainte-service'],
        ['label' => 'Plainte consommateur', 'slug' => 'plainte-consommateur'],
        ['label' => 'Signaler une pratique', 'slug' => 'signaler-pratique'],
        ['label' => 'Produits dangereux', 'slug' => 'produits-dangereux'],
        ['label' => 'Manuels d\'utilisation', 'slug' => 'manuels-utilisation'],
      ],
    ],
    [
      'label' => 'Forum',
      'route' => 'forum.index',
    ],
    [
      'label' => 'Contact',
      'route' => 'contact',
    ],
  ],
  'footer' => [
    'navigation' => [
      ['label' => 'Accueil', 'route' => 'home'],
      ['label' => 'Contact', 'route' => 'contact'],
      ['label' => 'Ministère de l\'Economie', 'url' => 'https://economie.gouv.cd'],
    ],
    'eServices' => [
      ['label' => 'Déposer une fusion', 'section' => 'e-services', 'slug' => 'deposer-fusion'],
      ['label' => 'Plainte consommateur', 'section' => 'e-services', 'slug' => 'plainte-consommateur'],
      ['label' => 'Signaler une pratique', 'section' => 'e-services', 'slug' => 'signaler-pratique'],
    ],
    'quickLinks' => [
      ['label' => 'Cadre juridique', 'section' => 'centre-information', 'slug' => 'cadre-juridique'],
      ['label' => 'Forum', 'route' => 'forum.index'],
      ['label' => 'Actualités', 'section' => 'centre-information', 'slug' => 'actualites'],
      ['label' => 'Présentation COMCO', 'section' => 'qui-sommes-nous', 'slug' => 'presentation'],
    ],
  ],
  'sections' => [
    'qui-sommes-nous' => 'Qui sommes-nous',
    'centre-information' => 'Centre d\'information',
    'medias' => 'Médias',
    'e-services' => 'E-services',
  ],
];
