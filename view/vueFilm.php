<?php 
// Inclusion du head (balises <head> : meta, CSS, JS)
include_once RACINE . '/view/layout/head.php'; 

// Inclusion du header (logo, barre de recherche, navigation)
include_once RACINE . '/view/layout/header.php'; 
?>

<main id="detailsFilm" data-idutilisateur="<?= $_SESSION['id'] ?? '' ?>">
  <!-- La variable data-idutilisateur sera utilisée côté JS pour vérifier si l'utilisateur est connecté -->

  <!-- SECTION : Informations principales du film -->
  <section class="infosFilm">

    <!-- Affiche du film -->
    <div class="imageFilm">
      <img src="" alt="">
    </div>

    <!-- Informations principales : titre, réalisateur, synopsis -->
    <div class="infosGlobales">

      <div class="titreEtWatchlist">
        <h1></h1> <!-- Titre du film -->

        <?php if(isset($_SESSION['id'])) :?>
          <!-- Bouton d'ajout/retrait du film dans la watchlist -->
          <i class="fa-solid fa-bookmark btnWatchlist <?= $estDansWatchlist ? 'active' : '' ?>" data-idfilm="<?= $idFilm ?>"></i>
        <?php endif;?>
      </div>

      <p class="realisateur"></p> <!-- Réalisateur -->
      <p class="synopsis"></p> <!-- Résumé / synopsis -->

    </div>

  </section>

  <!-- SECTION : Bande-annonce -->
  <section class="bandeAnnonce">
    <h2>Bande-annonce</h2>
    <div class="bandeAnnonceDesktop">
      <iframe src="" allowfullscreen></iframe> <!-- La bande-annonce sera injectée en JS -->
    </div>
  </section>

  <!-- SECTION : Acteurs principaux -->
  <section class="acteursSection">
    <h2>Acteurs principaux</h2>
    <div class="listeActeurs"></div> <!-- Liste des acteurs affichée dynamiquement -->
  </section>

  <!-- SECTION : Commentaires -->
  <section id="commentaires">
    <h2>Commentaires</h2>

    <!-- Variable JS pour savoir si l'utilisateur est connecté -->
    <?php if (isset($_SESSION['id'])): ?>
      <script>
        const idUtilisateurConnecte = <?= (int)$_SESSION['id'] ?>;
      </script>
    <?php else: ?>
      <script>
        const idUtilisateurConnecte = null;
      </script>
    <?php endif; ?>

    <!-- Liste des commentaires (chargée dynamiquement) -->
    <div id="listeCommentaires"></div>

    <!-- Formulaire pour ajouter un commentaire -->
    <?php if (isset($_SESSION['id'])): ?>
      <form id="formCommentaire">
        <textarea id="contenuCommentaire" name="contenu" placeholder="Votre commentaire..." required></textarea>
        <button type="submit">Envoyer</button>
      </form>
    <?php else: ?>
      <p>Connectez-vous pour laisser un commentaire.</p>
    <?php endif; ?>
  </section>

  <!-- MODALE : Suppression de commentaire -->
  <div id="modalSuppressionCommentaire" class="modal hidden">
    <div class="modalContent">
      <p>Êtes-vous sûr de vouloir supprimer ce commentaire ?</p>
      <div class="btnsModal">
        <button id="confirmerSuppression" class="confirm">Confirmer</button>
        <button id="annulerSuppression">Annuler</button>
      </div>
    </div>
  </div>

  <!-- MODALE : Modification d'un commentaire -->
  <div id="modalModificationCommentaire" class="modal hidden">
    <div class="modalContent">
      <h3>Modifier votre commentaire</h3>
      <textarea id="nouveauContenu"></textarea>
      <div class="btnsModal">
        <button id="confirmerModification" class="confirm">Modifier</button>
        <button id="annulerModification">Annuler</button>
      </div>
    </div>
  </div>

</main>

<?php 
// Inclusion du footer
include_once RACINE . '/view/layout/footer.php'; 
?>
