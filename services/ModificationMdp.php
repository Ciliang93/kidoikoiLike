<?php
/**
 * Created by IntelliJ IDEA.
 * User: angel
 * Date: 03-08-18
 * Time: 14:04
 */

session_start();

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/UtilisateurInscritManager.php';
require_once '../model/PO.php';
require_once '../model/Utilisateur.php';
require_once '../model/UtilisateurInscrit.php';
require_once 'FonctionsDiverses.php';

$dbManager = null;
$db = null;
$utilisateurInscritManager = null;
$prep = null;


try{
    if ( (isset($_POST['mdpCurrent']) && isset($_POST['mdpNew']) && isset($_POST['mdpNewVerif'])) ||
        (isset($_POST['nouveauMdpA']) && isset($_POST['nouveauMdpB'])) ) { //On verifie qu'on vient d'un formulaire correct

        $nouveauMdpA = null;
        $nouveauMdpB = null;
        $ancienMdp = null;

        if(isset($_POST['mdpCurrent']) && isset($_POST['mdpNew']) && isset($_POST['mdpNewVerif'])){ //Si on vient du profil
            $nouveauMdpA = $_POST['mdpNew'];
            $nouveauMdpB = $_POST['mdpNewVerif'];
            $ancienMdp = $_POST['mdpCurrent'];
        } else { //Si on vient de l'email de récupération
            $nouveauMdpA = $_POST['nouveauMdpA'];
            $nouveauMdpB = $_POST['nouveauMdpB'];
        }

        $dbManager = new DBManager();
        $db = $dbManager->connect();

        /** @var UtilisateurInscrit $utilisateur */
        $utilisateur = new UtilisateurInscrit(null);
        $utilisateurInscritManager = new UtilisateurInscritManager($db);

        $utilisateur->setPseudoUtilisateur(strtolower($_SESSION['pseudoUtilisateur']));

        $utilisateur = $utilisateurInscritManager->recupererUtilisateurInscrit($utilisateur);

        if($ancienMdp == null){ //Si on vient de l'email de récupération
            modifierLeMotDePasse($utilisateur, $nouveauMdpA);
            session_destroy();
            header('Location: ../index.php');
        } else { //Si on vient du profil
            $mdp = $_SESSION['password'];
            $sel = $utilisateur->getSel();
            if($mdp == substr(sha1($_POST['mdpCurrent'] . $sel), 0, 10)){
                $returncode = confirmerMdp($nouveauMdpA, $nouveauMdpB);
                if($returncode == 0){
                    modifierLeMotDePasse($utilisateur, $nouveauMdpA);
                    header('Location: ../view/profil.php?successMdp=1');
                } else if($returncode == 1){
                    echo 'Le nouveau mot de passe est vide';
                    header('Location: ../view/profil.php?errorMdp=1&emptyNew=1');
                } else if($returncode == 2){
                    echo 'Les deux mdp ne correspondent pas';
                    header('Location: ../view/profil.php?errorMdp=1&newMatch=0');
                } else {
                    echo 'oops';
                }
            } else {

                header('Location: ../view/profil.php?errorMdp=1&currentMatch=0');
                echo 'Mot de passe actuel incorrect';

            }
        }
    }
}catch (Exception $e){
    die($e->getMessage());
}finally{
    if($prep != null){
        $dbManager->disconnect();
    }
}

/**
 * @param UtilisateurInscrit $utilisateur
 * @param String $nouveauMdp
 */
function modifierLeMotDePasse($utilisateur, $nouveauMdp){
    $dbManager = new DBManager();
    $db = $dbManager->connect();
    $utilisateurInscritManager = new UtilisateurInscritManager($db);

    $utilisateur->setSel(substr(time() * rand(10,1000), 3, 5));
    $utilisateur->setPassword(substr(sha1($nouveauMdp . $utilisateur->getSel()), 0, 10));

    $_SESSION['sel'] = $utilisateur->getSel();
    $_SESSION['password'] = $utilisateur->getPassword();

    $utilisateurInscritManager->modifierUtilisateurInscrit($utilisateur);
}

