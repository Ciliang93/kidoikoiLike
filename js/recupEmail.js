function validerFormulaireRecupEmail(){
    var domEmail = document.getElementById('mailRecupMdp');
    var email = domEmail.value;
    var domPseudo = document.getElementById('pseudoRecupMdp')
    var pseudo = domPseudo.value;

    //Verification de la taille du pseudo
    if (pseudo.length < 8 || pseudo.length > 20 ){
        domEmail.style.borderColor ="#ced4da";
        document.getElementById('responseMailRecupMdp').innerHTML = "Pseudo invalide";
        document.getElementById('pseudoRecupMdp').style.borderColor ="red";
        return false;
    }

    if( !verifEmail(email) ) {
        domPseudo.style.borderColor = "#ced4da";
        domEmail.style.borderColor = "red";
        document.getElementById('responseMailRecupMdp').innerHTML = "L'adresse email n'est pas valide";
        return false;
    }
}