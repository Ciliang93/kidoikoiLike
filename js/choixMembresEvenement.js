$(document).ready(function () {

    //Récupération des éléments dans des variables

    var divAdd = $("#champAddParticipants"),
        select = $("#selectParticipants"),
        valider = $("#validerAjoutParticipant"),
        divSelected = $("#selectedParticipants"),
        formAdd = $("#formAddMembres");


    $(select).on('change', function () {
        var choix = $(select).val();
        $(divSelected).show();
        $(divSelected).append('<button style="margin-right: 2%" disabled class="btn btn-info choisi" ' +
            'value="' + choix + '">' + choix + '</button>');
        $(select).find("option[value=" + choix + "]").hide();
        $(select).find("option[value='none']").prop('selected', true);
    });

    $('#listeParticipants').find("button").each(function () {
        $(select).find("option[value=" + $(this).text() + "]").hide();
    });

    $(formAdd).on('submit', function () {
        var tableauSelected = [];

        $(divSelected).find('.choisi').each(function () {
            if ($(this).hasClass("invite")) {
                tableauSelected.push('UT' + $(this).text());
            } else {
                tableauSelected.push($(this).text());
            }
        });

        tableauSelected = tableauSelected.join('/');
        $('#selectedValues').val(tableauSelected);

    });
});