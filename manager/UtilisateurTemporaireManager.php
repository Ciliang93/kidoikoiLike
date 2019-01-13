<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 09/08/2018
 * Time: 19:55
 */

class UtilisateurTemporaireManager extends AbstractManager
{
    /**
     * UtilisateurTemporaireManager constructor.
     * @param $db PDO
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $utilisateurTemporaire UtilisateurTemporaire
     * @return UtilisateurTemporaire|bool
     */
    public function ajouterUtilisateurTemporaire($utilisateurTemporaire) {
        $prep = null;
        $pseudo = null;
        $retour = false;

        try{

            if($utilisateurTemporaire->getPrenom()) {
                $pseudo = uniqid('UT');

                $utilisateurTemporaire->setPseudoUtilisateur($pseudo);

                $query = "INSERT INTO utilisateur(pseudoUtilisateur) VALUES (:pseudoUtilisateur)";
                $prep = $this->db->prepare($query);
                $prep->bindValue(":pseudoUtilisateur", $utilisateurTemporaire->getPseudoUtilisateur());
                if($prep->execute()){
                    $query = "INSERT INTO utilisateurtemporaire(pseudoUtilisateur, prenom) VALUES (:pseudoUtilisateur, :prenom)";
                    $prep = $this->db->prepare($query);
                    $prep->bindValue(":pseudoUtilisateur", $utilisateurTemporaire->getPseudoUtilisateur());
                    $prep->bindValue(":prenom", $utilisateurTemporaire->getPrenom());
                    if($prep->execute()) {
                        $retour = $utilisateurTemporaire;
                    }
                }
            }

        } catch(Exception $e){
            die($e->getMessage());
        } finally {
            if($prep){
                $prep->closeCursor();
            }
        }

        return $retour;
    }

    /**
     * @param $utilisateurTemporaire UtilisateurTemporaire
     * @return UtilisateurTemporaire|bool
     */
    public function recupererPrenom($utilisateurTemporaire){
        $prep = null;
        $retour = null;
        $result = false;

        try{

            $query = "SELECT * FROM utilisateurtemporaire WHERE pseudoUtilisateur = :pseudoUtilisateur";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":pseudoUtilisateur", $utilisateurTemporaire->getPseudoUtilisateur());
            if($prep->execute()){
                $result = $prep->fetch();
            }
            if($result){
                $retour = new UtilisateurTemporaire($result);
            }

        }catch(Exception $e){
            die($e->getMessage());
        } finally {
            if($prep){
                $prep->closeCursor();
            }
        }

        return $retour;
    }


}