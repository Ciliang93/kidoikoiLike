<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 10/08/2018
 * Time: 23:10
 */

require_once '../model/PO.php';
require_once '../model/Utilisateur.php';
require_once '../model/UtilisateurTemporaire.php';
require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/UtilisateurTemporaireManager.php';

$dbManager = new DBManager();
$db = $dbManager->connect();
$utilisateurTemporaireManager = new UtilisateurTemporaireManager($db);

$utilisateurTemporaire = new UtilisateurTemporaire(null);
$utilisateurTemporaire->setPseudoUtilisateur("UT5b6df567ab577");

$utilisateurTemporaire = $utilisateurTemporaireManager->recupererPrenom($utilisateurTemporaire);

echo $utilisateurTemporaire->toString();

