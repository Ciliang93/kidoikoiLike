<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 12/08/2018
 * Time: 00:09
 */

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/ParticipeManager.php';
require_once '../manager/UtilisateurTemporaireManager.php';

require_once '../model/PO.php';
require_once '../model/Evenement.php';
require_once '../model/UtilisateurTemporaire.php';


$dbManager = new DBManager();
$db = $dbManager->connect();
$participeManager = new ParticipeManager($db);
$utilisateurTemporaireManager = new UtilisateurTemporaireManager($db);

$evenement = new Evenement(null);
$evenement->setIdEvenement('1');

$tab = $participeManager->recupererParticipants($evenement);

foreach ($tab as $key => $value){

    if( strpos($value['pseudoUtilisateur'], 'UT') !== false ){ //Si c'est un utilisateur temporaire
        $utilisateurTemporaireManager = new UtilisateurTemporaireManager($db);
        $utilisateurTemporaire = new UtilisateurTemporaire(null);
        $utilisateurTemporaire->setPseudoUtilisateur($value['pseudoUtilisateur']);
        $utilisateurTemporaire = $utilisateurTemporaireManager->recupererPrenom($utilisateurTemporaire);

        echo '<button value="'. $utilisateurTemporaire->getPseudoUtilisateur() .'">' . $utilisateurTemporaire->getPrenom() . '</option>';
    } else {
        echo '<button value="' . $value["pseudoUtilisateur"] . '">' . $value["pseudoUtilisateur"] . '</option>';
    }

}