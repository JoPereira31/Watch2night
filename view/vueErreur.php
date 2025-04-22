<?php
$titrePage = "Erreur - Watch2Night";
$descriptionPage = "Une erreur est survenue.";

include_once RACINE . '/view/layout/head.php'; 
include_once RACINE . '/view/layout/header.php'; 
// On récupère le message d'erreur en session, sinon message générique
$messageErreur = $_SESSION['erreur_message'] ?? "Une erreur est survenue.";
?>
<main id="pageErreur">
    <section class="conteneurErreur">
        <h1>Oups...</h1>
        <p><?= htmlspecialchars($messageErreur) ?></p>
        <a href="accueil" class="btnRetourAccueil">Retour à l'accueil</a>
    </section>
</main>
<?php 
include_once RACINE . '/view/layout/footer.php'; 
?>