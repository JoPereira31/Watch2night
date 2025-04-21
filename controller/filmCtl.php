<?php
require_once RACINE . '/model/watchlistDb.php';
$idFilm = isset($_GET['id']) ? (int)$_GET['id'] : 0;


// --- Début du contrôleur filmCtl.php ---

$idFilm = (int)($_GET['id'] ?? 0);

if ($idFilm <= 0) {
    header('Location: accueil');
    exit;
}

// Appelle directement ta fonction PHP pour récupérer titre + synopsis
$filmInfos = getFilmDetails($idFilm);

if (!$filmInfos) {
    header('Location: accueil');
    exit;
}

// Prépare le titre et la description
$titrePage = htmlspecialchars($filmInfos['title']) . " - Watch2Night";
$descriptionPage = "Regardez " . htmlspecialchars($filmInfos['title']) . " : ";

// Vérifie si le film est dans la watchlist
$estDansWatchlist = false;
if (isset($_SESSION['id'])) {
    $estDansWatchlist = estDansWatchlist($idFilm, $_SESSION['id']);
}

// Charge la vue
require_once RACINE . '/view/vueFilm.php';


// --- Et ici tu colles ta fonction juste en bas du fichier :

function getFilmDetails($idFilm) {
    $apiKey = TMDB_API_KEY;

    $url = "https://api.themoviedb.org/3/movie/{$idFilm}?language=fr&api_key={$apiKey}";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return false;
    }

    $data = json_decode($response, true);

    if (isset($data['status_code'])) {
        return false;
    }

    return [
        'title' => $data['title'] ?? '',
        'overview' => $data['overview'] ?? ''
    ];
}

?>
