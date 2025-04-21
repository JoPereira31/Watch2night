<?php
require_once RACINE . '/config/config.php';
require_once RACINE . '/model/commentaireDb.php';

header('Content-Type: application/json');

// 🧹 Nettoyage : GET → Récupérer les commentaires
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_film'])) {
    $idFilm = (int)$_GET['id_film'];
    
    $commentaires = commentaires($idFilm); // <- function correcte dans ton commentaireDb.php
    echo json_encode($commentaires);
    exit;
}

// 🧹 POST → Ajouter un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenu']) && !empty($_POST['id_film'])) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['error' => 'Utilisateur non connecté']);
        exit;
    }

    $idFilm = (int)$_POST['id_film'];
    $contenu = trim($_POST['contenu']);

    if ($idFilm && $contenu) {
        ajouterCommentaire($idFilm, $_SESSION['id'], $contenu);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Champs invalides']);
    }
    exit;
}

// 🧹 POST → Supprimer un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'supprimer_commentaire') {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['error' => 'Non connecté']);
        exit;
    }

    $idCommentaire = (int)($_POST['id_commentaire'] ?? 0);

    if ($idCommentaire && supprimerCommentaireParUtilisateur($idCommentaire, $_SESSION['id'])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Impossible de supprimer ce commentaire']);
    }
    exit;
}
//  Modifier un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifier_commentaire') {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['error' => 'Non connecté']);
        exit;
    }

    $idCommentaire = (int)($_POST['id_commentaire'] ?? 0);
    $nouveauContenu = trim($_POST['contenu'] ?? '');

    if ($idCommentaire && $nouveauContenu !== '') {
        if (modifierCommentaireParUtilisateur($idCommentaire, $_SESSION['id'], $nouveauContenu)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Impossible de modifier ce commentaire']);
        }
    } else {
        echo json_encode(['error' => 'Champs invalides']);
    }
    exit;
}


// ❌ Sinon : Mauvaise requête
http_response_code(400);
echo json_encode(['error' => 'Requête invalide']);
