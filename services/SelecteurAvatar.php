<?php
/**
 * Created by IntelliJ IDEA.
 * User: angel
 * Date: 02-08-18
 * Time: 18:13
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

    if (isset($_FILES['imageSelector'])) {
        $utilisateur = new UtilisateurInscrit(null);
        $utilisateur->setPseudoUtilisateur(strtolower($_SESSION['pseudoUtilisateur']));
        $utilisateur->setEmail($_SESSION['email']);
        $utilisateur->setPassword($_SESSION['password']);
        $utilisateur->setSel($_SESSION['sel']);
        $utilisateur->setImg($_SESSION['img']);


        $infosImage = pathinfo($_FILES['imageSelector']['name']);
        $extensionImage = $infosImage['extension'];

        $extensionsArray = array('png', 'jpg', 'jpeg', 'gif');

        if (in_array($extensionImage, $extensionsArray)) {
            $nameImage = "" . substr(time() * rand(), 3, 6) . "." . $extensionImage;
            move_uploaded_file($_FILES['imageSelector']['tmp_name'], '../uploads/' . $nameImage);

            /** @var UtilisateurInscrit $utilisateur */
            $utilisateur->setImg($nameImage);
            $_SESSION['img'] = $utilisateur->getImg();

            $dbManager = new DBManager();
            $db = $dbManager->connect();

            $utilisateurInscritManager = new UtilisateurInscritManager($db);

            if ($utilisateurInscritManager->modifierUtilisateurInscrit($utilisateur)) {
                header('Location: ../view/profil.php?successImg=1&img=1');
                return true;
            } else {
                header('Location: ../view/profil.php?errorImg=1&sql=1');
                return false;
            }
        } else {
            header('Location: ../view/profil.php?errorImg=1&extension=1');
        }
    }
} catch (Exception $e) {
    die($e->getMessage());
} finally {
    if ($prep != null) {
        $dbManager->disconnect();
    }
}