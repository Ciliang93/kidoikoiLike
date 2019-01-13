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
require_once '../model/UtilisateurInscrit.php';
require_once '../model/Repartition.php';
require_once '../model/Depense.php';

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
$utilisateurInscritManager = null;
$utilisateurTemporaireManager = null;
$utilisateurTemporaire = null;

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
';


try {
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div class="alert alert-success">Ajout de participant réussi</div>';
    }
    if (isset($_GET['evt'])) {
        $dbManager = new DBManager();
        $db = $dbManager->connect();
        $participeManager = new ParticipeManager($db);
        $evenementManager = new EvenementManager($db);
        $repartitionManager = new RepartitionManager($db);
        $utilisateurInscritManager = new UtilisateurInscritManager($db);

        $evenement = new Evenement(null);
        $evenement->setIdEvenement($_GET['evt']);
        $organisateur = false;
        $pseudoOrganisateur = '';
        $iconeOrganisateur = '';
        if ($evenementManager->chercherEvenement($evenement)) {
            $evenement = $evenementManager->recupererEvenement($evenement->getIdEvenement());
            $actif = $evenement->getActif();
            if ($participeManager->chercherParticipant($_SESSION['pseudoUtilisateur'], $evenement->getIdEvenement())) {
                echo '
                    <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8" style=" text-align: center">
                        <h1 class="h1">' . $evenement->getNom() . '</h1>
                        <div class="container">
                            <p class="text-info">
                                ' . $evenement->getDescription() . '
                            </p>
                        </div>
                        <h4 id="response">Participants</h4>

                         <div id="listeParticipants" class="row" style="margin: auto;">
                            
                            
                            ';
                $result = $participeManager->recupererParticipants($evenement);
                if ($result) {
                    foreach($result as $row => $attribut){
                        if($attribut['organise']){
                            $pseudoOrganisateur = $attribut['pseudoUtilisateur'];
                        }
                    }
                    if (!strcmp($_SESSION['pseudoUtilisateur'], $pseudoOrganisateur)) {
                        $organisateur = true;
                    }

                    $tableauDePseudosInscrits = [];
                    $tableauDePseudosTemporaires = [];
                    foreach ($result as $key => $row){

                        if(strpos($row['pseudoUtilisateur'], 'UT') !== false){
                           array_push($tableauDePseudosTemporaires, $row['pseudoUtilisateur']);
                        } else {
                            array_push($tableauDePseudosInscrits, $row['pseudoUtilisateur']);
                        }
                    }

                    sort($tableauDePseudosInscrits);
                    $key = array_search($pseudoOrganisateur, $tableauDePseudosInscrits);
                    unset($tableauDePseudosInscrits[$key]);
                    array_unshift($tableauDePseudosInscrits, $pseudoOrganisateur);
                    $tableauDePseudos = array_merge($tableauDePseudosInscrits, $tableauDePseudosTemporaires);

                    foreach ($tableauDePseudos as $pseudo) {
                        $pseudoUtilisateur = $pseudo;
                        if (!strcmp($pseudo, $pseudoOrganisateur)) {
                            $iconeOrganisateur = '<i class="fas fa-crown"></i>';
                        } else if (!strcmp($pseudo, strtolower($_SESSION['pseudoUtilisateur']))) {
                            $iconeOrganisateur = '<i class="far fa-user"></i>';
                        } else {
                            $iconeOrganisateur = '';
                        }
                        echo '<div class="card" style="width: 10rem; margin-top: 1%;margin-bottom: 1%;">
                                   <div class="card-body">         
                        ';
                        if( strpos($pseudo, 'UT') !== false ){
                            $utilisateurTemporaireManager = new UtilisateurTemporaireManager($db);
                            $utilisateurTemporaire = new UtilisateurTemporaire(null);
                            $utilisateurTemporaire->setPseudoUtilisateur($pseudo);
                            $utilisateurTemporaire = $utilisateurTemporaireManager->recupererPrenom($utilisateurTemporaire);
                            $pseudoUtilisateur = $utilisateurTemporaire->getPseudoUtilisateur();
                            echo '<img class="img-thumbnail" src="../uploads/defaultUser.png">';
                            echo $iconeOrganisateur . '<button style="margin-top: 1%; margin-bottom: 1%;" class="btn btn-dark" disabled value="'. $pseudoUtilisateur .'">' . $utilisateurTemporaire->getPrenom() . '</button>';
                        } else {
                            $utilisateurInscrit = new UtilisateurInscrit(null);
                            $utilisateurInscrit->setPseudoUtilisateur($pseudoUtilisateur);
                            $utilisateurInscrit = $utilisateurInscritManager->recupererUtilisateurInscrit($utilisateurInscrit);
                            echo '<img class="img-thumbnail" src="../uploads/'. $utilisateurInscrit->getImg() .'">';
                            echo $iconeOrganisateur . '<button style="margin-top: 1%; margin-bottom: 1%;" class="btn btn-info" disabled>' . $pseudoUtilisateur . '</button>';
                        }

                        $sqlArray = $repartitionManager->recupererRepartitionDuParticipant($pseudoUtilisateur, $evenement->getIdEvenement());
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
                            echo '<h5 class="card-text" style="color: green">+'. round($montantActuel, 2) .' <i class="far fa-money-bill-alt"></i></h5>';
                        } else if($montantActuel < 0) {
                            echo '<h5 class="card-text" style="color: red">'. round($montantActuel, 2) .' <i class="far fa-money-bill-alt"></i></h5>';
                        }

                        echo '</div></div>';
                    }
                }
                echo '</div><br><br>';

                        if($actif){
                            echo '<div style="display: inline-block;padding: auto;" id="divBoutons" >
                                    <button class="btn btn-primary" type="button" name="ajouterParticipant" id="ajouterParticipant"
                                    data-toggle="collapse" data-target="#champAddParticipant" aria-expanded="false" 
                                    aria-controls="champAddParticipant">
                                                                            Ajouter participants
                                    </button>';
                        }

                echo'
                    </div><br><br>
                         <!-- Collapse qui contient le formulaire d\'ajout de participants -->
                         <br><div class="collapse" id="champAddParticipant">
                            <form action="../services/ajouterParticipant.php" id="formAddMembres" method="POST">
                              <div class="form-row">                          
                               <select class="custom-select col-sm-8 col-md-8 col-lg-8" name="selectParticipants" id="selectParticipants">
                                    <option value="none" selected disabled>Selectionnez un participant inscrit</option>';

                $utilisateurInscritManager = new UtilisateurInscritManager($db);
                $arr = $utilisateurInscritManager->recupererTousLesPseudos();
                foreach ($arr as $key => $value) {
                    if (!($value == strtolower($_SESSION['pseudoUtilisateur'])) && !($value == strtolower($pseudoOrganisateur))) {
                        echo '<option value="' . $value . '">' . $value . '</option>';
                    }
                }

                echo '      </select>
                              </div><br>
                        <div class="form-row" id="formAjoutInvite">
                            <input type="text" id="inputInvite" class="form-control col-sm-4 col-md-4 col-lg-4" placeholder="Ou ajoutez un participant invité">
                            <button id="submitInvite" class="btn btn-success">+</button>
                        </div>
                                   <br><br>
                               <div class="alert alert-info" id="selectedParticipants" style="display: none">
                                   <h5>Participants à ajouter</h5>                               
                               </div>
                               <input type="text" id="selectedValues" name="selectedValues" style="display: none">
                               <input type="text" id="numEvent" name="numEvent"  value="' . $_GET["evt"] . '" style="display: none">
                               <input type="submit" class="btn btn-primary" name="validerAjoutParticipant" id="validerAjoutParticipant" value="Ajouter">
                            </form>
                         </div>
                        </div>';

                //Deuxieme container pour afficher les dépenses

                $depenseManager = new DepenseManager($db);
                $tableauDeDepense = $depenseManager->recupererLesDepenses($evenement);
                if(count($tableauDeDepense) > 0){
                    echo '
                        
                        <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8" style=" text-align: center;">
                        <h4>Liste des dépenses</h4><br>
                        <div id="divTable">
                        <table class="table table-active">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Montant</th>    
                                </tr>
                            </thead>
                            <tbody>
                        
                    ';
                } else {
                    echo '
                            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8" style=" text-align: center">
                            <h4>Aucune dépense</h4><br>
                            <div id="divTable">
                         ';
                }

                $depenseManager = new DepenseManager($db);
                $tableauDeDepense = $depenseManager->recupererLesDepenses($evenement);
                foreach ($tableauDeDepense as $row => $attribut){
                    $timeStamp = strtotime($attribut['date']);
                    echo '
                        <tr>
                            <td>'. date('d/m/Y', $timeStamp) .'</td>
                            <td>'. $attribut['titre'] .'</td>
                            <td>'. $attribut['description'] .'</td>
                            <td>'. $attribut['montant'] .'€</td>
                            <td> 
                                <form action="afficherDepense.php" method="POST">
                                    <input type="hidden" name="idDepense" value="'. $attribut['idDepense'] .'">
                                    <input type="hidden" name="idEvent" value="'. $_GET['evt'] .'">
                                    <input type="hidden" name="archived" value="'. $actif .'">
                                    <input type="submit" value="Détails" class="btn btn-info">
                                </form> 
                            </td>
                        </tr>
                    ';
                }

                echo '</tbody>
                        </table>';

                if($actif){
                    echo'       
                        <button class="btn btn-primary" type="button" name="ajouterDepense" id="ajouterDepense"
                            data-toggle="collapse" data-target="#champAddDepense" aria-expanded="false" 
                            aria-controls="champAddDepense">Ajouter une dépense</button><br>
                        ';
                }



                echo'   </div>     
                        <!-- Collapse qui contient le formulaire d\'ajout d\'une dépense -->
                         <br>
                         <div class="collapse" id="champAddDepense">
                            <div id="alertes" class="alert-danger">
                            
                            </div>
                            <form action="../services/ajouterDepense.php?evt='. $_GET['evt'] .'" id="formAddDepense" method="POST">
                                <div style="margin: auto;">
                                    <input type="text" id="titreDepense" name="titreDepense" class="form-control" placeholder="Titre">
                                </div><br>
                                <div class="form-row">
                                    <input type="date" id="dateDepense" name="dateDepense" class="form-control col" placeholder="Date">
                                    <input type="number" min="0.00" max="10000.00" step="0.01" id="montantDepense" name="montantDepense" class="form-control col" placeholder="Montant">
                                </div><br>
                                <div class="form-row">
                                    <textarea name="descriptionDepense" id="descriptionDepense" cols="30" rows="10" class="form-control" placeholder="Description (facultative)"></textarea>
                                </div><br>
                                <div class="form-row">
                                    <select name="participantsDepense" id="participantsDepense" class="custom-select">
                                        <option value="none" selected disabled>Selectionnez un participant</option>';
                $participeManager = new ParticipeManager($db);
                $tab = $participeManager->recupererParticipants($evenement);
                foreach ($tab as $key => $value) {
                    if( strpos($value['pseudoUtilisateur'], 'UT') !== false ){ //Si c'est un utilisateur temporaire
                        $utilisateurTemporaireManager = new UtilisateurTemporaireManager($db);
                        $utilisateurTemporaire = new UtilisateurTemporaire(null);
                        $utilisateurTemporaire->setPseudoUtilisateur($value['pseudoUtilisateur']);
                        $utilisateurTemporaire = $utilisateurTemporaireManager->recupererPrenom($utilisateurTemporaire);

                        echo '<option value="'. $utilisateurTemporaire->getPseudoUtilisateur() .'">' . $utilisateurTemporaire->getPrenom() . '</option>';
                    } else {
                        echo '<option value="' . $value["pseudoUtilisateur"] . '">' . $value["pseudoUtilisateur"] . '</option>';
                    }
                }

                echo '             </select>
                                </div><br> 
                                <div class="alert alert-info" id="selectedDepense" style="display: none">
                                   <h5>Participants de cette dépense</h5>
                                   <table class="table table-info" id="tableauParticipantsDepense">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-7 col-md-7 col-lg-7">Participant</th>
                                                <th class="col-sm-4 col-md-4 col-lg-4">Nombre de part</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                        <select id="selectAvanceur" name="selectAvanceur" class="form-control">
                                                
                                                    <option id="firstOptionAvanceur" value="none" disabled>Selectionnez l\'avanceur</option>
                                                
                                                </select>
                                        
                                    </table>                               
                               </div>
                                <input type="text" id="selectedValuesDepense" name="selectedValuesDepense" style="display: none">
                               <input type="text" id="numEventDepense" name="numEventDepense"  value="' . $_GET["evt"] . '" style="display: none">                           
                                <input type="submit" class="btn btn-primary" name="validerAjoutDepense" value="Ajouter">
                            </form>
                         </div>
                      </div>
                      
                      <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8" style=" text-align: center">
                        <form action="../services/clotureEvenement.php" method="post">
                        <input type="text" name="evt" style="display: none" value="'.$_GET['evt'].'">';

                if ($organisateur && $actif) {
                    echo '<input type="submit" class="btn btn-danger" name="cloreEvenement" id="cloreEvenement" value="Clôture de l\'événement"> 
                        </form>
                        ';
                } else {
                    echo '</form>';
                    echo '<form action="../services/generatePDF.php" method="post">';
                    echo    '<input type="submit" class="btn btn-info" name="printPDF" id="printPDF" value="Format PDF"> ';
                    echo    '<input type="hidden" name="idEvent" value="'. $_GET['evt'] .'">';
                    echo '</form>';
                    echo '<form action="../services/emailPDF.php" method="post">';
                    echo    '<input type="submit" class="btn btn-info" name="emailEvent" id="emailEvent" value="Envoyer la clôture par email"> ';
                    echo '</form>';
                }
                echo '
                      </form>
                      </div>
</body>';

            } else {
                echo 'Vous ne participez pas a cet evenement';
            }

        } else {
            echo 'l\'evenement n\'existe pas';
        }
    } else {
        echo 'oupsie';
    }
} catch (Exception $e) {
    die($e->getMessage());
} finally {
    if ($db) {
        $dbManager->disconnect();
        $db = null;
    }
}