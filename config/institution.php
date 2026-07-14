<?php

/**
 * Configuration globale du site institutionnel COMCO.
 */
return [
  'name' => env('INSTITUTION_NAME', 'COMCO'),
  'fullName' => env('INSTITUTION_FULL_NAME', 'Commission de la Concurrence'),
  'shortName' => env('INSTITUTION_SHORT_NAME', 'COMCO'),
  'tagline' => env(
    'INSTITUTION_TAGLINE',
    'Nous veillons à ce qu\'aucune entreprise ne domine injustement le marché et que la libre concurrence profite à toute l\'économie.'
  ),
  'locale' => env('APP_LOCALE', 'fr'),
  'contact' => [
    'email' => env('INSTITUTION_EMAIL', 'contact@comco.gouv.cd'),
    'phone' => env('INSTITUTION_PHONE', '+243 892 150 000'),
    'address' => env('INSTITUTION_ADDRESS', 'Avenue Ouganda, n°4216, Quartier des Cliniques, Kinshasa-Gombe'),
    'mapEmbedUrl' => env(
      'INSTITUTION_MAP_EMBED_URL',
      'https://maps.google.com/maps?q=Avenue+Ouganda+4216+Quartier+des+Cliniques+Kinshasa-Gombe+Democratic+Republic+of+the+Congo&hl=fr&z=18&output=embed'
    ),
    'mapLinkUrl' => env(
      'INSTITUTION_MAP_LINK_URL',
      'https://www.google.com/maps/search/?api=1&query=Avenue+Ouganda+4216+Quartier+des+Cliniques+Kinshasa-Gombe+Democratic+Republic+of+the+Congo'
    ),
  ],
  'social' => [
    'twitter' => env('INSTITUTION_TWITTER'),
    'facebook' => env('INSTITUTION_FACEBOOK'),
    'linkedin' => env('INSTITUTION_LINKEDIN'),
    'youtube' => env('INSTITUTION_YOUTUBE'),
  ],
  'seo' => [
    'titleSuffix' => env('SEO_TITLE_SUFFIX', ' | COMCO - RDC'),
    'defaultDescription' => env(
      'SEO_DEFAULT_DESCRIPTION',
      'Site officiel de la Commission de la Concurrence (COMCO) - République Démocratique du Congo.'
    ),
  ],
  'ministry' => [
    'name' => env('INSTITUTION_MINISTRY_NAME', 'Ministère des Finances'),
    'logo' => env('INSTITUTION_MINISTRY_LOGO', 'logo-minfinances.png'),
    'url' => env('INSTITUTION_MINISTRY_URL', 'https://finances.gouv.cd'),
  ],
  'homeTabs' => [
    'actualite' => 'Notre actualité',
    'une' => 'A la une',
    'activites' => 'Nos activités',
  ],
  'slider' => [
    [
      'title' => 'Bienvenue à la COMCO',
      'text' => 'Nous veillons à ce qu\'aucune entreprise ne domine injustement le marché.',
      'image' => '1.jpg.jpeg',
    ],
    [
      'title' => 'Liberté des prix et concurrence',
      'text' => 'La Commission de la Concurrence protège l\'économie congolaise.',
      'image' => '2.jpg.jpeg',
    ],
    [
      'title' => 'Surveillance des marchés',
      'text' => 'Contrôle, sensibilisation et application du cadre juridique en RDC.',
      'image' => '3.jpg.jpeg',
    ],
    [
      'title' => 'Protection du consommateur',
      'text' => 'Signalez les produits dangereux et les pratiques anticoncurrentielles.',
      'image' => '4.jpg.jpeg',
    ],
  ],
  'welcomeItems' => [
    [
      'title' => 'Régulation',
      'desc' => 'Veiller au respect de la législation congolaise en matière de concurrence.',
      'icon' => 'far fa-chart-bar',
    ],
    [
      'title' => 'Contrôle',
      'desc' => 'Interventions sur les marchés et produits non conformes.',
      'icon' => 'far fa-bell',
    ],
    [
      'title' => 'Sensibilisation',
      'desc' => 'Information du public et des opérateurs économiques.',
      'icon' => 'far fa-lightbulb',
    ],
    [
      'title' => 'E-Services',
      'desc' => 'Plaintes, renseignements et signalements en ligne.',
      'icon' => 'fas fa-headset',
    ],
  ],
  'storyItems' => [
    [
      'title' => 'Mission institutionnelle',
      'text' => 'Promouvoir la libre concurrence et la transparence des marchés en République Démocratique du Congo.',
      'icon' => 'fas fa-users',
    ],
    [
      'title' => 'Accompagnement',
      'text' => 'Assister les consommateurs et les entreprises dans le respect du cadre juridique.',
      'icon' => 'fas fa-comments',
    ],
    [
      'title' => 'Action sur le terrain',
      'text' => 'Mener des opérations de contrôle en partenariat avec les institutions nationales.',
      'icon' => 'fas fa-bolt',
    ],
  ],
  'services' => [
    [
      'title' => 'Cadre juridique',
      'text' => 'La COMCO veille à l\'application de la loi n°18/020 relative à la liberté des prix et à la concurrence sur l\'ensemble du territoire national.',
      'image' => 'cat-3.jpg',
      'link' => ['section' => 'centre-information', 'slug' => 'cadre-juridique'],
    ],
    [
      'title' => 'Contrôle et sanctions',
      'text' => 'Des opérations de contrôle sont menées pour lutter contre les ententes, les abus de position dominante et les produits dangereux.',
      'image' => 'mr.jpeg',
      'link' => ['section' => 'e-services', 'slug' => 'signaler-pratique'],
      'reverse' => true,
    ],
    [
      'title' => 'E-Services',
      'text' => 'Déposez vos plaintes, demandes de renseignements et signalements via les services en ligne de la COMCO.',
      'image' => 'talo.jpg',
      'link' => ['section' => 'e-services', 'slug' => 'deposer-fusion'],
    ],
  ],
  'whyChoose' => [
    [
      'title' => 'Autorité de régulation',
      'text' => 'Institution publique chargée de garantir une concurrence loyale au profit de l\'économie congolaise.',
      'icon' => 'fas fa-comment-dots',
      'transform' => 'flip-h',
    ],
    [
      'title' => 'Expertise nationale',
      'text' => 'Une équipe d\'agents formés pour analyser les marchés, enquêter et sensibiliser les acteurs économiques.',
      'icon' => 'fas fa-palette',
      'transform' => 'shrink-1',
    ],
    [
      'title' => 'Disponibilité',
      'text' => 'Contactez la COMCO pour toute question relative à la concurrence, aux prix ou aux pratiques commerciales.',
      'icon' => 'fas fa-stopwatch',
      'transform' => 'grow-1',
    ],
  ],
  'features' => [
    ['title' => 'Communiqués', 'text' => 'Consultez les alertes et communiqués officiels de la COMCO.', 'icon' => 'sharing.png'],
    ['title' => 'Plaintes en ligne', 'text' => 'Signalez une pratique anticoncurrentielle via nos e-services.', 'icon' => 'mail.png'],
    ['title' => 'Veille des prix', 'text' => 'Bientôt : l\'application TALO pour la surveillance des marchés.', 'icon' => 'target.png'],
    ['title' => 'Documentation', 'text' => 'Accédez au cadre juridique et à la documentation institutionnelle.', 'icon' => 'world-globe.png'],
    ['title' => 'Médias', 'text' => 'Galerie photo et vidéothèque des activités de la COMCO.', 'icon' => 'money.png'],
    ['title' => 'Partenariats', 'text' => 'La COMCO collabore avec les institutions nationales et internationales.', 'icon' => 'data-analytics.png'],
  ],
  'funFacts' => [
    ['value' => 18, 'label' => 'Loi de référence'],
    ['value' => 4, 'label' => 'E-Services actifs'],
    ['value' => 26, 'label' => 'Provinces couvertes'],
    ['value' => 100, 'label' => 'Engagement public'],
  ],
  'featured' => [
    'title' => 'Cessez immédiatement toute consommation de ces produits',
    'text' => 'La boisson énergisante Power Plus dite Mutu Rouge est interdite de consommation et de vente.',
    'date' => '29 avril 2026',
    'image' => 'sc.jpeg',
  ],
  'activities' => [
    [
      'title' => 'Renforcement des capacités des agents',
      'text' => 'Session de formation des agents de la COMCO.',
    ],
    [
      'title' => 'Atelier de Renforcement Institutionnel',
      'text' => 'Concurrence en RDC — octobre 2021.',
    ],
  ],
  'latestVideo' => [
    'title' => 'Scellement de la société REVIN SARL',
    'text' => 'Opération de contrôle menée par la COMCO et l\'ACOREP — Kinshasa, 29 avril 2026.',
    'image' => 'videoframe_0.png',
    'youtube' => 'jlWMTNZNOc0',
  ],
  'testimonials' => [
    [
      'quote' => 'La COMCO a traité notre signalement avec rigueur et confidentialité. Nous avons retrouvé une concurrence plus équitable sur notre secteur.',
      'name' => 'Entrepreneur — Kinshasa',
      'role' => 'Commerce de détail',
      'image' => 'assets/img/client1.png',
    ],
    [
      'quote' => 'Les échanges avec la Commission nous ont permis de mieux comprendre le cadre juridique congolais en matière de concurrence et de prix.',
      'name' => 'Responsable juridique',
      'role' => 'Entreprise industrielle',
      'image' => 'assets/img/client2.png',
    ],
    [
      'quote' => 'Un partenaire institutionnel à l\'écoute des consommateurs et des acteurs économiques. La transparence des procédures est un atout majeur.',
      'name' => 'Association de consommateurs',
      'role' => 'Partenaire de la COMCO',
      'image' => 'assets/img/client3.png',
    ],
  ],
  'partners' => [
    ['logo' => 'assets/img/partner/logo2.png', 'name' => 'Partenaire institutionnel'],
    ['logo' => 'assets/img/partner/logo1.png', 'name' => 'Partenaire institutionnel'],
    ['logo' => 'assets/img/partner/logo6.png', 'name' => 'Partenaire institutionnel'],
    ['logo' => 'assets/img/partner/logo3.png', 'name' => 'Partenaire institutionnel'],
    ['logo' => 'assets/img/partner/logo5.png', 'name' => 'Partenaire institutionnel'],
    ['logo' => 'assets/img/partner/logo4.png', 'name' => 'Partenaire institutionnel'],
  ],
];
