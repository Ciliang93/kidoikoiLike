<?php

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/ParticipeManager.php';
require_once '../manager/EvenementManager.php';
require_once '../manager/UtilisateurInscritManager.php';
require_once '../manager/UtilisateurTemporaireManager.php';
require_once '../manager/DepenseManager.php';
require_once '../manager/RepartitionManager.php';
require_once '../model/PO.php';
require_once '../model/Participe.php';
require_once '../model/Evenement.php';
require_once '../model/UtilisateurTemporaire.php';
require_once '../model/Depense.php';
require_once '../model/Repartition.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 12/08/2018
 * Time: 04:32
 */

echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Evenement</title>
        <link rel="stylesheet" href="../css/styles.css">
        <!--Link css bootstrap-->
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/bootstrap-grid.css">
        <!--fontawesome sert à ajouter des petites icones-->
        <link rel="stylesheet" href="../css/fontawesome-all.css">
    </head>
    <body class="bodyPages">
    <header>
        <!--Barre de navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <a href="accueil.php" class="navbar-brand">KiDoiKoi</a>
            <!--Button est ce qui permet de collapse la navbar sur les trop petites résolutions comme les smartphones-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <!--Le span est l\'icone de ce bouton qui permet de dérouler la navbar sur smartphone-->
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--La classe signale que la navbar peut être collapser-->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!--Liste des éléments de la navbar de gauche-->
                <ul class="navbar-nav mr-auto">
                    <!--la class active permet de signaler la page sur laquelle on se trouve, donc à déplacer sur chaque page-->
                    <li class="nav-item">
                        <a class="nav-link" id="newEvent" href="creationEvenement.php">Nouveau
                            KiDoiKoi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profileManagement" href="profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="finishedEvents" href="archives.php">Evènements archivés</a>
                    </li>
                </ul>
                <!--La création de cette 2e ul permet de la placer à droite tandis que la 1e reste à gauche par défaut-->
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link disabled"><i class="far fa-user"></i>' . $_SESSION["pseudoUtilisateur"] . '</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../services/Deconnexion.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>  
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script src="../js/fontawesome-all.js"></script>
<script src="../js/choixMembresEvenement.js"></script>
<script src="../js/nouvelleDepense.js"></script>
<script src="../js/ajouterParticipantInvite.js"></script>     
<script src="../js/afficherDepense.js"></script>     
';

$idEvent = $_POST['idEvent'];
$dbManager = new DBManager();
$db = $dbManager->connect();
$depenseManager = new DepenseManager($db);
$repartitionManager = new RepartitionManager($db);
$utilisateurTemporaireManager = new UtilisateurTemporaireManager($db);
$depense = new Depense(null);
$depense->setIdDepense($_POST['idDepense']);
$depense = $depenseManager->rechercherDepense($depense);
$timeStamp = strtotime($depense->getDate());
$repartition = null;
$nbPartTotale = 0;
$pseudoAvanceur = null;
$tableauDeRepartitionSQL = $repartitionManager->recupererRepartition($depense->getIdDepense());
/**
 * @var $tableauDeRepartitionObject Repartition[]
 */
$tableauDeRepartitionObject = [];

foreach($tableauDeRepartitionSQL as $key => $row){
    $repartition = new Repartition($row);
    array_push($tableauDeRepartitionObject, $repartition);
}

foreach ($tableauDeRepartitionObject as $repartition) {
    $nbPartTotale++;
    if($repartition->getAvance()){
        $pseudoAvanceur = $repartition->getPseudoUtilisateur();
        $pseudoAffichage = $pseudoAvanceur;

        if(strpos($pseudoAvanceur, 'UT') !== false){
            $utilisateurTemporaire = new UtilisateurTemporaire(null);
            $utilisateurTemporaire->setPseudoUtilisateur($pseudoAvanceur);
            $utilisateurTemporaire = $utilisateurTemporaireManager->recupererPrenom($utilisateurTemporaire);
            $pseudoAffichage = $utilisateurTemporaire->getPrenom();
        }
    }
}

echo '
<div class="mainDiv" id="mainDiv">
    <form action="afficherEvenement.php?evt='. $idEvent .'" method="POST">
        <input type="hidden" id="idEvent" value="'. $idEvent .'">
        <button class="btn btn-secondary" id="btnRedirect"> <i class="fas fa-arrow-left"></i> Retour à l\'évenement</button>
    </form>
    <br>
    <h4 id="titreDepense">'. $depense->getTitre() .'  ('. date('d-m-Y', $timeStamp) .')</h4>
    <h6 id="descriptionDepense">'. $depense->getDescription() .'</h6>
    <h3 id="montantDepense" style="text-align: center">  <button class="btn btn-success" >'. $depense->getMontant() .'€</button> <i class="fas fa-hand-holding-usd"></i> <button class="btn btn-info">'. $pseudoAffichage .'</button> </h3>
    <br>
    <div style="text-align: center">
        <table style="margin: 0 auto;"class="table table-dark">
';
        foreach ($tableauDeRepartitionObject as $repartition){
            $pseudoUtilisateur = $repartition->getPseudoUtilisateur();
            if( strcmp($pseudoUtilisateur, $pseudoAvanceur) != 0 ){ //Si la répartition ne concerne pas l'avanceur
                if( strpos($repartition->getPseudoUtilisateur(), 'UT') !== false){
                    $utilisateurTemporaire = new UtilisateurTemporaire(null);
                    $utilisateurTemporaire->setPseudoUtilisateur($pseudoUtilisateur);
                    $utilisateurTemporaire = $utilisateurTemporaireManager->recupererPrenom($utilisateurTemporaire);
                    $pseudoUtilisateur = $utilisateurTemporaire->getPrenom();
                }

                echo '
            
                <tr>
                    <td><button class="btn btn-info">'. $pseudoUtilisateur .'</button></td>
                    <td><button class="btn btn-danger">'. round(($depense->getMontant() / $nbPartTotale) * $repartition->getNbPart(),2) .'€</button></td>
                    <td><h3><i class="far fa-arrow-alt-circle-right"></i></h3></td>
                    <td><button class="btn btn-info">'. $pseudoAffichage .'</button></td>
                </tr>
            
                ';
            }
        }

echo '
        </table>
    </div>
</div>

';