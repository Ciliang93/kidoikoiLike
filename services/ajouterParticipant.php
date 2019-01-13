<?php
/**
 * Created by IntelliJ IDEA.
 * User: angel
 * Date: 09-08-18
 * Time: 14:45
 */


require_once '../model/PO.php';
require_once '../model/Participe.php';

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/ParticipeManager.php';


$dbManager = null;
$db = null;

$participeManager = null;
$participe = null;


$success = true;

try {
    if (!empty($_POST)) {
        $dbManager = new DBManager();
        $db = $dbManager->connect();
        $participeManager = new ParticipeManager($db);


        $selectedString = $_POST['selectedValues'];
        $selectedArray = explode('/', $selectedString);
        $numEvent = $_POST['numEvent'];


        foreach ($selectedArray as $newParticipant) {

            $participe = new Participe(null);
            $participe->setIdEvenement($numEvent);
            $participe->setOrganise(false);

            if (!substr_compare($newParticipant, 'UT', 0, 2)) { //Si l'utilisateur est temporaire

                require_once '../manager/UtilisateurTemporaireManager.php';
                require_once '../model/UtilisateurTemporaire.php';

                $utilisateurTempManager = new UtilisateurTemporaireManager($db);
                $utilisateurTemp = new UtilisateurTemporaire(null);
                $utilisateurTemp->setPrenom( str_replace('UT', '', $newParticipant) );
                $utilisateurTemp = $utilisateurTempManager->ajouterUtilisateurTemporaire($utilisateurTemp);
                $participe->setPseudoUtilisateur($utilisateurTemp->getPseudoUtilisateur());
            } else {
                $participe->setPseudoUtilisateur($newParticipant);
            }

            if (!$participeManager->sauverParticipe($participe)) {
                $success = false;
            }
        }

        if ($success) {
            header('Location: ../view/afficherEvenement.php?success=1&evt='.$numEvent);
        } else {
            header('Location: ../view/afficherEvenement.php?error=1');
        }

    }


} catch (Exception $e) {
    die($e->getMessage());
} finally {

}



