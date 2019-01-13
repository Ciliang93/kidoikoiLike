<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 11/08/2018
 * Time: 20:51
 */

class RepartitionManager extends AbstractManager
{
    /**
     * RepartitionManager constructor.
     * @param $db PDO
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $repartition Repartition
     * @return bool|Repartition
     */
    public function ajouterRepartition($repartition){
        $retour = false;
        $prep = null;

        try{
            $query = "INSERT INTO repartition (idDepense, pseudoUtilisateur, nbPart, avance) VALUES (:idDepense, :pseudoUtilisateur, :nbPart, :avance)";
            $prep = $this->db->prepare($query);
            $prep->bindValue("idDepense", $repartition->getIdDepense());
            $prep->bindValue(":pseudoUtilisateur", $repartition->getPseudoUtilisateur());
            $prep->bindValue(":nbPart", $repartition->getNbPart());
            $prep->bindValue(":avance", $repartition->getAvance());
            if($prep->execute()){
                $repartition->setIdRepartition($this->db->lastInsertId());
                $retour = $repartition;
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
     * @param $idDepense Depense
     * @return bool|array
     */
    public function recupererRepartition($idDepense){ //Renvoie les répartition d'une dépense
        $prep = null;
        $retour = false;

        try{
            $query = "SELECT * FROM repartition WHERE idDepense = :idDepense";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":idDepense", $idDepense);
            if($prep->execute()){
                $arr = $prep->fetchAll();
                $retour = $arr;
            }

        } catch (Exception $e){
            die($e->getMessage());
        } finally {
            if($prep){
                $prep->closeCursor();
            }
        }

        return $retour;
    }

    public function recupererRepartitionDuParticipant($pseudoUtilisateur, $idEvenement){
        $prep = null;
        $retour = false;

        try{

            $query = "SELECT r.idRepartition, r.idDepense, r.pseudoUtilisateur, r.nbPart, r.avance, d.idDepense, d.titre, d.date, d.montant, d.description, d.idEvenement FROM repartition AS r
                      INNER JOIN depense AS d ON r.idDepense = d.idDepense
                      INNER JOIN utilisateur AS u ON r.pseudoUtilisateur = u.pseudoUtilisateur
                      WHERE r.pseudoUtilisateur = :pseudoUtilisateur
                      AND d.idEvenement = :idEvenement";

            $prep = $this->db->prepare($query);
            $prep->bindValue(":pseudoUtilisateur", $pseudoUtilisateur);
            $prep->bindValue("idEvenement", $idEvenement);
            if($prep->execute()){
                $retour = $prep->fetchAll();
            }

        } catch(Exception $e){
            die($e->getMessage());
        } finally{
            if($prep){
                $prep->closeCursor();
            }

        }

        return $retour;
    }

}