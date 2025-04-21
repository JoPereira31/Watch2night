<?php 
$titrePage = "Connexion/Inscription - Watch2Night";
$descriptionPage = "Connectez-vous à votre compte Watch2Night ou créez un nouveau compte pour profiter pleinement de notre plateforme.";

// Inclusion du head (balises <head> : meta, CSS, JS)
include_once RACINE . '/view/layout/head.php'; 
?>

<main id="authentificationPage">

  <!-- Carte d'authentification avec animation flip (connexion / inscription) -->
  <div class="carteAuthentification <?php if ($actionForm === 'inscription') echo 'active'; ?>">
    <div class="carte">

      <!-- Face avant : Connexion -->
      <div class="face face-front">
        <h1>Connexion</h1>

        <!-- Message d'erreur affiché si problème lors de la connexion -->
        <?php if (!empty($erreur) && $actionForm === 'connexion'): ?>
          <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form action="connexion" method="post">
          <input type="text" name="pseudo" placeholder="Votre pseudo" required>
          <input type="password" name="mdp" placeholder="Votre mot de passe" required>
          <button type="submit">Se connecter</button>
        </form>

        <!-- Lien pour basculer vers l'inscription -->
        <p class="lienAuthentification">
          Pas de compte ? <span class="btnFlip">S'inscrire</span>
        </p>
      </div>

      <!-- Face arrière : Inscription -->
      <div class="face face-back">
        <h1>Inscription</h1>

        <!-- Emplacement d'une erreur de mot de passe -->
        <p id="erreurMdp" class="erreur"></p>

        <!-- Message d'erreur affiché si problème lors de l'inscription -->
        <?php if (!empty($erreur) && $actionForm === 'inscription'): ?>
          <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form action="connexion" method="post" id="formInscription">
          <input type="hidden" name="action" value="inscription"> <!-- Spécifie que c'est une inscription -->
          <input type="text" name="pseudo" placeholder="Votre pseudo" required>
          <input type="email" name="email" placeholder="Votre email" required>
          <input type="password" name="mot_de_passe" id="mot_de_passe" placeholder="Votre mot de passe" required>
          <input type="password" name="confirmation" id="confirmation" placeholder="Confirmez le mot de passe" required>
          <button type="submit">S'inscrire</button>
        </form>

        <!-- Lien pour basculer vers la connexion -->
        <p class="lienAuthentification">
          Déjà un compte ? <span class="btnFlip">Se connecter</span>
        </p>
      </div>

    </div>
  </div>

</main>
</body>
