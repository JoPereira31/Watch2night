<?php
session_start();

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

define('RACINE', __DIR__);

require_once RACINE . '/config/config.php';
require_once RACINE . '/model/bdd.php';
require_once RACINE . '/config/router.php';

// On récupère uniquement le paramètre "url"
$action = $_GET['url'] ?? 'accueil';

charger_controleur($action);
?>
