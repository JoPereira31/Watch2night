<?php

function charger_controleur($action) {
        switch ($action) {
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
                    header("Location:=connexion");
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
                    header("Location: erreur&code=403");
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
}
?>