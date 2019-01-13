<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 30/08/2018
 * Time: 21:51
 */

require_once '../model/PO.php';
require_once '../model/Evenement.php';
require_once '../model/Depense.php';
require_once '../model/Repartition.php';
require_once '../model/UtilisateurTemporaire.php';
require_once '../model/UtilisateurInscrit.php';
require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/UtilisateurInscritManager.php';
require_once '../manager/UtilisateurTemporaireManager.php';
require_once '../manager/ParticipeManager.php';
require_once '../manager/RepartitionManager.php';
require_once '../manager/EvenementManager.php';
require_once '../fpdf/fpdf.php';

/***************************************************
 *
 *          Instanciations
 *
 ***************************************************/
$dbManager = new DBManager();
$db = $dbManager->connect();
$participeManager = new ParticipeManager($db);
$utilisateurTemporaireManager = new UtilisateurTemporaireManager($db);
$utilisateurInscritManager = new UtilisateurInscritManager($db);
$repartitionManager = new RepartitionManager($db);
$evenementManager = new EvenementManager($db);
$evt = new Evenement(null);
$evt->setIdEvenement($_POST['idEvent']);
$evt = $evenementManager->recupererEvenement($evt->getIdEvenement());
$tableauAvanceurs = array();
$tableauEndettes = array();
$tableauMontants = array();

/***************************************************
 *
 *          Traitement
 *
 ***************************************************/

$participantsSQL = $participeManager->recupererParticipants($evt);
$tableauDePseudos = [];

foreach($participantsSQL as $key => $row){

    array_push($tableauDePseudos, $row['pseudoUtilisateur']);

}

foreach ($tableauDePseudos as $pseudo) {

    $pseudoAffichage = null;

    if( strpos($pseudo, 'UT') !== false ){

        $utilisateurTemporaire = new UtilisateurTemporaire(null);
        $utilisateurTemporaire->setPseudoUtilisateur($pseudo);
        $utilisateurTemporaire = $utilisateurTemporaireManager->recupererPrenom($utilisateurTemporaire);
        $pseudoAffichage = $utilisateurTemporaire->getPrenom();

    } else {

        $utilisateurInscrit = new UtilisateurInscrit(null);
        $utilisateurInscrit->setPseudoUtilisateur($pseudo);
        $utilisateurInscrit = $utilisateurInscritManager->recupererUtilisateurInscrit($utilisateurInscrit);
        $pseudoAffichage = $pseudo;

    }

    $sqlArray = $repartitionManager->recupererRepartitionDuParticipant($pseudo, $evt->getIdEvenement());
    /**
     * @var $tableauDeRepartitionObject Repartition[]
     * @var $tableauDeDepenseObject Depense[]
     */
    $tableauDeRepartitionObject = [];
    $tableauDeDepenseObject = [];
    foreach ($sqlArray as $key => $value){
        $repartition = new Repartition($value);
        $depense = new Depense($value);

        array_push($tableauDeRepartitionObject, $repartition);
        array_push($tableauDeDepenseObject, $depense);

    }

    $index = 0;
    $montantActuel = 0;
    foreach($tableauDeRepartitionObject as $repartition){
        $repartitonSubTab = $repartitionManager->recupererRepartition($tableauDeDepenseObject[$index]->getIdDepense());
        $nbPartTotale = count($repartitonSubTab);

        if($repartition->getAvance()){ //Si il est avanceur
            $montantActuel += ( $tableauDeDepenseObject[$index]->getMontant() ) - ( $tableauDeDepenseObject[$index]->getMontant() / $nbPartTotale ) * $repartition->getNbPart();
        } else {
            $montantActuel -= ( $tableauDeDepenseObject[$index]->getMontant() / $nbPartTotale ) * $repartition->getNbPart();
        }
        $index ++;
    }

    if($montantActuel > 0){
        //PSEUDO MONTANT DANS LE TABLEAU AVANCEUR
        array_push($tableauAvanceurs, $pseudoAffichage);
        $tableauMontants[$pseudoAffichage] = $montantActuel;

    } else if($montantActuel < 0) {
        //PSEUDO MONTANT DANS LE TABLEAU ENDETTÉ
        array_push($tableauEndettes, $pseudoAffichage);
        $tableauMontants[$pseudoAffichage] = $montantActuel;
    }

}

imprimer($tableauAvanceurs, $tableauEndettes, $tableauMontants, $evt);

/***************************************************
 *
 *          Fonctions
 *
 ***************************************************/

/**
 * @param $tableauAvanceurs array
 * @param $tableauEndettes array
 * @param $tableauMontants array
 * @param $evt Evenement
 */
function imprimer($tableauAvanceurs, $tableauEndettes, $tableauMontants, $evt){
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('courier','B','40');
    $pdf->Cell(190,20,'Kidoikoi',0,1,'C');
    $pdf->SetFont('courier', '','30');
    $pdf->Cell(190, 20, $evt->getNom(), 0, 1, 'C');
    $pdf->SetFont('courier', '','20');

    $boucle = false;
    $i = $j = 0;
    while(!$boucle){
        $montantAvanceur = $tableauMontants[$tableauAvanceurs[$j]];
        $montantEndette = $tableauMontants[$tableauEndettes[$i]];

        if( abs($montantEndette) > abs($montantAvanceur) ){ //L'endetté est-il capable de rembourser plus d'une personne
            $pdf->Cell(190, 20, $tableauEndettes[$i] .' doit '. abs($montantAvanceur) .' euros a '.$tableauAvanceurs[$j], 0, 1, 'L');
            $montantEndette = $montantEndette + $montantAvanceur; // pex: -125 + 50
            $montantAvanceur = 0;
            $tableauMontants[$tableauAvanceurs[$j]] = $montantAvanceur;
            $tableauMontants[$tableauEndettes[$i]] = $montantEndette;

            $j++;
        } else {
            $pdf->Cell(190, 20, $tableauEndettes[$i] .' doit '. abs($montantEndette) .' euros a '.$tableauAvanceurs[$j], 0, 1, 'L');
            $montantAvanceur = $montantAvanceur - abs($montantEndette); //pex 125 - |-50|
            $montantEndette = 0;
            $tableauMontants[$tableauAvanceurs[$j]] = $montantAvanceur;
            $tableauMontants[$tableauEndettes[$i]] = $montantEndette;

            $i++;
        }

        $boucle = isZero($tableauMontants);
    }
    $pdf->Output();
}

/**
 * @param $tableauAssociatif array
 * @return bool
 */
function isZero($tableauAssociatif){ //Renvoie true si les dettes sont purgées (plus aucun montant non nul)
    $retour = true;

    foreach ($tableauAssociatif as $pseudo => $montant) {
        if($montant > 0){
            $retour = false;
        }
    }

    return $retour;
}
