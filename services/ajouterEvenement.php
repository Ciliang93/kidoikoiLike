<?php

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/ParticipeManager.php';
require_once '../manager/EvenementManager.php';
require_once '../model/PO.php';
require_once '../model/Participe.php';
require_once '../model/Evenement.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/**
 * @var $dbManager DBManager
 * @var $db PDO
 * @var $participeManager ParticipeManager
 * @var $evenementManager EvenementManager
 */
$dbManager = null;
$db = null;
$participeManager = null;
$evenementManager = null;

try{
    if( isset($_POST['titreNewEvent']) ){
        $dbManager = new DBManager();
        $db = $dbManager->connect();
        $participeManager = new ParticipeManager($db);
        $evenementManager = new EvenementManager($db);

        $nom = $_POST['titreNewEvent'] ;

        if( isset($_POST['descriptionNewEvent']) ) {
            $description = $_POST['descriptionNewEvent'];
        }

        //Création d'un nouvel evenement
        $evenement = new Evenement(null);
        $evenement->setNom($nom);
        if($description){
            $evenement->setDescription($description);
        } else {
            $evenement->setDescription('');
        }

         $evenement = $evenementManager->ajouterEvenement($evenement);
         if($evenement->getIdEvenement()){
             $participe = new Participe(null);
             $participe->setPseudoUtilisateur($_SESSION['pseudoUtilisateur']);
             $participe->setIdEvenement($evenement->getIdEvenement());
             $participe->setOrganise(true);

             if($participeManager->sauverParticipe($participe)){
                 header('Location: ../view/accueil.php');
             } else {
                 header('Location ../index.php?erreur=true');
             }
         } else {
             header('Location ../index.php?erreur=true');
         }
    }
} catch(Exception $e){
    die($e->getMessage());
} finally {
    if($db){
        $dbManager->disconnect();
        $db = null;
    }
}

