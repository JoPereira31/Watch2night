<?php

function charger_controleur($action) {
    try {
        // Traitement spécial pour les actions envoyées en POST (ex: suppression utilisateur/commentaire)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'supprimerUtilisateur':
                        if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
                            require_once RACINE . '/model/utilisateurDb.php';
                            supprimerUtilisateur((int)$_POST['id_utilisateur']);
                            header('Location: index.php?url=admin');
                            exit;
                        } else {
                            header('Location: index.php?url=erreur&code=403');
                            exit;
                        }

                    case 'supprimerCommentaire':
                        if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
                            require_once RACINE . '/model/commentaireDb.php';
                            supprimerCommentaire((int)$_POST['id_commentaire']);
                            header('Location: index.php?url=admin');
                            exit;
                        } else {
                            header('Location: index.php?url=erreur&code=403');
                            exit;
                        }
                }
            }
        }

        // Si ce n'est pas un POST spécifique, traitement classique
        switch ($action) {
            case '':
            case 'accueil':
                require_once RACINE . '/controller/accueilCtl.php';
                break;

            case 'connexion':
            case 'inscription':
                require_once RACINE . '/controller/authentificationCtl.php';
                break;

            case 'deconnexion':
                require_once RACINE . '/controller/deconnexionCtl.php';
                break;

            case 'compte':
                if (!isset($_SESSION['id'])) {
                    header("Location: index.php?url=connexion");
                    exit;
                }
                require_once RACINE . '/controller/compteCtl.php';
                break;

            case 'recherche':
                require_once RACINE . '/controller/rechercheCtl.php';
                break;

            case 'film':
                require_once RACINE . '/controller/filmCtl.php';
                break;

            case 'admin':
                if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
                    header("Location: index.php?url=erreur&code=403");
                    exit;
                }
                require_once RACINE . '/controller/adminCtl.php';
                break;

            case 'watchlist':
                require_once RACINE . '/controller/watchlistCtl.php';
                break;

            case 'commentaire':
                require_once RACINE . '/controller/commentaireCtl.php';
                break;

            case 'faq':
                require_once RACINE . '/controller/faqCtl.php';
                break;

            case 'mentions':
                require_once RACINE . '/controller/mentionsCtl.php';
                break;

            case 'contact':
                require_once RACINE . '/controller/contactCtl.php';
                break;

            case 'erreur':
                require_once RACINE . '/controller/erreurCtl.php';
                $codeErreur = isset($_GET['code']) ? intval($_GET['code']) : 500;
                afficherErreur($codeErreur);
                break;

            default:
                require_once RACINE . '/controller/erreurCtl.php';
                afficherErreur(404);
                break;
        }
    } catch (Throwable $e) {
        // Gestion d'une erreur PHP grave
        require_once RACINE . '/controller/erreurCtl.php';
        afficherErreur(500);
    }
}

?>
