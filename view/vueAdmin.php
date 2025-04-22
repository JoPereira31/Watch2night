<?php 
$titrePage = "Administration - Watch2Night";
$descriptionPage = "Gérez les utilisateurs, les commentaires et votre watchlist dans votre espace administrateur Watch2Night.";

include_once RACINE . '/view/layout/head.php'; 
include_once RACINE . '/view/layout/header.php'; 
?>

<main id="adminPage">

  <div class="enteteProfil">
    <h1>Mon espace administrateur</h1>
    <p><strong>Bonjour,</strong> <?= htmlspecialchars($pseudo) ?></p>
  </div>

  <!-- WATCHLIST -->
  <section class="watchlistSection">
    <h2>Ma Watchlist</h2>
    <div id="listeWatchlist" class="carouselWatchlist" data-watchlist='<?= json_encode($watchlist ?? []) ?>'></div>
  </section>

  <!-- RECHERCHE UTILISATEURS -->
  <section id="rechercheUtilisateurs">
    <h2>Recherche d'utilisateurs</h2>
    <form method="GET" action="index.php" class="formRechercheUtilisateur">
      <input type="hidden" name="action" value="admin">
      <div class="zoneRecherche">
        <input type="text" name="recherche_utilisateur" placeholder="Rechercher par pseudo ou email">
        <button type="submit">Rechercher</button>
      </div>
    </form>

    <?php if (!empty($utilisateurs)): ?>
      <table class="tableUtilisateurs">
        <thead>
          <tr>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($utilisateurs as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['pseudo']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= htmlspecialchars($user['role']) ?></td>
              <td>
                <form method="POST" action="admin" class="formSuppressionUtilisateur" id="formSuppUser<?= $user['id_utilisateur'] ?>">
                  <input type="hidden" name="action" value="supprimerUtilisateur">
                  <input type="hidden" name="id_utilisateur" value="<?= $user['id_utilisateur'] ?>">
                  <button type="button" class="btnSupprimerUtilisateur" data-id="<?= $user['id_utilisateur'] ?>">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>
  </section>

  <!-- COMMENTAIRES RECENTS -->
  <section id="commentairesRecents">
    <h2>Derniers commentaires</h2>

    <?php if (!empty($commentairesRecents)): ?>
      <ul>
        <?php foreach ($commentairesRecents as $commentaire): ?>
          <li>
            <div>
              <strong><?= htmlspecialchars($commentaire['pseudo']) ?></strong> sur 
              <em><?= htmlspecialchars($commentaire['titre']) ?></em> : 
              "<?= htmlspecialchars($commentaire['contenu']) ?>"
            </div>

            <form method="POST" action="admin" class="formSuppressionCommentaire" id="formSuppCom<?= $commentaire['id_com'] ?>">
              <input type="hidden" name="action" value="supprimerCommentaire">
              <input type="hidden" name="id_commentaire" value="<?= $commentaire['id_com'] ?>">
              <button type="button" class="btnSupprimerCommentaire" data-id="<?= $commentaire['id_com'] ?>">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Aucun commentaire récent.</p>
    <?php endif; ?>
  </section>

  <!-- MODALES -->
  <div id="modalSuppUser" class="modalSuppUser hidden">
    <div class="modalContent">
      <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
      <div class="btnsModal">
        <button id="confirmSuppUser" class="confirm">Confirmer</button>
        <button id="btnCloseModalUser">Annuler</button>
      </div>
    </div>
  </div>

  <div id="modalSuppCom" class="modalSuppUser hidden">
    <div class="modalContent">
      <p>Êtes-vous sûr de vouloir supprimer ce commentaire ?</p>
      <div class="btnsModal">
        <button id="confirmSuppCom" class="confirm">Confirmer</button>
        <button id="btnCloseModalCom">Annuler</button>
      </div>
    </div>
  </div>

  <a href="deconnexion" class="btnDeconnexion">Se déconnecter</a>

</main>

<?php 
include_once RACINE . '/view/layout/footer.php'; 
?>