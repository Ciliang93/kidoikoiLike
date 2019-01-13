<?php
session_start();
if (!(isset($_SESSION['connect']) && $_SESSION['connect'] == 1)) {
    header('Location: ../index.php');
}

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/UtilisateurInscritManager.php';

$dbManager = new DBManager();
$db = $dbManager->connect();
$utilisateurInscritManager = new UtilisateurInscritManager($db);

$utilisateurs = $utilisateurInscritManager->recupererTousLesPseudos();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
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
                    <a class="nav-link active" id="newEvent" href="creationEvenement.php">Nouveau
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
    <h1 class="h1">Création d'un nouvel évènement</h1>
    <br>
    <form action="../services/ajouterEvenement.php" id="formNewEvent" method="post">
        <div class="form-group">
            <label for="titreNewEvent" class="h4">Titre</label>
            <input type="text" class="form-control" id="titreNewEvent" name="titreNewEvent">
        </div>
        <div class="form-group">
            <label for="descriptionNewEvent" class="h4">Description</label>
            <textarea name="descriptionNewEvent" id="descriptionNewEvent" rows="7" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <input type="submit" id="submit" name="submit" value="Créer" class="btn btn-primary">
        </div>
    </form>
</div>


<script src="../js/JQuery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/fontawesome-all.js"></script>
<script>
    $(document).ready(function () {

        //à l'envoi du formulaire, on vérifie si le titre est setté. Si oui, on envoi, sinon, alerte affichée
        $("#submit").on('click', function (e) {
            if ($("#titreNewEvent").val() == "" || $("#titreNewEvent").val() == null) {
                e.preventDefault();
                $('.alert-danger').remove();
                $("form").before('<div class="alert alert-danger">Veuillez renseigner un titre</div>');
                $(selected).show();
            }
        });
    });
</script>
</body>
</html>