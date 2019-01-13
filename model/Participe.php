<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 04/08/2018
 * Time: 20:08
 */

class Participe extends PO
{
    /**
     * @var $idParticipe int
     * @var $idEvenement int
     * @var $pseudoUtilisateur string
     * @var $organise bool
     */
    private $idParticipe;
    private $idEvenement;
    private $pseudoUtilisateur;
    private $organise;

    public function __construct($arr)
    {
        if($arr){
            $this->fillObject($arr);
        }
    }

    /**
     * @return mixed
     */
    public function getIdParticipe()
    {
        return $this->idParticipe;
    }

    /**
     * @param mixed $idParticipe
     */
    public function setIdParticipe($idParticipe)
    {
        $this->idParticipe = $idParticipe;
    }

    /**
     * @return mixed
     */
    public function getIdEvenement()
    {
        return $this->idEvenement;
    }

    /**
     * @param mixed $idEvenement
     */
    public function setIdEvenement($idEvenement)
    {
        $this->idEvenement = $idEvenement;
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
    public function getOrganise()
    {
        return $this->organise;
    }

    /**
     * @param mixed $organise
     */
    public function setOrganise($organise)
    {
        $this->organise = $organise;
    }
}