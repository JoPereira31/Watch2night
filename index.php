<?php
session_name("watch2night");
session_start();
define('RACINE', __DIR__);

require_once RACINE . '/config/config.php';
require_once RACINE . '/model/bdd.php';
require_once RACINE . '/config/router.php';

// On récupère uniquement le paramètre "url"
$action = $_GET['url'] ?? 'accueil';

charger_controleur($action);
?>