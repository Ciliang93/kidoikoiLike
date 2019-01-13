<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 30/08/2018
 * Time: 20:40
 */

require('../fpdf/fpdf.php');

$tableauAvanceurs = array('nothingeni','junior');
$tableauEndettes = array('bernardo','ben','max','tekxy','coco');
$tableauMontants = array(
    'nothingeni' => 2000,
    'junior' => 700,
    'bernardo' => -200,
    'ben' => -250,
    'max' => -1250,
    'tekxy' => -500,
    'coco' => -500,
);
/**
var_dump($tableauAvanceurs);
var_dump($tableauEndettes);
var_dump($tableauMontants);

$retour = isZero($tableauMontants);
var_dump($retour); */

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('courier','B','40');
$pdf->Cell(190,20,'Kidoikoi',0,1,'C');
$pdf->SetFont('courier', '','18');

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