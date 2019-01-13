$(document).ready(function () {

    var form = $("#formModifMdpProfil"),
        mdpCurrent = $("#mdpCurrent"),
        mdpNew = $("#mdpNew"),
        mdpNewVerif = $("#mdpNewVerif");





    //Controle de remplissage du champs du mdp actuel

    $(mdpCurrent).on('focus', function () {
        $('#alertMdpCurrent').remove();
        $('.text-success').remove();
        document.getElementById('mdpCurrent').style.borderColor = '#ced4da';
    });

    $(mdpCurrent).on('focusout', function () {
        if ($(mdpCurrent).val() == "") {
            $("#alertMdpCurrent").remove();
            $('.text-success').remove();
            document.getElementById('mdpCurrent').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMdpCurrent">Mot de passe actuel obligatoire</div>');
        }
    });


    //Controle de remplissage et de non similitude avec l'ancien mdp
    $(mdpNew).on('focus', function () {
        $('#alertMdpNew').remove();
        $('#alertMdpSameBefore').remove();
        $('.text-success').remove();
        document.getElementById('mdpNew').style.borderColor = '#ced4da';
        document.getElementById('mdpCurrent').style.borderColor = '#ced4da';
    });

    $(mdpNew).on('focusout', function () {
        if ($(mdpNew).val() == "") {
            $("#alertMdpNew").remove();
            $('.text-success').remove();
            document.getElementById('mdpNew').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMdpNew">Veuillez renseigner un nouveau mot de passe</div>');
        } else if ($(mdpNew).val() == $(mdpCurrent).val()) {
            $('#alertMdpNew').remove();
            $('#alertMdpCurrent').remove();
            $('#alertMdpSameBefore').remove();
            $('.text-success').remove();

            document.getElementById('mdpCurrent').style.borderColor = 'red';
            document.getElementById('mdpNew').style.borderColor = 'red';

            $(form).before('<div class="alert alert-danger" id="alertMdpSameBefore">Le nouveau mot de passe est identique à celui que vous utilisez actuellement !</div>');
        }
    });

    //Controle de remplissage et de similitude entre les nouveaux mdp
    $(mdpNewVerif).on('focus', function () {
        $('#alertMdpNewVerif').remove();
        $('#alertMdpNewDifferent').remove();
        $('.text-success').remove();
        document.getElementById('mdpNew').style.borderColor = '#ced4da';
        document.getElementById('mdpNewVerif').style.borderColor = '#ced4da';
    });

    $(mdpNewVerif).on('focusout', function () {
        if ($(mdpNewVerif).val() == "" || !($(mdpNew).val() == $(mdpNewVerif).val())) {
            $('#alertMdpNewVerif').remove();
            $('#alertMdpNewDifferent').remove();
            $('.text-success').remove();
            document.getElementById('mdpNew').style.borderColor = 'red';
            document.getElementById('mdpNewVerif').style.borderColor = 'red';

            $(form).before('<div class="alert alert-danger" id="alertMdpNewDifferent">Les nouveaux mots de passe ne correspondent pas</div>');
        }
    });



    //Controle global à l'envoi du formulaire

    $(form).on('submit', function (e) {
        $("#alertMdpCurrent").remove();
        $("#alertMdpNew").remove();
        $('#alertMdpSameBefore').remove();
        $('#alertMdpNewVerif').remove();
        $('#alertMdpNewDifferent').remove();
        $('.text-success').remove();

        if ($(mdpCurrent).val() == "") {
            e.preventDefault();
            document.getElementById('mdpCurrent').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMdpCurrent">Mot de passe actuel obligatoire</div>');
        }
        if ($(mdpNew).val() == "") {
            e.preventDefault()
            document.getElementById('mdpNew').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMdpNew">Veuillez renseigner un nouveau mot de passe</div>');
        } else if ($(mdpNew).val() == $(mdpCurrent).val()) {
            e.preventDefault()
            document.getElementById('mdpCurrent').style.borderColor = 'red';
            document.getElementById('mdpNew').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMdpSameBefore">Le nouveau mot de passe est identique à celui que vous utilisez actuellement !</div>');
        }
        if ($(mdpNewVerif).val() == "" || !($(mdpNew).val() == $(mdpNewVerif).val())) {
            e.preventDefault();
            document.getElementById('mdpNew').style.borderColor = 'red';
            document.getElementById('mdpNewVerif').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMdpNewDifferent">Les nouveaux mots de passe ne correspondent pas</div>');
        }

    })

});