$(document).ready(function () {

    var titre = $("#titreDepense"),
        date = $("#dateDepense"),
        montant = $("#montantDepense"),
        selectedParticipants = $("#selectedDepense"),
        select = $("#participantsDepense"),
        form = $("#formAddDepense"),
        divAlertes = $("#alertes"),
        tableauPart = $("#tableauParticipantsDepense").find("tbody"),
        firstOptionAvanceur = $("#firstOptionAvanceur");


    $(tableauPart).find('.lock').each(function() {

        $(this).on('focus', function () {
            $(this).prop('disabled', true);
        });
    });

    $(montant).on('focusout', function () {
        //Trouver un moyen d'ajouter le symbole € dans le champs
    });

    $(select).on('change', function () {
        var prenom, pseudo;
        prenom = $("#participantsDepense option:selected").text();
        pseudo = $("#participantsDepense option:selected").val();

        if($(select).val().indexOf('UT') !== -1){ //Si c'est un utilisateur Temporaire
            $(firstOptionAvanceur).after( //Ajout dans le select de l'avanceur

                '<option value="' + pseudo + '">' + prenom + '</option>'

            );

            $(selectedParticipants).show();
            $(selectedParticipants).find("option[value='none']").prop('selected', true);

            $(tableauPart).append('<tr>' +
                                    '<td class="col-sm-7 col-md-7 col-lg-7"><input type="text" style="margin-right: 2%" class="form-control text-info partDepense lock" name="txtPart' + pseudo + '" value="' + prenom + '"></td>' +
                                    '<td class="col-sm-4 col-md-4 col-lg-4"><input class="form-control" value="1" type="number" name="spinnerPart'+ pseudo +'" id="spinnerPart'+ prenom +'"/></td>' +
                                  '</tr>');


            $(select).find("option[value=" + pseudo + "]").hide();
            $(select).find("option[value='none']").prop('selected', true);

        } else {

            $(selectedParticipants).show();
            $(tableauPart).append('<tr>' +
                '<td class="col-sm-7 col-md-7 col-lg-7"><input type="text" style="margin-right: 2%" name="txtPart'+ prenom +'" class="form-control text-info partDepense lock" value="' + prenom + '"></td>' +
                '<td class="col-sm-4 col-md-4 col-lg-4"><input type="number" class="form-control" value="1" min="1" name="spinnerPart'+ prenom +'" id="spinnerPart'+ prenom +'"/></td>' +
                '</tr>');
            $(select).find("option[value=" + pseudo + "]").hide();
            $(select).find("option[value='none']").prop('selected', true);
            $(firstOptionAvanceur).after( //Ajout dans le select de l'avanceur

                '<option value="' + pseudo + '">' + prenom + '</option>'


            );
        }

    });

    //Controles de remplissage sur l'input du titre
    $(titre).on('focus', function () {
        $("#alertTitre").remove();
        document.getElementById('titreDepense').style.borderColor = '#ced4da';
    });

    $(titre).on('focusout', function () {
        if ($(titre).val() == "") {
            $("#alertTitre").remove();
            document.getElementById('titreDepense').style.borderColor = 'red';
            $(divAlertes).append('<div class="alert alert-danger" id="alertTitre">Veuillez renseigner un titre</div>');
        }
    });

    //Controles de remplissage sur l'input de la date

    $(date).on('focus', function () {
        $("#alertDate").remove();
        document.getElementById('dateDepense').style.borderColor = '#ced4da';
    })

    $(date).on('focusout', function () {
        if ($(date).val() == "") {
            $("#alertDate").remove();
            document.getElementById('dateDepense').style.borderColor = 'red';
            $(divAlertes).append('<div class="alert alert-danger" id="alertDate">Veuillez renseigner une date</div>');
        }

    });

    //Controles de remplissage sur l'input du montant
    $(montant).on('focus', function () {
        $("#alertMontant").remove();
        document.getElementById('montantDepense').style.borderColor = '#ced4da';
    });

    $(montant).on('focusout', function () {
        if ($(montant).val() == '' || $(montant).val() <= 0) {
            $("#alertMontant").remove();
            document.getElementById('montantDepense').style.borderColor = 'red';
            $(divAlertes).append('<div class="alert alert-danger" id="alertMontant">Veuillez renseigner un montant correct</div>');
        }
    });

    //remise à la normale de la couleur de bordure du select
    $(select).on('focus', function () {
        $("#alertParticipant").remove();
        document.getElementById('participantsDepense').style.borderColor = '#ced4da';
    })


    $(form).on('submit', function (e) {

        var tableauSelected = [];

        $(selectedParticipants).find('.partDepense').each(function () {
            tableauSelected.push($(this).text());
        });

        console.log(tableauSelected);


        tableauSelected = tableauSelected.join('/');
        $('#selectedValuesDepense').val(tableauSelected);


        if (tableauSelected == "" || $(titre).val() == "" || $(date).val() == "" || ($(montant).val() == '' || $(montant).val() <= 0)) {

            e.preventDefault();


            if ($(titre).val() == "") {
                $("#alertTitre").remove();
                document.getElementById('titreDepense').style.borderColor = 'red';
                $(divAlertes).append('<div class="alert alert-danger" id="alertTitre">Veuillez renseigner un titre</div>');
            }

            if ($(date).val() == "") {
                $("#alertDate").remove();
                document.getElementById('dateDepense').style.borderColor = 'red';
                $(divAlertes).append('<div class="alert alert-danger" id="alertDate">Veuillez renseigner une date</div>');
            }

            if ($(montant).val() == '' || $(montant).val() <= 0) {
                $("#alertMontant").remove();
                document.getElementById('montantDepense').style.borderColor = 'red';
                $(divAlertes).append('<div class="alert alert-danger" id="alertMontant">Veuillez renseigner un montant correct</div>');
            }

            if (tableauSelected == "" || tableauSelected == null) {
                $('#alertParticipant').remove();
                document.getElementById('participantsDepense').style.borderColor = 'red';
                $(divAlertes).append('<div class="alert alert-danger" id="alertParticipant">Veuillez renseigner au moins un participant</div>');
            }

        }


    });

});