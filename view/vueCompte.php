<?php 
$titrePage = "Mon Compte - Watch2Night";
$descriptionPage = "Gérez votre compte, votre mot de passe et votre watchlist sur Watch2Night.";

// Inclusion du head (balises <head> : meta, CSS, JS)
include_once RACINE . '/view/layout/head.php'; 

// Inclusion du header (logo, barre de recherche, navigation)
include_once RACINE . '/view/layout/header.php'; 
?>

<main id="profilPage">

    <!-- Petit script pour avoir le token en JavaScript aussi -->
    <script>
        const csrfToken = '<?= $_SESSION['token'] ?>';
    </script>

    <!-- En-tête de la page de profil -->
    <div class="enteteProfil">
        <h1>Bonjour, <?= htmlspecialchars($pseudo) ?></h1> <!-- Affiche le pseudo de l'utilisateur connecté -->
    </div>

    <!-- Messages de succès ou d'erreur après une action -->
    <?php if (isset($_GET['infos']) && $_GET['infos'] === 'ok'): ?>
      <p class="successMessage">Vos informations ont bien été mises à jour.</p>
    <?php endif; ?>

    <?php if (isset($_GET['mdp']) && $_GET['mdp'] === 'ok'): ?>
        <p class="successMessage">Votre mot de passe a bien été changé.</p>
    <?php endif; ?>

    <?php if (isset($_GET['erreur'])): ?>
        <p class="errorMessage">
            <?php
            if ($_GET['erreur'] === 'mdp') echo 'Mot de passe actuel incorrect.';
            if ($_GET['erreur'] === 'validation') echo 'Les mots de passe ne correspondent pas ou sont trop courts.';
            ?>
        </p>
    <?php endif; ?>

    <!-- SECTION : Affichage de la watchlist -->
    <section class="watchlistSection">
        <h2>Ma Watchlist</h2>
        <div class="carouselWatchlist" id="listeWatchlist" data-watchlist='<?= json_encode($watchlist) ?>'>
        </div>
    </section>

    <!-- Formulaires pour modifier profil ou mot de passe -->
    <div class="formulairesProfil">

        <!-- SECTION : Modifier le pseudo ou l'email -->
        <section id="modifierInfos">
            <h2>Modifier mes informations</h2>
            <form method="POST" action="profil">
                <input type="hidden" name="maj_infos" value="1">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>"> <!-- Ajout du token -->
                <input type="text" name="nouveau_pseudo" placeholder="Nouveau pseudo">
                <input type="email" name="nouvel_email" placeholder="Nouvel email">
                <button type="submit">Enregistrer</button>
            </form>
        </section>

        <!-- SECTION : Changer de mot de passe -->
        <section id="modifierMdp">
            <h2>Changer mon mot de passe</h2>
            <form method="POST" action="profil">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>"> <!-- Ajout du token -->
                <input type="password" name="ancien_mdp" id="ancien_mdp" placeholder="Mot de passe actuel" required>
                <input type="password" name="nouveau_mdp" id="nouveau_mdp" placeholder="Nouveau mot de passe" required>
                <input type="password" name="confirmation_mdp" id="confirmation_mdp" placeholder="Confirmer le mot de passe" required>
                <button type="submit">Changer le mot de passe</button>
            </form>
        </section>

    </div>

    <!-- SECTION : Suppression du compte utilisateur -->
    <section id="supprimerCompte">
        <button type="button" class="btnSupprimerCompte" id="btnOpenModal">Supprimer mon compte</button>

        <div class="modalSuppression" id="modalSuppression">
            <div class="modalContent">
                <p>Êtes-vous sûr de vouloir supprimer votre compte ?<br>Cela est irréversible.</p>
                <form method="POST" action="profil">
                    <input type="hidden" name="supprimer_compte" value="1">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <button type="submit" class="btnSupprimerCompte confirm">Confirmer</button>
                    <button type="button" id="btnCloseModal">Annuler</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Bouton de déconnexion -->
    <a href="deconnexion" class="btnDeconnexion">Se déconnecter</a>

</main>

<?php 
// Inclusion du footer
include_once RACINE . '/view/layout/footer.php'; 
?>