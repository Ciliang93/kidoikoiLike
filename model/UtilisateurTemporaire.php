<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 01/08/2018
 * Time: 17:30
 */

require_once 'Utilisateur.php';
require_once 'PO.php';

class UtilisateurTemporaire extends Utilisateur
{
    private $prenom;

    public function __construct($data)
    {
        if($data){
            $this->fillObject($data);
        }
    }

    /**
     * @return mixed
     */
    public function getPseudoUtilisateur()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudoUtilisateur($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
}