<?php
/**
 * Created by IntelliJ IDEA.
 * User: angel
 * Date: 10-08-18
 * Time: 12:38
 */


require_once '../model/PO.php';
require_once '../model/Depense.php';
require_once '../model/Repartition.php';
require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/DepenseManager.php';
require_once '../manager/RepartitionManager.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * @var $dbManager DBManager
 * @var $db PDO
 * @var $depenseManager DepenseManager
 */
$dbManager = null;
$db = null;
$depenseManager = null;
$repartitionManager = null;

try{
    if (isset($_POST['titreDepense'])){
        if(isset($_POST['dateDepense'])){
            if(isset($_POST['montantDepense'])){
                if(isset($_GET['evt'])) {
                    if(!isset($_POST['descriptionDepense'])){ //Si description est vide
                        $descriptionDepense = '';
                    }

                    $titreDepense = $_POST['titreDepense'];
                    $dateDepense = $_POST['dateDepense'];
                    $montantDepense = $_POST['montantDepense'];
                    $descriptionDepense = $_POST['descriptionDepense'];
                    $idEvenement = $_GET['evt'];

                    $dbManager = new DBManager();
                    $db = $dbManager->connect();
                    $depenseManager = new DepenseManager($db);

                    $depense = new Depense(null);
                    $depense->setTitre($titreDepense);
                    $depense->setDate($dateDepense);
                    $depense->setMontant($montantDepense);
                    $depense->setDescription($descriptionDepense);
                    $depense->setIdEvenement($idEvenement);

                    $depense = $depenseManager->ajouterDepense($depense);

                    $repartitionManager = new RepartitionManager($db);
                    $pseudoUtilisateur = null;
                    $repartition = new Repartition(null);

                    foreach ($_POST as $key => $value) {

                        if(strpos($key, "txtPart") !== false){

                            $repartition->setIdDepense($depense->getIdDepense());
                            $pseudoUtilisateur = str_replace('txtPart', '', $key);
                            $repartition->setPseudoUtilisateur($pseudoUtilisateur);

                            if(strcmp($_POST['selectAvanceur'], $pseudoUtilisateur) == 0) {
                                $repartition->setAvance('1');
                            } else {
                                $repartition->setAvance('0');
                            }

                        }

                        if(strpos($key, "spinnerPart") !== false){

                            $repartition->setNbPart($value);

                        }

                        if($repartition->getIdDepense() && $repartition->getPseudoUtilisateur() && $repartition->getNbPart()){

                            $repartition = $repartitionManager->ajouterRepartition($repartition);
                            $repartition->setIdRepartition(null);
                            $repartition->setIdDepense(null);
                            $repartition->setAvance(null);
                            $repartition->setPseudoUtilisateur(null);
                            $repartition->setNbPart(null);

                        }
                    }

                    header('Location: ../view/afficherEvenement.php?evt='.$_GET['evt']);

                } else echo 'pas d\'evenement ?';
            } else echo 'Pas de montant';
        } else echo 'Pas de date';
    } else echo 'Pas de titre';
}catch(Exception $e){
    die($e->getMessage());
}finally{
    if($db){
        $dbManager->disconnect();
        $db = null;
    }
}