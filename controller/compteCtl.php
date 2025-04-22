<?php
require_once RACINE . '/model/utilisateurDb.php';
require_once RACINE . '/model/watchlistDb.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
  header('Location: connexion');
  exit;
}


$idUtilisateur = $_SESSION['id'];
$pseudo = $_SESSION['pseudo'];

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Modifier les infos (pseudo/email)
  if (!empty($_POST['maj_infos'])) {
    $nouveauPseudo = trim($_POST['nouveau_pseudo'] ?? '');
    $nouvelEmail = trim($_POST['nouvel_email'] ?? '');

    if (majUtilisateur($idUtilisateur, $nouveauPseudo, $nouvelEmail)) {
      $_SESSION['pseudo'] = $nouveauPseudo ?: $_SESSION['pseudo'];
      header('Location: compte');
      exit;
    }
  }

  // Changer le mot de passe
  if (!empty($_POST['ancien_mdp']) && !empty($_POST['nouveau_mdp']) && !empty($_POST['confirmation_mdp'])) {
    $ancien = $_POST['ancien_mdp'];
    $nouveau = $_POST['nouveau_mdp'];
    $confirmation = $_POST['confirmation_mdp'];

    if ($nouveau !== $confirmation || strlen($nouveau) < 6) {
      header('Location: compte');
      exit;
    }

    if (changerMdp($idUtilisateur, $ancien, $nouveau)) {
      header('Location: compte');
      exit;
    } else {
      header('Location: compte');
      exit;
    }
  }

  // Supprimer compte
  if (isset($_POST['supprimer_compte'])) {
    supprimerCompte($idUtilisateur);
    session_destroy();
    header('Location: connexion');
    exit;
  }
}

// Watchlist
$watchlist = recupererWatchlist($idUtilisateur);

// Vue
require_once RACINE . '/view/vueCompte.php';
