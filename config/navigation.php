<?php

/**
 * Structure de navigation du site COMCO
 * (arborescence institutionnelle — rapport de synthèse IT).
 *
 * Les clés `section` / `slug` restent stables pour ne pas casser les URLs CMS.
 */
return [
  'main' => [
    [
      'label' => 'Accueil',
      'route' => 'home',
    ],
    [
      'label' => 'Présentation',
      'section' => 'qui-sommes-nous',
      'slug' => 'presentation',
      'sidebar' => true,
      'children' => [
        ['label' => 'Mandat', 'slug' => 'notre-mandat'],
        ['label' => 'Mission', 'slug' => 'missions-services'],
        ['label' => 'Coordination', 'slug' => 'coordination'],
        ['label' => 'Partenaires', 'slug' => 'partenaires'],
        ['label' => 'Équipe', 'slug' => 'equipe'],
      ],
    ],
    [
      'label' => 'Actualités',
      'section' => 'centre-information',
      'slug' => 'actualites',
    ],
    [
      'label' => 'Nos textes et lois',
      'section' => 'centre-information',
      'children' => [
        ['label' => 'Lois', 'slug' => 'cadre-juridique'],
        ['label' => 'Décrets', 'slug' => 'decrets'],
        ['label' => 'Autres textes réglementaires', 'slug' => 'documentation-diverse'],
      ],
    ],
    [
      'label' => 'E-services',
      'section' => 'e-services',
      'children' => [
        ['label' => 'Dépôt de plainte', 'slug' => 'plainte-consommateur'],
        ['label' => 'Dénonciation', 'slug' => 'signaler-pratique'],
        ['label' => 'Fusions', 'slug' => 'deposer-fusion'],
        ['label' => 'Exemptions', 'slug' => 'deposer-exemption'],
        ['label' => 'Produits dangereux', 'slug' => 'produits-dangereux'],
      ],
    ],
    [
      'label' => 'Nos galeries',
      'section' => 'medias',
      'children' => [
        ['label' => 'Photos', 'slug' => 'galerie-photo'],
        ['label' => 'Vidéos', 'slug' => 'videotheque'],
      ],
    ],
    [
      'label' => 'Nous contacter',
      'route' => 'contact',
    ],
    [
      'label' => 'Plan du site',
      'route' => 'sitemap',
    ],
  ],
  'footer' => [
    'navigation' => [
      ['label' => 'Accueil', 'route' => 'home'],
      ['label' => 'Nous contacter', 'route' => 'contact'],
      ['label' => 'Plan du site', 'route' => 'sitemap'],
      ['label' => 'Ministère de l\'Economie', 'url' => 'https://economie.gouv.cd'],
    ],
    'eServices' => [
      ['label' => 'Fusions', 'section' => 'e-services', 'slug' => 'deposer-fusion'],
      ['label' => 'Dépôt de plainte', 'section' => 'e-services', 'slug' => 'plainte-consommateur'],
      ['label' => 'Dénonciation', 'section' => 'e-services', 'slug' => 'signaler-pratique'],
    ],
    'quickLinks' => [
      ['label' => 'Nos textes et lois', 'section' => 'centre-information', 'slug' => 'cadre-juridique'],
      ['label' => 'Actualités', 'section' => 'centre-information', 'slug' => 'actualites'],
      ['label' => 'Présentation', 'section' => 'qui-sommes-nous', 'slug' => 'presentation'],
      ['label' => 'Nos galeries', 'section' => 'medias', 'slug' => 'galerie-photo'],
    ],
  ],
  'sections' => [
    'qui-sommes-nous' => 'Présentation',
    'centre-information' => 'Nos textes et lois',
    'medias' => 'Nos galeries',
    'e-services' => 'E-services',
  ],
];
