<?php 
$titrePage = "Accueil - Watch2Night";
$descriptionPage = "Découvrez les films les plus populaires du moment sur Watch2Night.";
// Inclusion du fichier head.php (balises <head> : meta, title, liens CSS/JS)
include_once RACINE . '/view/layout/head.php'; 

// Inclusion du fichier header.php (barre de navigation du site)
include_once RACINE . '/view/layout/header.php'; 
?>

<main id="accueil">
    <!-- Section principale pour afficher le film vedette -->
    <section id="filmVedette">
        <div class="imageFilm"></div> <!-- Image d'affiche du film -->
        <div class="infosGlobales">
            <h1></h1> <!-- Titre du film -->
            <p class="realisateur"></p> <!-- Nom du réalisateur et année -->
            <p class="listeActeurs"></p> <!-- Liste des acteurs principaux -->
            <p class="synopsis"></p> <!-- Résumé du film -->
            <a class="voirDetails" href="film">Voir détails</a> <!-- Lien pour voir la fiche du film -->
        </div>
    </section>

    <!-- Conteneur qui va accueillir dynamiquement les films par genre -->
    <div id="genresContainer">
    </div>
</main>

<?php 
// Inclusion du fichier footer.php (pied de page du site)
include_once RACINE . '/view/layout/footer.php'; 
?>
