<?php

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

try {
    if (    !(empty($_POST['pseudoInscription']) || $_POST['pseudoInscription'] == null)
         && !(empty($_POST['mailInscription']) || $_POST['mailInscription'] == null)
         && !(empty($_POST['mdpInscriptionA']) || $_POST['mdpInscriptionA'] == null)
         && !(empty($_POST['mdpInscriptionB']) || $_POST['mdpInscriptionB'] == null) ) {

        $returncode = confirmerMdp($_POST['mdpInscriptionA'], $_POST['mdpInscriptionB']);

        switch ($returncode){
            case 1:
                echo '<p class="text-danger">Veuillez remplir tous les champs</p>';
                break;

            case 2:
                echo '<p class="text-danger">Les deux mots de passe ne correspondent pas';
                break;

            case 3:
                echo '<p class="text-danger">Il y a eu un probleme</p>';
                break;

            case 0:
                $dbManager = new DBManager();
                $db = $dbManager->connect();
                $utilisateurInscritManager = new UtilisateurInscritManager($db);

                //création du sel
                $sel = substr(time() * rand(10,1000), 3, 5);

                //chiffrement du mdp
                $mdp = substr(sha1($_POST['mdpInscriptionA'] . $sel), 0, 10);

                //Nettoyage des caractères spéciaux et des injections puis forcing en minuscule dans le pseudo
                $pseudo = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(htmlspecialchars($_POST['pseudoInscription'])));

                //Création d'un nouvel utilisateur
                $utilisateur = new UtilisateurInscrit(null);
                $utilisateur->setPseudoUtilisateur($pseudo);
                $utilisateur->setEmail(htmlspecialchars($_POST['mailInscription'])); //Les adresse emails sont case sensitive
                $utilisateur->setPassword($mdp);
                $utilisateur->setSel($sel);

                /** @var boolean $verifUserExistant */
                $verifUserExistant = $utilisateurInscritManager->chercherUtilisateur($utilisateur);
                if(!$verifUserExistant) {
                    $utilisateurInscritManager->ajouterUtilisateur($utilisateur);
                    header('Location: ../index.php?inscription=true');
                } else {
                    header('Location: ../index.php?erreur=true');
                }
        }
    } else {
        header('Location: ../index.php?erreur=true');
    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
} finally {
    if($db != null){
        $dbManager->disconnect();
        $db = null;
    }
}