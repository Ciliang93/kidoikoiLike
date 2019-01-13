<?php
/**
 * Created by IntelliJ IDEA.
 * User: angel
 * Date: 03-08-18
 * Time: 14:30
 */

session_start();

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/UtilisateurInscritManager.php';
require_once '../model/PO.php';
require_once '../model/Utilisateur.php';
require_once '../model/UtilisateurInscrit.php';

$dbManager = null;
$db = null;
$utilisateurInscritManager = null;
$prep = null;

try {
    $dbManager = new DBManager();
    $db = $dbManager->connect();

    /** @var UtilisateurInscrit $utilisateur */
    $utilisateur = new UtilisateurInscrit(null);
    $utilisateurInscritManager = new UtilisateurInscritManager($db);

    $utilisateur->setPseudoUtilisateur(strtolower($_SESSION['pseudoUtilisateur']));

    $utilisateur = $utilisateurInscritManager->recupererUtilisateurInscrit($utilisateur);


    if (isset($_POST['mailNew']) && isset($_POST['mailVerif'])) {
        if ($_POST['mailNew'] == $_POST['mailVerif']) {
            if ($_POST['mailNew'] == $_SESSION['email']) {
                header('Location: ../view/profil.php?errorMail=1&same=1');
                return false;
            } else {
                $utilisateur->setEmail($_POST['mailNew']);
                $_SESSION['email'] = $_POST['mailNew'];
                $utilisateurInscritManager->modifierUtilisateurInscrit($utilisateur);
                header('Location: ../view/profil.php?successMail=1');
                return true;
            }
        } else {
            header('Location: ../view/profil.php?errorMail=1&same=0');
            return false;
        }
    } else {
        header('Location: ../view/profil.php?errorMail=1&bothSet=0');
        echo 'Il manque un des nouveaux mails';

        return false;
    }

} catch (Exception $e) {
    die($e->getMessage());
} finally {
    if ($prep != null) {
        $dbManager->disconnect();
    }
}