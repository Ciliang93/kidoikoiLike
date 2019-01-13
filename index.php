<?php
//comment
session_start();
if(isset($_SESSION['connect']) && $_SESSION['connect'] == 1){
    header('Location: ./view/accueil.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page de connexion / inscription</title>
    <link rel="stylesheet" href="css/styles.css">
    <!--Link css bootstrap-->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <!--fontawesome sert à ajouter des petites icones-->
    <link rel="stylesheet" href="css/fontawesome-all.css">
</head>
<body class="bodyPages">
<?php

if (isset($_GET['erreur'])) { //Message d'erreur
    if(isset($_GET['message'])){
        $message = $_GET['message'];
        echo '
            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
                <h3 id="messageBienvenue">Oops, '. $message .'</h3>
            </div>
        ';
        header('refresh: 2;url=http://localhost/KidoiKoi/index.php');
    } else {
        echo '
            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
                <h3 id="messageBienvenue">Oops, il y a eu un problème</h3>
            </div>
        ';
        header('refresh: 2;url=http://localhost/KidoiKoi/index.php');
    }
} else if (isset($_GET['conerror'])) {
    echo '
            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
                <h3 id="messageBienvenue">Identifiants incorrects</h3>
            </div>
        ';
    header('refresh: 2;url=http://localhost/KidoiKoi/index.php');
} else if (isset($_GET['inscription'])) {
    echo '
            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
                <h3 id="messageBienvenue">Inscription réussie !</h3>
            </div>
        ';
    header('refresh: 2;url=http://localhost/KidoiKoi/index.php');
} else if(isset($_GET['mail'])) {
    echo '
            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
                <h3 id="messageBienvenue">Email de recuperation envoyé!</h3>
            </div>
        ';
    header('refresh: 2;url=http://localhost/KidoiKoi/index.php');
} else if(isset($_GET['expired'])){
    echo '
            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
                <h3 id="messageBienvenue">Lien expiré</h3>
            </div>
        ';
    header('refresh: 1;url=http://localhost/KidoiKoi/index.php');
} else { // Le formulaire de connexion qui s'affiche directement quand on arrive sur le site
        echo '
            <div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
                <h3 id="messageBienvenue">Bienvenue sur KiDoiKoi</h3>
                <h5>Veuillez entrer vos informations de connexion</h5>
                <form name="connect" method="post" action="services/Connexion.php" onsubmit="return validerFormulaireConnexion()">
                    <div class="form-group">
                        <input type="text" class="form-control" id="pseudoConnexion" name="pseudoConnexion" placeholder="Pseudo">
                        <input type="password" class="form-control" id="mdpConnexion" name="mdpConnexion" placeholder="Mot de passe">
                        <div id="response" class="text-danger"></div>
                    </div>
                    <div class="form-group" id="divConnexion">
                        <input type="submit" id="btnConnexion" class="btn btn-primary" value="Connexion">
                    </div>
                    <div class="form-group" id="appelsModals">
                        <a id="pasEncoreInscrit" href="#" data-toggle="modal" data-target="#modalInscription"><i
                                    class="fas fa-user-plus"></i> Pas encore inscrit ?</a><br>
                        <a id="mdpOublie" href="#" data-toggle="modal" data-target="#modalMdpOublie"><i class="fas fa-redo"></i> Mot de passe oublié ?</a>
                    </div>
                </form>
            </div>
        ';
}
?>

<!--Le modal qui s'affiche au clic sur pas encore inscrit-->
<form name="pasEncoreInscrit" method="post" action="services/Inscription.php" onsubmit="return validerFormulaireInscription()">
    <div class="modal fade" id="modalInscription">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Formulaire d'inscription</h4>
                    <button type="button" class="close" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <input type="text" class="form-control col-xs-9 col-sm-5 col-md-5 col-lg-5"
                               id="pseudoInscription" name="pseudoInscription" placeholder="Pseudo">
                        <p class="text-info">Remarque : Votre pseudo servira d'identifiant dans les différents
                            évênements créés.</p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control col-md-5" id="mdpInscriptionA" name="mdpInscriptionA" placeholder="Mot de passe">
                        <input type="password" class="form-control col-md-5" id="mdpInscriptionB" name="mdpInscriptionB" placeholder="Confirmez le mot de passe">

                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control col-md-9" id="mailInscription" name="mailInscription"
                               placeholder="Email">
                        <p class="text-danger" id="reponseInscription"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div>
                        <input type="submit" class="btn btn-primary">
                        <button type="reset" class="btn" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!--Le modal qui s'affiche quand on clique sur mot de passe oublié-->
<form name="mdpoublier" action="services/RecuperationMdp.php" method="post" onsubmit="return validerFormulaireRecupEmail()">
    <div class="modal fade" id="modalMdpOublie">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Demande de nouveau mot de passe</h4>
                </div>
                <div class="modal-body" id="divBodyRecupMdp">
                    <input type="text" class="form-control col-md-9" id="pseudoRecupMdp" name="pseudoRecupMdp"
                           placeholder="Pseudo">
                    <input type="email" class="form-control col-md-9" id="mailRecupMdp" name="mailRecupMdp"
                           placeholder="Email">
                    <p class="text-danger" id="responseMailRecupMdp"></p>
                </div>
                <div class="modal-footer">
                    <div>
                        <input type="submit" class="btn btn-primary">
                        <button type="button" class="btn" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!--Script d'envoi du mail pour la récupération du mot de passe à écrire -->
<?php /**
 * try {
 *
 * $db4 = new PDO('mysql:host=localhost;dbname=kidoikoi;charset=utf8', 'root', '');
 * if (isset($_POST['mailRecupMdp'])) {
 * $mailRecup = $_POST['mailRecupMdp'];
 * $query3 = $db4->prepare('SELECT * FROM utilisateurinscrit WHERE email = ?');
 * $query3->execute(array($mailRecup));
 * $query3->fetch();
 *
 * if ($query3->rowCount() < 1) {
 * echo '<script>console.log("Cette adresse ne correspond à aucun user")</script>';
 * } else {
 * //envoi du mail à l'adresse renseignée
 * }
 * $query3->closeCursor();
 * }
 * } catch (PDOException $e) {
 * die("Erreur :" . $e->getMessage());
 * }
 * $db4 = null; */
?>

<!-- Scripts perso -->
<script type="text/javascript" src="js/connexion.js"></script>
<script type="text/javascript" src="js/inscription.js"></script>
<script type="text/javascript" src="js/recupEmail.js"></script>

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
<script src="js/fontawesome-all.js"></script>
</body>
</html>