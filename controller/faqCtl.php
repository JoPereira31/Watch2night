<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($nom) && !empty($email) && !empty($message)) {
        $_SESSION['success'] = "Votre message a bien été envoyé.";
    } else {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
    }

    header('Location: faq');
    exit;
}

require_once RACINE . '/view/vueFaq.php';
?>
