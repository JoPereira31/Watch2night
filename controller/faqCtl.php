<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($nom) && !empty($email) && !empty($message)) {
        // Ici tu pourrais envoyer un mail, sauvegarder dans la BDD, etc.
        $_SESSION['success'] = "Votre message a bien été envoyé.";
    } else {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
    }

    header('Location: contact');
    exit;
}

require_once RACINE . '/view/vueFaq.php';
?>
