<?php

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/UtilisateurInscritManager.php';
require_once '../model/PO.php';
require_once '../model/Utilisateur.php';
require_once '../model/UtilisateurInscrit.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dbManager = null;
$db = null;
$utilisateurInscritManager = null;
$prep = null;

try {
    if (!(empty($_POST['pseudoConnexion']) || $_POST['pseudoConnexion'] == null)
        && !(empty($_POST['mdpConnexion']) || $_POST['mdpConnexion'] == null)) {
        $dbManager = new DBManager();
        $db = $dbManager->connect();
        $utilisateurInscritManager = new UtilisateurInscritManager($db);

        //Nettoyage des caract?res sp?ciaux et des injections puis forcing en minuscule dans le pseudo
        $pseudo = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(htmlspecialchars($_POST['pseudoConnexion'])));

        $utilisateur = new UtilisateurInscrit(null);
        $utilisateur->setPseudoUtilisateur($pseudo);

        $utilisateur = $utilisateurInscritManager->recupererUtilisateurInscrit($utilisateur); //Hydratation

        if($utilisateur){
            $sel = $utilisateur->getSel();

            $mdp = substr(sha1($_POST['mdpConnexion'] . $sel), 0, 10);

            if (!strcmp($mdp, $utilisateur->getPassword())) { //Si les mots de passe chiffr?s correspondent
                $_SESSION['pseudoUtilisateur'] = $_POST['pseudoConnexion'];
                $_SESSION['email'] = $utilisateur->getEmail();
                $_SESSION['password'] = $utilisateur->getPassword();
                $_SESSION['sel'] = $utilisateur->getSel();
                $_SESSION['img'] = $utilisateur->getImg();
                $_SESSION['connect'] = 1;
                header('Location: ../view/accueil.php');
            } else {
                header('Location: ../index.php?conerror=true');
            }
        } else {
            header('Location: ../index.php?conerror=true');
        }
    } else if($_SESSION['recupmdp'] == true) {
        $dbManager = new DBManager();
        $db = $dbManager->connect();
        $utilisateurInscritManager = new UtilisateurInscritManager($db);

        $utilisateur = new UtilisateurInscrit(null);
        $utilisateur->setPseudoUtilisateur($_SESSION['pseudoUtilisateur']);
        $utilisateur = $utilisateurInscritManager->recupererUtilisateurInscrit($utilisateur);

        $_SESSION['email'] = $utilisateur->getEmail();
        $_SESSION['password'] = $utilisateur->getPassword();
        $_SESSION['sel'] = $utilisateur->getSel();
        $_SESSION['img'] = $utilisateur->getImg();
        header('Location: ../view/ModificationMdpView.php');

    } else {
            header('Location ../index.php?conerror=true');
    }
} catch (Exception $e) {
    die($e->getMessage());
} finally {
    if ($prep != null) {
        $dbManager->disconnect();
    }
}