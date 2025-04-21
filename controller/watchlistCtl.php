<?php
require_once RACINE . '/model/watchlistDb.php';

header('Content-Type: application/json');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

// Récupération des données POST
$idFilm = (int)($_POST['id_film'] ?? 0);
$titreFilm = trim($_POST['titre'] ?? '');
$afficheFilm = trim($_POST['affiche'] ?? '');
$idUtilisateur = $_SESSION['id'];

// Vérification des données essentielles
if ($idFilm === 0 || empty($titreFilm)) {
    echo json_encode(['error' => 'Données incomplètes']);
    exit;
}

// Construction de l'objet film
$film = [
    'id' => $idFilm,
    'title' => $titreFilm,
];

// Traitement de la watchlist (ajout ou retrait)
$status = toggleWatchlist($film, $idUtilisateur);

// Réponse JSON pour JS
echo json_encode([
    'status' => $status,
    'film' => $film
]);
exit;
