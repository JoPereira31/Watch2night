<?php
require_once RACINE . '/model/watchlistDb.php';
require_once RACINE . '/model/utilisateurDb.php';
require_once RACINE . '/model/commentaireDb.php';

// --- Vérification que l'utilisateur est bien admin ---
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: connexion');
    exit;
}
    // --- Suppression d'utilisateur ---
    if (isset($_POST['action']) && $_POST['action'] === 'supprimerUtilisateur' && !empty($_POST['id_utilisateur'])) {
        $idUtilisateur = (int)$_POST['id_utilisateur'];
        if (supprimerUtilisateur($idUtilisateur)) {
            header('Location: admin');
            exit;
        } else {
            header('Location: erreur&code=500');
            exit;
        }
    }

    // --- Suppression de commentaire ---
    if (isset($_POST['action']) && $_POST['action'] === 'supprimerCommentaire' && !empty($_POST['id_commentaire'])) {
        $idCommentaire = (int)$_POST['id_commentaire'];
        if (supprimerCommentaire($idCommentaire)) {
            header('Location: admin');
            exit;
        } else {
            header('Location: erreur&code=500');
            exit;
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