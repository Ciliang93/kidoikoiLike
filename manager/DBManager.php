<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 01/08/2018
 * Time: 17:11
 */

class DBManager extends AbstractManager
{
    function connect(){
        $this->db = null;
        try{
            $strConnection = 'mysql:host=localhost;dbname=kidoikoi';
            $this->db = new PDO($strConnection, 'root', '');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile () . ' Ligne : ' . $e->getLine () . ' : ' . $e->getMessage ();
            die ( $msg );
        }
        return $this->db;
    }

    function disconnect(){
        $this->db = null;
    }
}