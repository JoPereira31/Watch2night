<?php
require_once RACINE . '/config/config.php';

/*
* connexionPDO : établit une connexion sécurisée à la base de données avec PDO.
* paramètre : aucun (utilise les constantes de config)
* retourne : objet PDO si succès, ou redirige vers page d'erreur 500 en cas d'échec
*/
function connexionPDO() {
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOTE . ';dbname=' . DB_NOM . ';charset=utf8',
            DB_UTILISATEUR,
            DB_MDP
        );

        // Active les exceptions PDO pour capturer les erreurs facilement
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        // Redirige automatiquement vers la page erreur si la connexion échoue
        redirigerErreur("Erreur de connexion à la base de données : " . $e->getMessage(), 500);
    }
}
?>
