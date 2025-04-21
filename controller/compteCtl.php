<?php
require_once RACINE . '/model/utilisateurDb.php';
require_once RACINE . '/model/watchlistDb.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
  header('Location: connexion');
  exit;
}

// Génère un token CSRF si pas déjà présent
if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$idUtilisateur = $_SESSION['id'];
$pseudo = $_SESSION['pseudo'];

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Vérification token CSRF
  if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    die('Erreur de sécurité CSRF');
  }

  // Modifier les infos (pseudo/email)
  if (!empty($_POST['maj_infos'])) {
    $nouveauPseudo = trim($_POST['nouveau_pseudo'] ?? '');
    $nouvelEmail = trim($_POST['nouvel_email'] ?? '');

    if (majUtilisateur($idUtilisateur, $nouveauPseudo, $nouvelEmail)) {
      $_SESSION['pseudo'] = $nouveauPseudo ?: $_SESSION['pseudo'];
      header('Location: profil?infos=ok');
      exit;
    }
  }

  // Changer le mot de passe
  if (!empty($_POST['ancien_mdp']) && !empty($_POST['nouveau_mdp']) && !empty($_POST['confirmation_mdp'])) {
    $ancien = $_POST['ancien_mdp'];
    $nouveau = $_POST['nouveau_mdp'];
    $confirmation = $_POST['confirmation_mdp'];

    if ($nouveau !== $confirmation || strlen($nouveau) < 6) {
      header('Location: profil?erreur=validation');
      exit;
    }

    if (changerMdp($idUtilisateur, $ancien, $nouveau)) {
      header('Location: profil?mdp=ok');
      exit;
    } else {
      header('Location: profil?erreur=mdp');
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
