window.onload = function(){
    document.getElementById('btnRedirect').addEventListener('click', function(){

        console.log("placé dans ma ville");
        var idEvent = document.getElementById('idEvent').value;
        document.location.href = 'afficherEvenement.php?evt=' + idEvent;

    });
}


