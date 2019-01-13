<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 04/08/2018
 * Time: 20:08
 */

class ParticipeManager extends AbstractManager
{
    /**
     * ParticipeManager constructor.
     * @param $db PDO
     */
    public function __construct($db)
    {
            $this->db = $db;
    }

    /**
     * @param $participe Participe
     * @return bool|Participe
     */
    public function sauverParticipe($participe){
        $prep = null;
        $retour = false;

        try{

            $query = "INSERT INTO participe(idEvenement, pseudoUtilisateur, organise) VALUES (:idEvenement, :pseudoUtilisateur, :organise)";
            $prep = $this->db->prepare($query);
            if($participe->getOrganise()) {
                $prep->bindValue(":organise", $participe->getOrganise());
            } else {
                $query = "INSERT INTO participe(idEvenement, pseudoUtilisateur) VALUES (:idEvenement, :pseudoUtilisateur)";
                $prep = $this->db->prepare($query);
            }
            $prep->bindValue(":idEvenement", $participe->getIdEvenement());
            $prep->bindValue(":pseudoUtilisateur", $participe->getPseudoUtilisateur());


            if($prep->execute()){
                $participe->setIdParticipe($this->db->lastInsertId());
                $retour = $participe;
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

    public function chercherParticipant($pseudoUtilisateur, $idEvenement){ //Renvoie vrai si l'utilisateur participe à l'évenement
        $prep = null;
        $retour = false;

        try{

            $query = "SELECT * FROM participe WHERE pseudoUtilisateur = :pseudoUtilisateur AND idEvenement = :idEvenement";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":pseudoUtilisateur", $pseudoUtilisateur);
            $prep->bindValue(":idEvenement", $idEvenement);
            $prep->execute();
            if($prep->fetch()){
                $retour = true;
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
     * @param $evenement Evenement
     * @return array|bool
     */
    public function recupererParticipants($evenement){
        $prep = null;
        $retour = false;

        try {

            $query= "SELECT * FROM utilisateur AS u INNER JOIN participe AS p ON u.pseudoUtilisateur = p.pseudoUtilisateur WHERE p.idEvenement = :idEvenement";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":idEvenement", $evenement->getIdEvenement());
            $prep->execute();
            $retour = $prep->fetchAll();

        } catch(Exception $e){
            die($e->getMessage());
        } finally {
            if($prep){
                $prep->closeCursor();
            }
        }

        return $retour;
    }
}