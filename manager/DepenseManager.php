<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 09/08/2018
 * Time: 17:53
 */

class DepenseManager extends AbstractManager
{
    /**
     * DepenseManager constructor.
     * @param $db PDO
     */
    public function __construct($db)
    {
        $this->db = $db;
    }


    /**
     * @param $depense Depense
     * @return Depense ( Renvoie la dépense avec son ID garni )
     */
    public function ajouterDepense($depense)
    {
        $prep = null;

        try {
            if($this->validerDepense($depense)){
                $query = 'INSERT INTO depense(titre, date, montant, description, idEvenement) VALUES (:titre, :date, :montant, :description, :idEvenement)';
                $prep = $this->db->prepare($query);
                $prep->bindValue(":titre",$depense->getTitre());
                $prep->bindValue(":date", $depense->getDate());
                $prep->bindValue(":montant",$depense->getMontant());
                $prep->bindValue(":description",$depense->getDescription());
                $prep->bindValue(":idEvenement",$depense->getIdEvenement());

                if($prep->execute()) {
                    $depense->setIdDepense( $this->db->lastInsertId() );
                }
            }

        } catch (Exception $e) {
            die($e->getMessage());
        } finally {
            if ($prep) {
                $prep->closeCursor();
            }
        }

        return $depense;
    }

    /**
     * @param $depense Depense
     * @return bool
     */
    public function validerDepense($depense)
    {
        if ($depense->getTitre()) {

            if (strlen($depense->getTitre()) > 30) {

                return false;

            } else {

                if ( !($depense->getDescription()) ) {

                    $depense->setDescription('');

                } else {

                    if (strlen($depense->getDescription()) > 250) {

                        $depense->setDescription(str_split($depense->getDescription(), 250));

                    }

                }

                if($depense->getMontant()){

                    if(($depense->getMontant() <= 0)) {

                        return false;

                    } else {

                        if($depense->getIdEvenement()){

                            if($depense->getIdEvenement() < 0) {
                                return false;
                            } else {
                                return true;
                            }

                        } else {

                            return false;

                        }

                    }

                } else {

                    return false;

                }

            }

        } else {

            return false;

        }
    }

    /**
     * @param $evenement Evenement (son id)
     * @return bool|array
     */
    public function recupererLesDepenses($evenement){ //Renvoie un tableau avec toutes les dépenses de l'évenement
        $prep = null;
        $retour = false;

        try{

            $query = "SELECT * FROM depense WHERE idEvenement = :idEvenement";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":idEvenement", $evenement->getIdEvenement());
            if($prep->execute()){
                $retour = $prep->fetchAll();
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
     * @param $depense Depense (son id)
     * @return Depense|bool
     */
    public function rechercherDepense($depense){
        $prep = null;
        $retour = false;

        try{
            $query = "SELECT * FROM depense WHERE idDepense = :idDepense";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":idDepense", $depense->getIdDepense());
            if($prep->execute()){

                $arr = $prep->fetch();
                $retour = new Depense($arr);

            }

            return $retour;
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
     * @param $pseudoUtilisateur string
     * @return bool|array
     */
    public function recupererDepenseDuParticipant($pseudoUtilisateur){
        $prep = null;
        $retour = false;

        try{
            $query = "SELECT d.idRepartition, d.idDepense, d.pseudoUtilisateur, d.nbPart, d.avance IN depense AS d
                      INNER JOIN repartition AS p 
                      ON d.idDepense = p.idDepense
                      WHERE p.pseudoUtilisateur = :pseudoUtilisateur";

            $prep = $this->db->prepare($query);
            $prep->bindValue(":pseudoUtilisateur", $pseudoUtilisateur);
            if($prep->execute()){
                $retour = $prep->fetchAll();
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
}