<?php
/**
 * Created by IntelliJ IDEA.
 * User: angel
 * Date: 10-08-18
 * Time: 20:01
 */


require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/EvenementManager.php';
require_once '../model/PO.php';
require_once '../model/Evenement.php';

$dbManager = null;
$db = null;
$prep = null;

try{
    if(isset($_POST)){
        if(isset($_POST['evt'])){
            $dbManager = new DBManager();
            $db = $dbManager->connect();
            $evenementManager = new EvenementManager($db);

            $evenement = $evenementManager->recupererEvenement($_POST['evt']);

            if($evenementManager->archiverEvenement($evenement)){
                header('Location: ../view/accueil.php');
            }

        }
    }

}catch (Exception $e){
    die($e->getMessage());
}finally{
    if($db){
        $db=null;
    }
}


