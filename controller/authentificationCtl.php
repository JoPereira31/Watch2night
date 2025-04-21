<?php
require_once RACINE . '/model/utilisateurDb.php';

$erreur = '';


$actionForm = $_POST['action'] ?? $_GET['action'] ?? 'connexion';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if ($actionForm === 'connexion') {

        $pseudo = htmlspecialchars(trim($_POST['pseudo']));
        $mdp = $_POST['mdp'];

        if (empty($pseudo) || empty($mdp)) {
            $erreur = "Tous les champs sont obligatoires.";
        } else {
            $utilisateur = verifieConnexion($pseudo, $mdp);

            if ($utilisateur) {
                $_SESSION['id'] = $utilisateur['id_utilisateur'];
                $_SESSION['pseudo'] = $utilisateur['pseudo'];
                $_SESSION['role'] = $utilisateur['role'];

                header('Location: ' . ($utilisateur['role'] === 'admin' ? 'admin' : 'accueil'));
                exit;
            } else {
                $erreur = "Identifiants incorrects.";
            }
        }

    } elseif ($actionForm === 'inscription') {

        $pseudo = trim($_POST['pseudo']);
        $email = trim($_POST['email']);
        $mdp = $_POST['mot_de_passe'];
        $confirmation = $_POST['confirmation'];

        if (empty($pseudo) || empty($email) || empty($mdp) || empty($confirmation)) {
            $erreur = "Tous les champs sont obligatoires.";
        } elseif (strlen($mdp) < 6) {
            $erreur = "Le mot de passe doit contenir au moins 6 caractères.";
        } elseif ($mdp !== $confirmation) {
            $erreur = "Les mots de passe ne correspondent pas.";
        } elseif (existeUtilisateur($pseudo, $email)) {
            $erreur = "Le pseudo ou l'email est déjà utilisé.";
        } else {
            if (ajoutUtilisateur($pseudo, $email, $mdp)) {
                $_SESSION['message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                header('Location: connexion');
                exit;
            } else {
                $erreur = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}

require_once RACINE . '/view/vueAuthentification.php';