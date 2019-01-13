<?php
session_start();
if (!(isset($_SESSION['connect']) && $_SESSION['connect'] == 1)) {
    header('Location: ../index.php');
}

require_once '../model/PO.php';
require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/EvenementManager.php';
require_once '../model/Evenement.php';
?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index Kidoikoi</title>
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
                    <?php echo '
                    <a class="nav-link disabled"><i class="far fa-user"></i>'.$_SESSION["pseudoUtilisateur"].'</a>
                    ';?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../services/Deconnexion.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
    <h3 class="h3">Vos évènements actifs</h3>
    <div class="row">
    <?php
      $dbManager = new DBManager();
      $db = $dbManager->connect();
      $eventManager = new EvenementManager($db);
      $result = $eventManager->recupEvenementsLiesActifs($_SESSION['pseudoUtilisateur']);
      $auMoinsUnEvent = false;
      if($result){
          foreach ($result as $key => $row){
              if($row['actif']){
                  $auMoinsUnEvent = true;
                  echo  '

                        <div class="card col-sm-5 col-md-3 col-lg-3 col-xl-3">
                            <img class="card-img-top imageEvents" src="../uploads/defaultEvent.jpg" alt="img card">
                            <div class="card-body">
                                <h5 class="card-title">'.$row['nom'].'</h5>
                                <p class="card-text">'.$row['description'].'</p>
                                <form action="afficherEvenement.php?evt='. $row['idEvenement'] .'" method="POST">
                                    <input type="hidden" name="archived" value="false">
                                    <input type="submit" class="btn btn-primary" value="Détails">
                                </form>
                            </div>
                        </div>
                        
                  ';
              }

          }
      }

      if(!$auMoinsUnEvent){
          echo '
                <div class="container">
                    <h5 class="text-info">Vous ne participez à aucun Kidoikoi</h5>
                </div>
          ';
      }



    ?>

    </div>
</div>


<!-- Scripts utilisés par bootstrap et awesomefont-->

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
</body>
</html>