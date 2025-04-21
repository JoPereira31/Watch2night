<?php
$titrePage = "Mentions légales - Watch2Night";
$descriptionPage = "Découvrez les mentions légales de Watch2Night : fonctionnement du site, données personnelles, responsabilités et modération.";
include_once RACINE . '/view/layout/head.php';
include_once RACINE . '/view/layout/header.php';
?>

<main id="mentionsLegales">
  <h1>Mentions Légales</h1>

  <div class="conteneurMentions">
    
    <div class="carteMentions">
      <h2>Présentation du site</h2>
      <p>Le site Watch2Night permet aux utilisateurs de rechercher des films, créer une watchlist personnalisée et partager leurs avis.</p>

      <h2>Accès au site</h2>
      <p>L'accès est libre et gratuit. Certaines fonctionnalités nécessitent une inscription (watchlist, commentaires).</p>

      <h2>Responsabilités</h2>
      <p>Les informations sont données à titre indicatif. Watch2Night ne peut être tenu responsable d'erreurs ou d'indisponibilités.</p>
    </div>

    <div class="carteMentions">
      <h2>Propriété intellectuelle</h2>
      <p>Tous les contenus du site sont protégés par les lois sur la propriété intellectuelle. Toute reproduction est interdite sans autorisation.</p>

      <h2>Données personnelles</h2>
      <p>Les données collectées sont uniquement utilisées pour les besoins du site. Chaque utilisateur peut demander la suppression de ses données.</p>

      <h2>Modération des contenus</h2>
      <p>L'administrateur se réserve le droit de supprimer tout commentaire ou compte diffusant des propos injurieux, racistes, sexistes, homophobes ou incitant à la haine.</p>
    </div>

  </div>

</main>

<?php
include_once RACINE . '/view/layout/footer.php';
?>