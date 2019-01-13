<?php
session_start();
if (!(isset($_SESSION['connect']) && $_SESSION['connect'] == 1)) {
    header('Location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion du profil</title>
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
                    <a class="nav-link active" id="profileManagement" href="profil.php">Profil</a>
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

<?php
/** @var UtilisateurInscrit $utilisateurSession */

echo '
<div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
    <h1 class="h1">Gérer votre profil</h1>
    <div class="container">
       <fieldset>
                <legend>Gestion identifiants</legend>';


if (isset($_GET['errorMdp'])) {
    if (isset($_GET['currentMatch'])) {
        echo '<p class="text-danger">Le mot de passe actuel est incorrect.</p>';
    }
    if (isset($_GET['emptyNew'])) {
        echo '<p class="text-danger">Pas de nouveau mot de passe renseigné.</p>';
    }
    if (isset($_GET['newMatch'])) {
        echo '<p class="text-danger">Les deux nouveaux mots de passe ne correspondent pas.</p>';
    }
} else if (isset($_GET['successMdp'])) {
    echo '<p class="text-success">Modification du mot de passe réussie.</p>';
}


echo '<form action="../services/ModificationMdp.php" id="formModifMdpProfil" method="post">
                <div class="form-row">
                    <label class="col" for="pseudoProfile">Pseudo</label>
                    <input class="form-control col" type="text" name="pseudoProfile" id="pseudoProfile"
                           value="' . $_SESSION['pseudoUtilisateur'] . '" disabled>
                </div>
                <div class="form-row">
                    <label for="mdpCurrent" class="col">Mot de passe actuel</label>
                    <input class="form-control col" type="password" name="mdpCurrent" id="mdpCurrent">
                </div>
                <div class="form-row">
                    <label for="mdpNew" class="col">Nouveau mot de passe</label>
                    <input class="form-control col" type="password" name="mdpNew" id="mdpNew">
                </div>
                <div class="form-row">
                    <label for="mdpNewVerif" class="col">Confirmer nouveau mot de passe</label>
                    <input class="form-control col" type="password" name="mdpNewVerif" id="mdpNewVerif">
                </div>
                <br>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Sauver">
                </div>
                <br>
        </form>
        </fieldset>';
?>

<?php
echo ' <fieldset>
            <legend>Gestion email</legend>';

if (isset($_GET['errorMail'])) {
    if (isset($_GET['same'])) {
        if ($_GET['same'] == 1) {
            echo '<p class="text-danger">La nouvelle adresse mail est la même que l\'ancienne.</p>';
        } else if ($_GET['same'] == 0) {
            echo '<p class="text-danger">Les nouveaux mails ne sont pas identiques</p>';
        }
    }
} else if (isset($_GET['successMail'])) {
    echo '<p class="text-success">Modification de l\'adresse mail réussie !</p>';
}

echo '
        <form action="../services/ModificationEmail.php" method="post" id="formModifEmailProfil">
                <div class="form-row">
                    <label for="mailCurrent" class="col">Adresse email actuelle</label>
                    <input class="form-control col" type="email" name="mailCurrent" id="mailCurrent"
                           value="' . $_SESSION['email'] . '" disabled>
                </div>
                <div class="form-row">
                    <label for="mailNew" class="col">Nouvelle adresse email</label>
                    <input class="form-control col" type="email" name="mailNew" id="mailNew">
                </div>
                <div class="form-row">
                    <label for="mailVerif" class="col">Confirmer adresse email</label>
                    <input class="form-control col" type="email" name="mailVerif" id="mailVerif">
                </div>
                <br>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Sauver">
                </div>
                <br>
        </form>
        </fieldset>
    </div>';
?>


<?php
echo '<div class="container">
        <form action="../services/SelecteurAvatar.php" method="post" enctype="multipart/form-data" id="formModifAvatarProfil">
            <fieldset>
                <legend>Personnalisation</legend>';


//Affichage pour la sécurité php si jQuery ne fonctionne pas
if (isset($_GET['errorImg'])) {
    if (isset($_GET['sql'])) {
        echo '<p class="text-danger">La modification de l\'avatar a échoué. (SQLERROR)</p>';
    } else if (isset($_GET['extension'])) {
        echo '<p class="text-danger">Le format du fichier n\'est pas autorisé</p>';

    }
} else if (isset($_GET['successImg'])) {
    if (isset($_GET['img'])) {
        echo '<p class="text-success">Modification de l\'avatar réussie.</p>';
    }
}

echo '<div class="form-row">
            <div class="col">
                    <label for="avatarCurrent" id="labelAvatarCurrent" class="col">Avatar actuel</label>
                    <img id="avatarCurrent" class="img-thumbnail" src="../uploads/' . $_SESSION['img'] . '"
                         alt="avatar ' . $_SESSION['pseudoUtilisateur'] . '"><br>
                    <input type="file" class="form-control-file col" name="imageSelector" id="imageSelector" placeholder="Nouvel avatar" required>
            </div>
     </div><br><br>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Sauver">
                </div>
            </fieldset>
        </form>
    </div>';
?>




<script src="../js/JQuery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/fontawesome-all.js"></script>
<script src="../js/modificationMdpProfil.js"></script>
<script src="../js/modificationEmailProfil.js"></script>
</body>
</html>