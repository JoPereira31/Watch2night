<header class="enteteSite">
  <div class="conteneurHeader">
    
    <!-- Logo du site cliquable qui redirige vers la page d'accueil -->
    <div class="conteneurLogo">
      <a href="accueil">
        <img src="public/assets/images/logo.webp" alt="Logo" class="logoSite">
      </a>
    </div>

    <!-- Barre de recherche pour rechercher un film ou un acteur -->
    <div class="barreRecherche">
      <form method="GET" action="recherche">
        <input type="text" name="query" placeholder="Rechercher un film, un acteur...">
        <button type="submit">OK</button>
      </form>
    </div>

    <!-- Icônes d'actions utilisateur (loupe, profil, admin, connexion) -->
    <div class="iconeActions">
      <i class="fa-solid fa-magnifying-glass btnOpenSearch"></i> <!-- Bouton pour ouvrir la barre de recherche sur mobile -->

      <?php if (isset($_SESSION['id'])): ?> <!-- Si l'utilisateur est connecté -->
        <?php if ($_SESSION['role'] === 'admin'): ?> <!-- Et si c'est un administrateur -->
          <a href="admin"><i class="fa-solid fa-shield"></i></a> <!-- Icône Admin -->
        <?php else: ?>
          <a href="compte"><i class="fa-solid fa-user"></i></a> <!-- Icône Profil classique -->
        <?php endif; ?>
      <?php else: ?>
        <a href="connexion"><i class="fa-solid fa-right-to-bracket"></i></a> <!-- Icône pour connexion si non connecté -->
      <?php endif; ?>
    </div>

    <!-- Menu texte pour l'utilisateur -->
    <nav class="zoneUtilisateur">
      <?php if (isset($_SESSION['id'])): ?> <!-- Si connecté -->
        <?php if ($_SESSION['role'] === 'admin'): ?> <!-- Admin -->
          <a href="admin">Admin</a>
        <?php else: ?> <!-- Utilisateur classique -->
          <a href="compte">Mon compte</a>
        <?php endif; ?>
      <?php else: ?> <!-- Si non connecté -->
        <a href="connexion">Connexion</a>
      <?php endif; ?>
    </nav>

  </div>
</header>