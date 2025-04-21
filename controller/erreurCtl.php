<?php
function afficherErreur($code = 500, $message = "Une erreur est survenue.") {
    http_response_code($code);

    $titrePage = "Erreur $code - Watch2Night";
    $descriptionPage = "Une erreur est survenue sur le site Watch2Night.";

    // On passe aussi le message pour l'afficher dans la vue
    require_once RACINE . '/view/vueErreur.php';
    exit;
}
?>
