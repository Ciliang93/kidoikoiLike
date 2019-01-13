<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 09/08/2018
 * Time: 16:26
 */

class Depense extends PO
{
    /**
     * @var $idDepense int
     * @var $titre string
     * @var $date string
     * @var $montant double
     * @var $description string
     * @var $idEvenement int
     */
    private $idDepense;
    private $titre;
    private $date;
    private $montant;
    private $description;
    private $idEvenement;

    public function __construct($data)
    {
        if($data){
            $this->fillObject($data);
        }
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
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
}