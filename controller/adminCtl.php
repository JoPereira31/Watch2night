<?php
require_once RACINE . '/model/watchlistDb.php';
require_once RACINE . '/model/utilisateurDb.php';
require_once RACINE . '/model/commentaireDb.php';

// Démarrage de session (si pas encore démarrée)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Vérification que l'utilisateur est bien admin ---
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?url=connexion');
    exit;
}

// --- TRAITEMENT DES FORMULAIRES POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    if (empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        header('Location: index.php?url=erreur&code=403');
        exit;
    }

    // --- Suppression d'utilisateur ---
    if (isset($_POST['action']) && $_POST['action'] === 'supprimerUtilisateur' && !empty($_POST['id_utilisateur'])) {
        $idUtilisateur = (int)$_POST['id_utilisateur'];
        if (supprimerUtilisateur($idUtilisateur)) {
            header('Location: index.php?url=admin');
            exit;
        } else {
            header('Location: index.php?url=erreur&code=500');
            exit;
        }
    }

    // --- Suppression de commentaire ---
    if (isset($_POST['action']) && $_POST['action'] === 'supprimerCommentaire' && !empty($_POST['id_commentaire'])) {
        $idCommentaire = (int)$_POST['id_commentaire'];
        if (supprimerCommentaire($idCommentaire)) {
            header('Location: index.php?url=admin');
            exit;
        } else {
            header('Location: index.php?url=erreur&code=500');
            exit;
        }
    }
}

// --- AFFICHAGE DE LA PAGE ADMIN ---
$pseudo = $_SESSION['pseudo'];
$role = $_SESSION['role'];

$watchlist = recupererWatchlist($_SESSION['id']);

// --- Recherche utilisateurs ---
if (!empty($_GET['recherche_utilisateur'])) {
    $motCle = trim($_GET['recherche_utilisateur']);
    $utilisateurs = rechercherUtilisateurs($motCle);
} else {
    $utilisateurs = listerUtilisateurs();
}

// --- Derniers commentaires ---
$commentairesRecents = commentairesRecents();

// --- Chargement de la vue ---
require_once RACINE . '/view/vueAdmin.php';
?>
