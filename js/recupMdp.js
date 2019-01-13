function validerFormulaireRecupMdp(){
    var domNouveauMdpA = document.getElementById('nouveauMdpA');
    var nouveauMdpA = domNouveauMdpA.value;
    var domNouveauMdpB = document.getElementById('nouveauMdpB');
    var nouveauMdpB = domNouveauMdpB.value;
    var domResponse = document.getElementById('reponseNouveauMdp');
    domNouveauMdpA.style.borderColor = "#ced4da";
    domNouveauMdpB.style.borderColor = "#ced4da";

    if(nouveauMdpA == "" || nouveauMdpB == ""){
        domResponse.innerHTML = "Un champ est vide";
        return false;
    } else if(nouveauMdpA.length < 8 || nouveauMdpB.length > 20){
        domResponse.innerHTML = "Votre nouveau mot de passe doit contenir entre 8 et 20 caractères";
        domNouveauMdpA.style.borderColor = "red";
    } else if (nouveauMdpA == nouveauMdpB) {
        if(verifCaracSpeciaux(nouveauMdpA)){
            if(verifChiffres(nouveauMdpA)){

            } else {
                domResponse.innerHTML = "Votre nouveau mot de passe doit contenir au moins un chiffre";
                domNouveauMdpA.style.borderColor = "red";
            }
        } else {
            domResponse.innerHTML = "Votre nouveau mot de passe doit contenir au moins un caractère spécial";
            domNouveauMdpA.style.borderColor = "red";
        }
    } else {
        domResponse.innerHTML = "Les deux mots de passe ne correspondent pas";
        domNouveauMdpA.style.borderColor = "red";
        domNouveauMdpB.style.borderColor = "red";
    }
}
