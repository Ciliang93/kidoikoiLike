<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 03/08/2018
 * Time: 00:21
 */

require_once '../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/UtilisateurInscritManager.php';
require_once '../model/PO.php';
require_once '../model/Utilisateur.php';
require_once '../model/UtilisateurInscrit.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$dbManager = null;
$db = null;
$utilisateurInscritManager = null;
$prep = null;
try{
    if(isset($_POST['mailRecupMdp']) && isset($_POST['pseudoRecupMdp']) && !isset($_SESSION['emailenvoye'])) {
        $email = $_POST['mailRecupMdp'];
        $pseudo = strtolower(htmlspecialchars($_POST['pseudoRecupMdp']));
        if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $pseudo) ) { //Si le pseudo ne contient pas de caractères spéciaux
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) { //Renvoie vrai si l'adresse email est correcte
                $dbManager = new DBManager();
                $db = $dbManager->connect();
                $utilisateurInscritManager = new UtilisateurInscritManager($db);

                $utilisateurInscrit = new UtilisateurInscrit(null);
                $utilisateurInscrit->setPseudoUtilisateur($pseudo);

                $utilisateurInscrit = $utilisateurInscritManager->recupererUtilisateurInscrit($utilisateurInscrit);

                if ($utilisateurInscrit) {
                    if( $utilisateurInscrit->getEmail() == $email ) { //Si l'email entrée correspond a celle en db

                        $mail = new PHPMailer(true);

                        //Configuration du serveur mail (Gmail)
                        $mail->SMTPDebug = 0;
                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->Port = 587;
                        $mail->SMTPSecure = 'tls';
                        $mail->SMTPAuth = true;
                        $mail->Username = "kidoikoihelha@gmail.com";
                        $mail->Password = "qsdAZE123";

                        //Adresses
                        $mail->setFrom('kidoikoihelha@gmail.com');
                        $mail->addAddress($utilisateurInscrit->getEmail());

                        //Contenu
                        $random = substr(time() * rand(10,1000), 3, 5);
                        $_SESSION['random'] = $random;
                        $mail->isHTML(true);
                        $mail->Subject = "Recuperation de votre mot de passe";
                        $mail->Body ='
                            <div>
                                <p>Cet email vous permettra de reinitialiser votre mot de passe.
                                Si vous etes a l\'origine de cette demande, <a href="http://localhost/kidoikoi/services/RecuperationMdp.php?random='. $random .'&pseudo='. $utilisateurInscrit->getPseudoUtilisateur() .'">cliquez ici</a></p>
                                
                            </div>
                        ';
                        $_SESSION['emailenvoye'] = true;
                        $mail->send();
                        header('Location: ../index.php?mail=true');

                    } else {
                        afficherMessageErreur("email incorrect");
                    }

                } else {

                    afficherMessageErreur("utilisateurintrouvable");
                }

            } else {

                afficherMessageErreur("emailinvalide");
            }

        } else {

            afficherMessageErreur("pseudoinvalide");

        }

    } else {

        if( isset($_SESSION['random']) && isset($_GET['random']) && $_SESSION['random'] == $_GET['random']) {
            $_SESSION['recupmdp'] = true;
            $_SESSION['pseudoUtilisateur'] = $_GET['pseudo'];
            header('Location: ./connexion.php');
        } else {
            header('Location: ../index.php?expired=false');
        }
    }
} catch (Exception $e) {
    die( $e->getMessage() );
} finally {
    if($prep != null){
        $dbManager->disconnect();
    }

}

function afficherMessageErreur($message){
    echo'<script>alert("'.$message.'");</script>';
    //header('Location: ../index.php?erreur=true&message='.$message);
}