<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder de 15 articles d'actualité COMCO (contenu provisoire + images Elixir).
 */
class PostsSeeder extends Seeder
{
  /**
   * Exécute le seeding des articles publiés.
   */
  public function run(): void
  {
    $articles = [
      ['title' => 'La COMCO sensibilise les opérateurs économiques à Kinshasa', 'category' => 'Sensibilisation', 'author' => 'Cellule communication COMCO', 'image' => 'assets/img/9.jpg', 'excerpt' => 'Une session de sensibilisation sur la loi n° 18-020 a réuni les acteurs du secteur privé à Kinshasa-Gombe.', 'body' => '<p class="dropcap">La Commission de la Concurrence a organisé une session de sensibilisation destinée aux opérateurs économiques sur les règles de la libre concurrence et la transparence des prix. Cette activité s\'inscrit dans le mandat de prévention des pratiques anticoncurrentielles.</p><p>Les participants ont été informés des obligations légales en matière de notification des concentrations et des voies de recours ouvertes aux consommateurs.</p>'],
      ['title' => 'Opération de contrôle sur les marchés de Kinshasa', 'category' => 'Contrôle', 'author' => 'Direction des enquêtes', 'image' => 'assets/img/10.jpg', 'excerpt' => 'Une opération conjointe de contrôle a permis d\'inspecter plusieurs points de vente suspectés de pratiques de fixation des prix.', 'body' => '<p class="dropcap">Dans le cadre de sa mission de surveillance des marchés, la COMCO a mené une opération de contrôle en partenariat avec les services compétents. L\'objectif était de vérifier le respect de la réglementation sur la liberté des prix.</p><p>Des constats ont été établis et les procédures appropriées seront engagées conformément à la loi.</p>'],
      ['title' => 'Alerte consommateurs : produit non conforme retiré du marché', 'category' => 'Protection du consommateur', 'author' => 'COMCO', 'image' => 'assets/img/11.jpg', 'excerpt' => 'La COMCO publie une alerte relative à un produit identifié comme dangereux pour la santé publique.', 'body' => '<p class="dropcap">Suite à des signalements reçus via les e-services, la COMCO alerte les consommateurs sur un produit non conforme circulant sur certains marchés. Les autorités compétentes ont été saisies pour les mesures conservatoires.</p><p>Le public est invité à signaler toute vente suspecte via le portail COMCO.</p>'],
      ['title' => 'Atelier sur le contrôle des concentrations économiques', 'category' => 'Formation', 'author' => 'Collège des analystes', 'image' => 'assets/img/12.jpg', 'excerpt' => 'Les agents COMCO renforcent leurs capacités d\'analyse des opérations de fusion et d\'acquisition.', 'body' => '<p class="dropcap">Un atelier technique a réuni les analystes de la COMCO autour des méthodes d\'évaluation des concentrations économiques. Les cas pratiques portent sur les secteurs stratégiques de l\'économie congolaise.</p>'],
      ['title' => 'Partenariat COMCO – Ministère de l\'Économie nationale', 'category' => 'Institutionnel', 'author' => 'Coordination nationale', 'image' => 'assets/img/13.jpg', 'excerpt' => 'Renforcement de la coopération institutionnelle pour une meilleure gouvernance économique.', 'body' => '<p class="dropcap">La COMCO et le Ministère de l\'Économie nationale ont réaffirmé leur engagement commun pour la promotion d\'une concurrence loyale et la protection des consommateurs sur l\'ensemble du territoire.</p>'],
      ['title' => 'Lancement de la campagne « Prix transparents »', 'category' => 'Campagne', 'author' => 'COMCO', 'image' => 'assets/img/news-1.jpg', 'excerpt' => 'Une campagne nationale vise à informer les consommateurs sur leurs droits face aux hausses illicites des prix.', 'body' => '<p class="dropcap">La campagne « Prix transparents » sera déployée dans les provinces via les représentations de la COMCO. Des supports de communication et des sessions de proximité sont prévus.</p>'],
      ['title' => 'Notification d\'une opération de fusion dans le secteur bancaire', 'category' => 'Concentrations', 'author' => 'Service des concentrations', 'image' => 'assets/img/portfolio-1.jpg', 'excerpt' => 'Une opération de concentration a été notifiée à la COMCO pour analyse préalable.', 'body' => '<p class="dropcap">Conformément à la loi n° 18-020, une opération de fusion a été déposée via les e-services COMCO. L\'analyse de compatibilité avec la concurrence est en cours.</p>'],
      ['title' => 'Journée de la concurrence en République Démocratique du Congo', 'category' => 'Événement', 'author' => 'COMCO', 'image' => 'assets/img/portfolio-2.jpg', 'excerpt' => 'La COMCO commémore la journée nationale de la concurrence par des activités de plaidoyer.', 'body' => '<p class="dropcap">À l\'occasion de la journée de la concurrence, la COMCO rappelle l\'importance de marchés ouverts, transparents et équitables pour le développement économique national.</p>'],
      ['title' => 'Publication du rapport d\'activités semestriel', 'category' => 'Publication', 'author' => 'COMCO', 'image' => 'assets/img/portfolio-3.jpg', 'excerpt' => 'Bilan des enquêtes, sensibilisations et décisions rendues au premier semestre.', 'body' => '<p class="dropcap">Le rapport d\'activités présente les principales opérations menées par la COMCO : enquêtes, contrôles, formations et coopération internationale.</p>'],
      ['title' => 'Formation des représentations provinciales', 'category' => 'Formation', 'author' => 'Direction générale', 'image' => 'assets/img/portfolio-4.jpg', 'excerpt' => 'Renforcement des capacités des antennes provinciales pour une couverture effective du territoire.', 'body' => '<p class="dropcap">Les représentants provinciaux participent à une formation sur les procédures d\'enquête, de réception des plaintes et de collaboration interinstitutionnelle.</p>'],
      ['title' => 'Signalement d\'entente sur les prix du ciment', 'category' => 'Enquête', 'author' => 'Corps des enquêteurs', 'image' => 'assets/img/portfolio-5.jpg', 'excerpt' => 'La COMCO ouvre une enquête suite à des signalements d\'entente entre distributeurs.', 'body' => '<p class="dropcap">Des signalements confidentiels ont conduit à l\'ouverture d\'une enquête sur d\'éventuelles ententes restrictives de concurrence dans le secteur du ciment.</p>'],
      ['title' => 'Application TALO : veille des prix en cours de déploiement', 'category' => 'Innovation', 'author' => 'COMCO', 'image' => 'assets/img/portfolio-6.jpg', 'excerpt' => 'L\'outil TALO permettra aux consommateurs de comparer les prix sur les marchés.', 'body' => '<p class="dropcap">La COMCO annonce le déploiement progressif de l\'application TALO pour renforcer la transparence des prix et faciliter le signalement des anomalies.</p>'],
      ['title' => 'Communiqué : interdiction de commercialisation d\'une boisson énergisante', 'category' => 'Alerte', 'author' => 'COMCO', 'image' => 'assets/img/6.jpg', 'excerpt' => 'Mesure conservatoire prise à l\'encontre d\'un produit dangereux pour la santé.', 'body' => '<p class="dropcap">La COMCO rappelle l\'interdiction de consommation et de vente d\'une boisson énergisante non conforme. Les commerçants sont invités à retirer immédiatement le produit de leurs stocks.</p>'],
      ['title' => 'Rencontre avec les associations de consommateurs', 'category' => 'Consultation', 'author' => 'COMCO', 'image' => 'assets/img/7.jpg', 'excerpt' => 'Échanges sur les mécanismes de plainte et de protection des droits des consommateurs.', 'body' => '<p class="dropcap">La COMCO a reçu les associations de consommateurs pour discuter de l\'amélioration des voies de recours et de la sensibilisation du public.</p>'],
      ['title' => 'Ouverture du forum de discussion COMCO', 'category' => 'Digital', 'author' => 'COMCO', 'image' => 'assets/img/8.jpg', 'excerpt' => 'Le forum en ligne permet aux citoyens et opérateurs d\'échanger sous modération institutionnelle.', 'body' => '<p class="dropcap">La COMCO met à disposition un espace de discussion modéré pour favoriser le dialogue sur la concurrence, les prix et la protection du consommateur.</p>'],
    ];

    foreach ($articles as $index => $article) {
      $slug = Str::slug($article['title']);

      Post::query()->updateOrCreate(
        ['slug' => $slug],
        [
          'title' => $article['title'],
          'category' => $article['category'],
          'author' => $article['author'],
          'excerpt' => $article['excerpt'],
          'body' => $article['body'],
          'featured_image' => $article['image'],
          'meta_title' => $article['title'] . ' | COMCO',
          'meta_description' => $article['excerpt'],
          'is_published' => true,
          'published_at' => now()->subDays(15 - $index),
        ]
      );
    }
  }
}
