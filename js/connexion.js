function validerFormulaireConnexion(){
    var pseudo = document.getElementById('pseudoConnexion').value;

    //Verification de la taille du pseudo
    if (pseudo.length < 8 || pseudo.length > 20 ){
        document.getElementById('mdpConnexion').style.borderColor ="#ced4da";
        document.getElementById('response').innerHTML = "Pseudo invalide";
        document.getElementById('pseudoConnexion').style.borderColor ="red";
        return false;
    }

    //Verification du mot de passe
    if(document.getElementById('mdpConnexion').value.length < 1) {
        document.getElementById('pseudoConnexion').style.borderColor = "#ced4da";
        document.getElementById('response').innerHTML = "Veuillez saisir un mot de passe";
        document.getElementById('mdpConnexion').style.borderColor = "red";
        return false;
    }
}
