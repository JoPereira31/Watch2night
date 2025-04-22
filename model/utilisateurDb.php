<?php
require_once RACINE . '/model/bdd.php';

/*
* ajoutUtilisateur : Ajoute un nouvel utilisateur dans la base.
* paramètre : pseudo (string), email (string), motDePasse (string)
* retourne : true si l'insertion réussit, false sinon
*/
function ajoutUtilisateur($pseudo, $email, $motDePasse) {
    try {
        $bdd = connexionPDO();
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (pseudo, email, mot_de_passe, role) VALUES (?, ?, ?, 'membre')";
        $stmt = $bdd->prepare($sql);
        return $stmt->execute([$pseudo, $email, $hash]);
    } catch (PDOException $e) {
        redirigerErreur("Erreur ajout utilisateur : " . $e->getMessage(), 500);
    }
}

/*
* existeUtilisateur : Vérifie si un pseudo ou un email existe déjà.
* paramètre : pseudo (string), email (string)
* retourne : true si trouvé, false sinon
*/
function existeUtilisateur($pseudo, $email) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE pseudo = ? OR email = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$pseudo, $email]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        redirigerErreur("Erreur vérification utilisateur : " . $e->getMessage(), 500);
    }
}

/*
* verifieConnexion : Vérifie si un utilisateur et mot de passe sont valides.
* paramètre : pseudo (string), motDePasse (string)
* retourne : tableau utilisateur si valide, false sinon
*/
function verifieConnexion($pseudo, $motDePasse) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT * FROM utilisateur WHERE pseudo = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$pseudo]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            return $utilisateur;
        }

        return false;
    } catch (PDOException $e) {
        redirigerErreur("Erreur vérification connexion : " . $e->getMessage(), 500);
    }
}

/*
* majUtilisateur : Met à jour le pseudo ou email d'un utilisateur.
* paramètre : idUtilisateur , nouveauPseudo , nouvelEmail 
* retourne :
*/
function majUtilisateur($idUtilisateur, $nouveauPseudo, $nouvelEmail) {
    try {
        $bdd = connexionPDO();

        // On vérifie si au moins un champ est rempli
        if (empty($nouveauPseudo) && empty($nouvelEmail)) {
            return false;
        }

        $fields = [];
        $params = [];

        if (!empty($nouveauPseudo)) {
            $fields[] = "pseudo = ?";
            $params[] = $nouveauPseudo;
        }

        if (!empty($nouvelEmail)) {
            $fields[] = "email = ?";
            $params[] = $nouvelEmail;
        }

        // Ajout de l'ID utilisateur à la fin des paramètres
        $params[] = $idUtilisateur;

        $sql = "UPDATE utilisateur SET " . implode(", ", $fields) . " WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($sql);

        return $stmt->execute($params);
        
    } catch (PDOException $e) {
        redirigerErreur("Erreur mise à jour utilisateur : " . $e->getMessage(), 500);
    }
}

/*
* changerMdp : Change le mot de passe d'un utilisateur.
* paramètre : idUtilisateur (int), ancienMdp (string), nouveauMdp (string)
* retourne : true si changé, false sinon
*/
function changerMdp($idUtilisateur, $ancienMdp, $nouveauMdp) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT mot_de_passe FROM utilisateur WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idUtilisateur]);
        $ancienHash = $stmt->fetchColumn();

        if ($ancienHash && password_verify($ancienMdp, $ancienHash)) {
            $nouveauHash = password_hash($nouveauMdp, PASSWORD_DEFAULT);
            $sqlUpdate = "UPDATE utilisateur SET mot_de_passe = ? WHERE id_utilisateur = ?";
            $stmtUpdate = $bdd->prepare($sqlUpdate);
            return $stmtUpdate->execute([$nouveauHash, $idUtilisateur]);
        }

        return false;
    } catch (PDOException $e) {
        redirigerErreur("Erreur changement mot de passe : " . $e->getMessage(), 500);
    }
}

/*
* rechercherUtilisateurs : Recherche des utilisateurs par pseudo ou email.
* paramètre : motCle (string)
* retourne : tableau associatif d'utilisateurs
*/
function rechercherUtilisateurs($motCle) {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT * FROM utilisateur WHERE pseudo LIKE ? OR email LIKE ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute(["%$motCle%", "%$motCle%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        redirigerErreur("Erreur recherche utilisateurs : " . $e->getMessage(), 500);
    }
}

/*
* listerUtilisateurs : Liste tous les utilisateurs.
* paramètre : aucun
* retourne : tableau associatif des utilisateurs
*/
function listerUtilisateurs() {
    try {
        $bdd = connexionPDO();
        $sql = "SELECT * FROM utilisateur ORDER BY id_utilisateur DESC";
        return $bdd->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        redirigerErreur("Erreur liste utilisateurs : " . $e->getMessage(), 500);
    }
}

/*
* supprimerUtilisateur : Supprime un utilisateur spécifique.
* paramètre : idUtilisateur (int)
* retourne : true si supprimé, false sinon
*/
function supprimerUtilisateur($idUtilisateur) {
    try {
        $bdd = connexionPDO();
        $sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($sql);
        return $stmt->execute([$idUtilisateur]);
    } catch (PDOException $e) {
        redirigerErreur("Erreur suppression utilisateur : " . $e->getMessage(), 500);
    }
}

/*
* supprimerCompte : Supprime un utilisateur et ses voeux (watchlist).
* paramètre : idUtilisateur (int)
* retourne : rien
*/
function supprimerCompte($idUtilisateur) {
    try {
        $bdd = connexionPDO();
        
        $sql1 = "DELETE FROM voeu WHERE id_utilisateur = :idUtilisateur";
        $req1 = $bdd->prepare($sql1);
        $req1->execute(['idUtilisateur' => $idUtilisateur]);
        
        $sql2 = "DELETE FROM utilisateur WHERE id_utilisateur = :idUtilisateur";
        $req2 = $bdd->prepare($sql2);
        $req2->execute(['idUtilisateur' => $idUtilisateur]);
    } catch (PDOException $e) {
        redirigerErreur("Erreur suppression compte : " . $e->getMessage(), 500);
    }
}
?>