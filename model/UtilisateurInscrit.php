<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 01/08/2018
 * Time: 17:45
 */

class UtilisateurInscrit extends Utilisateur
{
    private $email;
    private $password;
    private $sel;
    private $img;

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
        return $this->pseudoUtilisateur;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudoUtilisateur($pseudo)
    {
        $this->pseudoUtilisateur = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getSel()
    {
        return $this->sel;
    }

    /**
     * @param mixed $sel
     */
    public function setSel($sel)
    {
        $this->sel = $sel;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }
}