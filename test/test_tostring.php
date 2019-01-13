<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 09/08/2018
 * Time: 17:16
 */

echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test toString</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!--Link css bootstrap-->
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-grid.css">
    <!--fontawesome sert à ajouter des petites icones-->
    <link rel="stylesheet" href="../css/fontawesome-all.css">
</head>
';

require_once '../model/PO.php';
require_once '../model/Evenement.php';
require_once '../model/Depense.php';
require_once '../model/Utilisateur.php';
require_once '../model/UtilisateurInscrit.php';

$evenement = new Evenement(null);
$evenement->setIdEvenement(50);
$evenement->setDescription('Ceci est un test');
$evenement->setNom('Le test');
$evenement->setActif(true);

echo $evenement->toString();

$depense = new Depense(null);
$depense->setIdDepense(1);
$depense->setTitre('une depense');
$depense->setDescription('c\'était cher');
$depense->setMontant(200.0);
$depense->setIdEvenement(1);

echo $depense->toString();

$utilisateurInscrit = new UtilisateurInscrit(null);
$utilisateurInscrit->setPseudoUtilisateur('testpseudo');
$utilisateurInscrit->setPassword('oulala');
$utilisateurInscrit->setSel('cocoestsalé');
$utilisateurInscrit->setEmail('coco@lesale.com');
$utilisateurInscrit->setImg('saaal.png');

echo $utilisateurInscrit->toString();

echo '
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script src="js/fontawesome-all.js"></script>
    
';