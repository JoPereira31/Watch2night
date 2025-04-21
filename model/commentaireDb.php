<?php
require_once RACINE . '/model/bdd.php';

/*
* ajouterCommentaire : Ajoute un nouveau commentaire à un film.
* paramètre : idFilm (int), idUtilisateur (int), contenu (string)
* retourne : rien
*/
function ajouterCommentaire($idFilm, $idUtilisateur, $contenu) {
    try {
        $bdd = connexionPDO();
        $sql = "INSERT INTO commentaire (contenu, date_com, id_film, id_utilisateur)
                VALUES (:contenu, NOW(), :idFilm, :idUtilisateur)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            'contenu' => $contenu,
            'idFilm' => $idFilm,
            'idUtilisateur' => $idUtilisateur
        ]);
    } catch (PDOException $e) {
        redirigerErreur("Erreur ajout commentaire : " . $e->getMessage(), 500);
    }
}

/*
* supprimerCommentaire : Supprime un commentaire par son id.
* paramètre : idCommentaire (int)
* retourne : true si supprimé, false sinon
*/
function supprimerCommentaire($idCommentaire) {
    try {
        $bdd = connexionPDO();
        $sql = "DELETE FROM commentaire WHERE id_com = :id";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id', $idCommentaire, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        redirigerErreur("Erreur suppression commentaire : " . $e->getMessage(), 500);
    }
}

/*
* commentairesRecents : Récupère les derniers commentaires postés.
* paramètre : limite (int) par défaut 10
* retourne : tableau associatif de commentaires
*/
function commentairesRecents($limite = 10) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT c.id_com, c.contenu, u.pseudo, f.titre
                FROM commentaire c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                JOIN film f ON c.id_film = f.id_film
                ORDER BY c.date_com DESC
                LIMIT :limite";
        $stmt = $bdd->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        redirigerErreur("Erreur récupération commentaires récents : " . $e->getMessage(), 500);
    }
}

/*
* commentaires : Récupère tous les commentaires d'un film.
* paramètre : idFilm (int)
* retourne : tableau associatif des commentaires
*/
function commentaires($idFilm) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT c.id_com, c.contenu, c.date_com, c.id_utilisateur, u.pseudo
                FROM commentaire c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                WHERE c.id_film = :id_film
                ORDER BY c.date_com DESC";
        $stmt = $bdd->prepare($sql);
        $stmt->bindValue(':id_film', $idFilm, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        redirigerErreur("Erreur récupération commentaires film : " . $e->getMessage(), 500);
    }
}

/*
* supprimerCommentaireParUtilisateur : Supprime un commentaire d'un utilisateur spécifique.
* paramètre : idCommentaire (int), idUtilisateur (int)
* retourne : true si supprimé, false sinon
*/
function supprimerCommentaireParUtilisateur($idCommentaire, $idUtilisateur) {
    try {
        $bdd = connexionPDO();
        $sql = "DELETE FROM commentaire WHERE id_com = :id AND id_utilisateur = :user";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            'id' => $idCommentaire,
            'user' => $idUtilisateur
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        redirigerErreur("Erreur suppression commentaire utilisateur : " . $e->getMessage(), 500);
    }
}

/*
* modifierCommentaireParUtilisateur : Modifie un commentaire existant pour un utilisateur.
* paramètre : idCommentaire (int), idUtilisateur (int), nouveauContenu (string)
* retourne : true si modifié, false sinon
*/
function modifierCommentaireParUtilisateur($idCommentaire, $idUtilisateur, $nouveauContenu) {
    try {
        $bdd = connexionPDO();
        $sql = "UPDATE commentaire 
                SET contenu = :contenu 
                WHERE id_com = :id_com AND id_utilisateur = :id_utilisateur";
        $stmt = $bdd->prepare($sql);
        $stmt->bindValue(':contenu', $nouveauContenu, PDO::PARAM_STR);
        $stmt->bindValue(':id_com', $idCommentaire, PDO::PARAM_INT);
        $stmt->bindValue(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        redirigerErreur("Erreur modification commentaire : " . $e->getMessage(), 500);
    }
}
?>