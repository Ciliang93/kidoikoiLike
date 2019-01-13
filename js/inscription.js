function validerFormulaireInscription() {
    var domPseudo = document.getElementById('pseudoInscription');
    var pseudo = domPseudo.value;
    var domEmail = document.getElementById('mailInscription');
    var email = domEmail.value;
    var reponse = document.getElementById('reponseInscription');

    //Vérification de la taille du pseudo
    if(pseudo.length < 8 || pseudo.length > 20) {
        resetCouleursChamps();
        domPseudo.style.borderColor="red";
        reponse.innerHTML = "Le pseudo doit contenir entre 8 et 20 caracteres";
        return false;
    }

    //Vérification de la présence de caractère spécial dans le pseudo
    if(verifCaracSpeciaux(pseudo)) {
        resetCouleursChamps();
        domPseudo.style.borderColor="red";
        reponse.innerHTML = "Le pseudo ne peut pas contenir de caractère spéciaux";
        return false;
    }

    //Vérification de l'adresse email
    if(!verifEmail(email)){
        resetCouleursChamps();
        domEmail.style.borderColor="red";
        reponse.innerHTML = "L'adresse email n'est pas valide";
        return false;
    }

    //Blindage du mot de passe
    return ( validerMdp(document.getElementById('mdpInscriptionA'), document.getElementById('mdpInscriptionB'), reponse) );

}

function verifCaracSpeciaux(string) { //Renvoie vrai si un caractère spécial est trouvé
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
    return format.test(string);
}

function verifChiffres(string) { //Renvoie vrai si un chiffre est trouvé
    var format = /[0123456789]/;
    return format.test(string);
}

function verifEmail(email) { //Renvoie vrai si l'adresse mail est valide
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validerMdp(domNouveauMdp1, domNouveauMdp2, domMessage){
    var domNouveauMdpA = domNouveauMdp1;
    var nouveauMdpA = domNouveauMdpA.value;
    var domNouveauMdpB = domNouveauMdp2;
    var nouveauMdpB = domNouveauMdpB.value;
    var domResponse = domMessage;

    if(nouveauMdpA === "" || nouveauMdpB === ""){
        domResponse.innerHTML = "Entrez un mot de passe";
        return false;
    } else if(nouveauMdpA.length < 8 || nouveauMdpB.length > 20){
        domResponse.innerHTML = "Votre nouveau mot de passe doit contenir entre 8 et 20 caractères";
        domNouveauMdpA.style.borderColor = "red";
        return false;
    } else if (nouveauMdpA === nouveauMdpB) {
        if(verifCaracSpeciaux(nouveauMdpA)){
            if(verifChiffres(nouveauMdpA)){
                return true;
            } else {
                domResponse.innerHTML = "Votre nouveau mot de passe doit contenir au moins un chiffre";
                domNouveauMdpA.style.borderColor = "red";
                return false;
            }
        } else {
            domResponse.innerHTML = "Votre nouveau mot de passe doit contenir au moins un caractère spécial";
            domNouveauMdpA.style.borderColor = "red";
            return false;
        }
    } else {
        domResponse.innerHTML = "Les deux mots de passe ne correspondent pas";
        domNouveauMdpA.style.borderColor = "red";
        domNouveauMdpB.style.borderColor = "red";
        return false;
    }
}

function resetCouleursChamps(){
    var domChamp = [document.getElementById('pseudoInscription'),
                     document.getElementById('mailInscription'),
                     document.getElementById('mdpInscriptionA'),
                     document.getElementById('mdpInscriptionB')];

        for(var i = 0; i < 4 ; i++){
            domChamp[i].style.borderColor = "#ced4da";
        }
}