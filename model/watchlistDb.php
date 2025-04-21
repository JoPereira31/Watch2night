<?php
require_once RACINE . '/model/bdd.php';

/*
* estDansWatchlist : Vérifie si un film est dans la watchlist d'un utilisateur.
* paramètre : idFilm (int), idUtilisateur (int)
* retourne : true si présent, false sinon
*/
function estDansWatchlist($idFilm, $idUtilisateur) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT COUNT(*) FROM voeu WHERE id_film = :idFilm AND id_utilisateur = :idUtilisateur";
        $req = $bdd->prepare($sql);
        $req->execute([
            'idFilm' => $idFilm,
            'idUtilisateur' => $idUtilisateur
        ]);
        return $req->fetchColumn() > 0;
    } catch (PDOException $e) {
        $_SESSION['erreur_message'] = "Erreur base de données : " . $e->getMessage();
        $_SESSION['code_erreur'] = 500;
        header('Location: erreur');
        exit;
    }
}

/*
* toggleWatchlist : Ajoute ou retire un film dans la watchlist selon son état actuel.
* paramètre : film (array), idUtilisateur (int)
* retourne : 'ajoute' ou 'retire'
*/
function toggleWatchlist($film, $idUtilisateur) {
    try {
        if (estDansWatchlist($film['id'], $idUtilisateur)) {
            retirerFilmWatchlist($film['id'], $idUtilisateur);
            return 'retire';
        } else {
            ajouterFilmEtWatchlist($film, $idUtilisateur);
            return 'ajoute';
        }
    } catch (Exception $e) {
        $_SESSION['erreur_message'] = "Erreur manipulation watchlist : " . $e->getMessage();
        $_SESSION['code_erreur'] = 500;
        header('Location: erreur');
        exit;
    }
}

/*
* ajouterFilmEtWatchlist : Insère un film en base si absent et l'ajoute à la watchlist de l'utilisateur.
* paramètre : film (array), idUtilisateur (int)
* retourne : rien
*/
function ajouterFilmEtWatchlist($film, $idUtilisateur) {
    try {
        $bdd = connexionPDO();

        $sqlCheck = "SELECT COUNT(*) FROM film WHERE id_film = :idFilm";
        $reqCheck = $bdd->prepare($sqlCheck);
        $reqCheck->execute(['idFilm' => $film['id']]);
        $existe = $reqCheck->fetchColumn() > 0;

        if (!$existe) {
            $sqlInsertFilm = "INSERT INTO film (id_film, titre) VALUES (:idFilm, :titre)";
            $reqInsert = $bdd->prepare($sqlInsertFilm);
            $reqInsert->execute([
                'idFilm' => $film['id'],
                'titre' => $film['title']
            ]);
        }

        $sqlVoeu = "INSERT IGNORE INTO voeu (id_film, id_utilisateur) VALUES (:idFilm, :idUtilisateur)";
        $reqVoeu = $bdd->prepare($sqlVoeu);
        $reqVoeu->execute([
            'idFilm' => $film['id'],
            'idUtilisateur' => $idUtilisateur
        ]);
    } catch (PDOException $e) {
        $_SESSION['erreur_message'] = "Erreur ajout watchlist : " . $e->getMessage();
        $_SESSION['code_erreur'] = 500;
        header('Location: erreur');
        exit;
    }
}

/*
* retirerFilmWatchlist : Retire un film de la watchlist d'un utilisateur.
* paramètre : idFilm (int), idUtilisateur (int)
* retourne : rien
*/
function retirerFilmWatchlist($idFilm, $idUtilisateur) {
    try {
        $bdd = connexionPDO();
        $sql = "DELETE FROM voeu WHERE id_film = :idFilm AND id_utilisateur = :idUtilisateur";
        $req = $bdd->prepare($sql);
        $req->execute([
            'idFilm' => $idFilm,
            'idUtilisateur' => $idUtilisateur
        ]);
    } catch (PDOException $e) {
        $_SESSION['erreur_message'] = "Erreur suppression watchlist : " . $e->getMessage();
        $_SESSION['code_erreur'] = 500;
        header('Location: erreur');
        exit;
    }
}

/*
* recupererWatchlist : Récupère la liste des id_film pour un utilisateur donné.
* paramètre : idUtilisateur (int)
* retourne : tableau d'identifiants de films
*/
function recupererWatchlist($idUtilisateur) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT id_film FROM voeu WHERE id_utilisateur = :idUtilisateur";
        $req = $bdd->prepare($sql);
        $req->execute(['idUtilisateur' => $idUtilisateur]);
        return $req->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $_SESSION['erreur_message'] = "Erreur récupération watchlist : " . $e->getMessage();
        $_SESSION['code_erreur'] = 500;
        header('Location: erreur');
        exit;
    }
}
?>
