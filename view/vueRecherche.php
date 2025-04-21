<?php 
$titrePage = "Résultats de recherche - Watch2Night";
$descriptionPage = "Explorez les résultats de votre recherche parmi des milliers de films et d'acteurs sur Watch2Night.";

// Inclusion du head (balises <head> : meta, CSS, JS)
include_once RACINE . '/view/layout/head.php'; 

// Inclusion du header (logo, barre de recherche, navigation)
include_once RACINE . '/view/layout/header.php'; 
?>

<main>

    <!-- SECTION : Page des résultats de recherche -->
    <section id="pageRecherche">
        <h2>Résultats de votre recherche</h2>

        <!-- Conteneur où les résultats seront injectés dynamiquement via JavaScript -->
        <div id="resultatsRecherche" class="listeResultat"></div>
    </section>

</main>

<?php 
// Inclusion du footer
include_once RACINE .'/view/layout/footer.php'; 
?>
``
