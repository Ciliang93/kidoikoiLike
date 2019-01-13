<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 11/08/2018
 * Time: 20:49
 */

class Repartition extends PO
{
    /**
     * @var $idRepartition int
     * @var $idDepense int
     * @var $pseudoUtilisateur string
     * @var $nbPart int
     * @var $avance bool
     */
    private $idRepartition;
    private $idDepense;
    private $pseudoUtilisateur;
    private $nbPart;
    private $avance;

    public function __construct($data)
    {
        if($data){
            $this->fillObject($data);
        }
    }

    /**
     * @return mixed
     */
    public function getIdRepartition()
    {
        return $this->idRepartition;
    }

    /**
     * @param mixed $idRepartition
     */
    public function setIdRepartition($idRepartition)
    {
        $this->idRepartition = $idRepartition;
    }

    /**
     * @return mixed
     */
    public function getIdDepense()
    {
        return $this->idDepense;
    }

    /**
     * @param mixed $idDepense
     */
    public function setIdDepense($idDepense)
    {
        $this->idDepense = $idDepense;
    }

    /**
     * @return mixed
     */
    public function getPseudoUtilisateur()
    {
        return $this->pseudoUtilisateur;
    }

    /**
     * @param mixed $pseudoUtilisateur
     */
    public function setPseudoUtilisateur($pseudoUtilisateur)
    {
        $this->pseudoUtilisateur = $pseudoUtilisateur;
    }

    /**
     * @return mixed
     */
    public function getNbPart()
    {
        return $this->nbPart;
    }

    /**
     * @param mixed $nbPart
     */
    public function setNbPart($nbPart)
    {
        $this->nbPart = $nbPart;
    }

    /**
     * @return mixed
     */
    public function getAvance()
    {
        return $this->avance;
    }

    /**
     * @param mixed $avance
     */
    public function setAvance($avance)
    {
        $this->avance = $avance;
    }

}