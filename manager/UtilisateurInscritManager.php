<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 01/08/2018
 * Time: 18:06
 */

class UtilisateurInscritManager extends AbstractManager
{
    /**
     * UtilisateurInscritManager constructor.
     * @param $db PDO
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $utilisateur UtilisateurInscrit
     */
    public function ajouterUtilisateur($utilisateur)
    {
        try {
            if($this->verifierChampsSansImg($utilisateur)){
                if(!$this->chercherUtilisateur($utilisateur)){
                    //Ajout dans la table utilisateur
                    $prep = null;
                    $query = "INSERT INTO utilisateur(pseudoUtilisateur) VALUES (:pseudoUtilisateur)";
                    $prep = $this->db->prepare($query);
                    $prep->bindValue(":pseudoUtilisateur", $utilisateur->getPseudoUtilisateur());
                    $prep->execute();

                    //Ajout dans la table utilisateurinscrit
                    $query = "INSERT INTO utilisateurinscrit(pseudoUtilisateur, email, password, sel, img) VALUES (:pseudoUtilisateur, :email, :password, :sel, :img)";
                    $prep = $this->db->prepare($query);
                    $prep->bindValue(":pseudoUtilisateur", $utilisateur->getPseudoUtilisateur());
                    $prep->bindValue(":email", $utilisateur->getEmail());
                    $prep->bindValue(":password", $utilisateur->getPassword());
                    $prep->bindValue(":sel", $utilisateur->getSel());
                    $prep->bindValue(":img", "defaultUser.png"); //Image par défaut à la création
                    $prep->execute();
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        } finally {
            if ($prep != null) {
                $prep->closeCursor();
            }
        }
    }

    /**
     * @param $utilisateur UtilisateurInscrit
     * @return boolean
     */
    public function chercherUtilisateur($utilisateur)
    {
        try {
            //Recherche de l'utilisateur sur son pseudo
            $prep = null;
            $query = "SELECT * FROM utilisateur WHERE pseudoUtilisateur = :pseudoUtilisateur";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":pseudoUtilisateur", $utilisateur->getPseudoUtilisateur());
            $prep->execute();
            $arr = $prep->fetch();
            if ($arr) {
                return true;
            } else { //Si le pseudo est introuvable, on recherche l'email
                $query = "SELECT * FROM utilisateurinscrit WHERE email = :email";
                $prep = $this->db->prepare($query);
                if ($utilisateur->getEmail() != null) { //On s'assure que l'objet UtilisateurInscrit possède un email
                    $prep->bindValue(":email", $utilisateur->getEmail());
                    $prep->execute();
                    $arr = $prep->fetch();
                    if ($arr) {
                        return true;
                    }
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        } finally {
            if ($prep != null) {
                $prep->closeCursor();
            }
        }
        return false;
    }

    /**
     * @param $utilisateur UtilisateurInscrit
     * @return UtilisateurInscrit
     */
    public function recupererUtilisateurInscrit($utilisateur)
    {
        try {
            $prep = null;
            if ($this->chercherUtilisateur($utilisateur)) { //Si l'utilisateur est présent en base de donnée

                $query = "SELECT * FROM utilisateurinscrit WHERE pseudoUtilisateur = :pseudoUtilisateur";
                $prep = $this->db->prepare($query);
                $prep->bindValue(":pseudoUtilisateur", $utilisateur->getPseudoUtilisateur());
                $prep->execute();
                $arr = $prep->fetch();
                if ($arr) {
                    $utilisateur = new UtilisateurInscrit($arr); //Hydratation
                }
            } else {
                $utilisateur = null;
            }
        } catch (Exception $e) {
            die($e->getMessage());
        } finally {
            if ($prep != null) {
                $prep->closeCursor();
            }
        }
        return $utilisateur;
    }

    public function modifierUtilisateurInscrit($utilisateur)
    {
        try {
            $prep = null;
            if ($this->verifierChamps($utilisateur)) {
                if ($this->chercherUtilisateur($utilisateur)) { //si l'utilisateur existe
                    $query = 'UPDATE utilisateurinscrit SET email = :email, password = :password, sel = :sel, img = :img WHERE pseudoUtilisateur = :pseudoUtilisateur';
                    $prep = $this->db->prepare($query);
                    /** @var UtilisateurInscrit $utilisateur */
                    $prep->bindValue(":pseudoUtilisateur", $utilisateur->getPseudoUtilisateur());
                    $prep->bindValue(":email", $utilisateur->getEmail());
                    $prep->bindValue(":password", $utilisateur->getPassword());
                    $prep->bindValue(":sel", $utilisateur->getSel());
                    $prep->bindValue(":img", $utilisateur->getImg());
                    return $prep->execute();

                }
            }
            return false;
        } catch (Exception $e) {
            die($e->getMessage());
        } finally {
            if ($prep != null) {
                $prep->closeCursor();
            }
        }
    }


    /**
     * @param UtilisateurInscrit $utilisateur
     */
    public function verifierChamps($utilisateur)
    { //Renvoie true si tous les attributs de l'utilisateur sont settés
        $retour = false;
        if ($utilisateur->getPseudoUtilisateur() != null) {
            if ($utilisateur->getEmail() != null) {
                if ($utilisateur->getPassword() != null) {
                    if ($utilisateur->getSel() != null) {
                        if ($utilisateur->getImg() != null) {
                            $retour = true;
                        }
                    }
                }
            }
        }
        return $retour;
    }

    /**
     * @param UtilisateurInscrit $utilisateur
     */
    public function verifierChampsSansImg($utilisateur)
    { //Renvoie true si tous les attributs de l'utilisateur sont settés
        $retour = false;
        if ($utilisateur->getPseudoUtilisateur() != null) {
            if ($utilisateur->getEmail() != null) {
                if ($utilisateur->getPassword() != null) {
                    if ($utilisateur->getSel() != null) {
                        $retour = true;
                    }
                }
            }
        }
        return $retour;
    }

    public function recupererTousLesPseudos(){
        try {
            $prep = null;

                $query = "SELECT pseudoUtilisateur FROM utilisateurinscrit";
                $prep = $this->db->prepare($query);
                $prep->execute();
                $arr = $prep->fetchAll(PDO::FETCH_COLUMN, 0);

                if (!$arr) {
                    echo 'Aucun utilisateur récupéré';
                }
        } catch (Exception $e) {
            die($e->getMessage());
        } finally {
            if ($prep != null) {
                $prep->closeCursor();
            }
        }
        return $arr;
    }
}