$(document).ready(function () {

    var inputInvite = $('#inputInvite'),
        divSelected = $('#selectedParticipants'),
        submit = $('#submitInvite');



    $(inputInvite).on('focus', function () {
        $('.alertInvite').remove();
    });

    $(submit).on('click', function (e) {
        e.preventDefault();
        if ($(inputInvite).val() == "") {
            $('.alertInvite').remove();
            $(submit).parent().after('<div class="alert alert-danger alertInvite">Aucun nom d\'invité renseigné</div>');
        } else {
            $('.alertInvite').remove();
            var invite = $(inputInvite).val();
            $(divSelected).show();
            $(divSelected).append('<button style="margin-right: 2%" disabled class="btn btn-secondary choisi invite" ' +
                'value="' + invite + '">' + invite + '</button>');
            $(inputInvite).val('');
        }
    })

});