<?php 
// Démarre une session PHP uniquement si aucune session n'est déjà active
if(session_status() === PHP_SESSION_NONE) session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Configuration de l'affichage responsive sur mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Définition de l'encodage de la page en UTF-8 -->
    <meta charset="UTF-8">

    <!-- Titre dynamique -->
    <title><?= isset($titrePage) ? htmlspecialchars($titrePage) : 'Watch2Night' ?></title>

    <!-- Description dynamique -->
    <meta name="description" content="<?= isset($descriptionPage) ? htmlspecialchars($descriptionPage) : 'Regardez vos films préférés sur Watch2Night !' ?>">

    <!-- FAVICON -->
    <link rel="icon" type="image/x-icon" href="public/assets/images/logo.ico">

    <!-- Lien vers le fichier CSS principal -->
    <link rel="stylesheet" href="public/assets/css/style.css">

    <!-- Importation de la librairie d'icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Chargement des scripts JavaScript (defer = chargement différé après le HTML) -->
    <script src="public/assets/js/film.js" defer></script>
    <script src="public/assets/js/watchlist.js" defer></script>
    <script src="public/assets/js/compte.js" defer></script>
    <script src="public/assets/js/header.js" defer></script>
    <script src="public/assets/js/authentification.js" defer></script>
    <script src="public/assets/js/admin.js" defer></script>
    <script src="public/assets/js/commentaire.js" defer></script>
</head>
