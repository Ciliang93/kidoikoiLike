<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 04/08/2018
 * Time: 20:08
 */

class EvenementManager extends AbstractManager
{
    /**
     * EvenementManager constructor.
     * @param $db PDO
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $evenement Evenement
     * @return Evenement
     */
    public function ajouterEvenement($evenement){
        $prep = null;

        try{
            if($this->validerEvenement($evenement)){
                $query = "INSERT INTO evenement(nom, description) VALUES (:nom, :description)";
                $prep = $this->db->prepare($query);
                $prep->bindValue(":nom",$evenement->getNom());
                $prep->bindValue(":description", $evenement->getDescription());

                if($prep->execute()){
                    $evenement->setIdEvenement( $this->db->lastInsertId() );
                }
            }
        } catch(Exception $e){
            die($e->getMessage());
        } finally {
            if($prep) {
                $prep->closeCursor();
            }
        }

        return $evenement;
    }

    /**
     * @param $evenement Evenement
     * @return boolean
     */
    public function archiverEvenement($evenement){

        try{
            $prep = null;
            $query = "UPDATE evenement SET actif = :actif WHERE idEvenement = :idEvenement";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":actif", "0");
            $prep->bindValue(":idEvenement", $evenement->getIdEvenement());

            return $prep->execute();

        } catch(Exception $e){
            die($e->getMessage());
        } finally {
            if($prep){
                $prep->closeCursor();
            }
        }
    }

    /**
     * @param $evenement Evenement
     * @return bool
     */
    public function chercherEvenement($evenement){
        $retour = false;
        try{
            if($evenement->getIdEvenement()){
                $prep = null;
                $query = "SELECT * FROM evenement WHERE idEvenement = :idEvenement";
                $prep = $this->db->prepare($query);
                $prep->bindValue(":idEvenement", $evenement->getIdEvenement());
                $prep->execute();
                $arr = $prep->fetch();
                if($arr){
                    $retour = true;
                }
            } else {
                die("recherche impossible, ID manquant");
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
     * @return bool
     */
    public function validerEvenement($evenement) { //Renvoie vrai si l'objet Evenement est valide
        $retour = false;

        if($evenement->getNom()){
            if(strlen($evenement->getNom()) > 50) {
                $retour = false;
            } else {
                $retour = true;
            }
        }

        if($evenement->getDescription()){
            if(strlen($evenement->getDescription()) > 250){
                $retour = false;
            }
        }

        return $retour;
    }

    public function recupEvenementsLiesActifs($pseudo){
        /**
         * @var $tableauEvenement array
         */
        $prep = null;
        $tableauEvenement = null;

        try{
            $query = "SELECT evt.idEvenement, evt.nom, evt.description, evt.actif FROM evenement AS evt
                      INNER JOIN participe AS p ON evt.idEvenement = p.idEvenement
                      WHERE p.pseudoUtilisateur = :pseudoUtilisateur AND evt.actif = 1";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":pseudoUtilisateur", $pseudo);
            $prep->execute();
            $arr = $prep->fetchAll();
        }catch (Exception $e){
            die($e->getMessage());
        }finally{
            if($prep){
                $prep->closeCursor();
            }
        }
        return $arr;
    }



    public function recupEvenementsLiesArchives($pseudo){
        /**
         * @var $tableauEvenement array
         */
        $prep = null;
        $tableauEvenement = null;

        try{
            $query = "SELECT evt.idEvenement, evt.nom, evt.description FROM evenement AS evt
                      INNER JOIN participe AS p ON evt.idEvenement = p.idEvenement
                      WHERE p.pseudoUtilisateur = :pseudoUtilisateur AND evt.actif = 0";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":pseudoUtilisateur", $pseudo);
            $prep->execute();
            $arr = $prep->fetchAll();
        }catch (Exception $e){
            die($e->getMessage());
        }finally{
            if($prep){
                $prep->closeCursor();
            }
        }
        return $arr;
    }

    /**
     * @param $id int
     * @return Evenement
     */
    public function recupererEvenement($id){
        $evenement = null;
        $prep = null;

        try{

            $query = "SELECT * FROM evenement WHERE idEvenement = :idEvenement";
            $prep = $this->db->prepare($query);
            $prep->bindValue(":idEvenement", $id);
            if($prep->execute()){
                $data = $prep->fetch();
                $evenement = new Evenement($data);
            }
        } catch(Exception $e){
            die($e->getMessage());
        } finally {
            if($prep){
                $prep->closeCursor();
            }
        }

        return $evenement;
    }

}