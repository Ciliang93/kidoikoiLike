$(document).ready(function () {

    var form = $('#formModifEmailProfil'),
        mailCurrent = $('#mailCurrent'),
        mailNew = $('#mailNew'),
        mailNewVerif = $('#mailVerif');


    //Controle de remplissage du champs mailNew 
    // et verfification de non similitude avec l'adresse actuelle

    $(mailNew).on('focus', function () {
        $('#alertMailNew').remove();
        $('.text-success').remove();
        $('#alertMailNewSame').remove();
        document.getElementById('mailNew').style.borderColor = '#ced4da';
    });

    $(mailNew).on('focusout', function () {
        if ($(mailNew).val() == "") {
            $('#alertMailNew').remove();
            $('#alertMailNewSame').remove();
            $('.text-success').remove();
            document.getElementById('mailNew').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMailNew">Veuillez renseigner une nouvelle adresse mail</div>');
        } else if ($(mailNew).val() == $(mailCurrent).val()) {
            $('#alertMailNew').remove();
            $('#alertMailNewSame').remove();
            $('.text-success').remove();
            document.getElementById('mailNew').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMailNewSame">La nouvelle adresse est identique à celle que vous utilisez actuellement</div>');

        }
    });

    //Controle de remplissage du champs mailVerif
    //et vérification de la concordance avec mailNew

    $(mailNewVerif).on('focus', function () {
        $('#alertMailNewVerif').remove();
        $('.text-success').remove();
        document.getElementById('mailVerif').style.borderColor = '#ced4da';
    });

    $(mailNewVerif).on('focusout', function () {
        if ($(mailNewVerif).val() == "" || !($(mailNewVerif).val() == $(mailNew).val())) {
            $('#alertMailNewVerif').remove();
            $('.text-success').remove();
            document.getElementById('mailVerif').style.borderColor = 'red';
            document.getElementById('mailNew').style.borderColor = 'red';

            $(form).before('<div class="alert alert-danger" id="alertMailNewVerif">Les nouvelles adresses mail ne correspondent pas</div>');
        } else {
            document.getElementById('mailNew').style.borderColor = '#ced4da';
            document.getElementById('mailVerif').style.borderColor = '#ced4da';
        }
    });


    $(form).on('submit', function (e) {
        $('#alertMailNewVerif').remove();
        $('#alertMailNew').remove();
        $('#alertMailNewSame').remove();
        $('.text-success').remove();

        if ($(mailNew).val() == "") {
            e.preventDefault();
            document.getElementById('mailNew').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMailNew">Veuillez renseigner une nouvelle adresse mail</div>');
        } else if ($(mailNew).val() == $(mailCurrent).val()) {
            e.preventDefault();
            document.getElementById('mailNew').style.borderColor = 'red';
            $(form).before('<div class="alert alert-danger" id="alertMailNewSame">La nouvelle adresse est identique à celle que vous utilisez actuellement</div>');
        }
        if ($(mailNewVerif).val() == "" || !($(mailNewVerif).val() == $(mailNew).val())) {
            e.preventDefault();
            document.getElementById('mailVerif').style.borderColor = 'red';
            document.getElementById('mailNew').style.borderColor = 'red';

            $(form).before('<div class="alert alert-danger" id="alertMailNewVerif">Les nouvelles adresses mail ne correspondent pas</div>');
        }


    })
});
