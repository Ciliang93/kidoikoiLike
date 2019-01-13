<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index Kidoikoi</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!--Link css bootstrap-->
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-grid.css">
    <!--fontawesome sert à ajouter des petites icones-->
    <link rel="stylesheet" href="../css/fontawesome-all.css">
</head>

<body>
<div class="container mainDiv col-xs-11 col-sm-8 col-md-8 col-lg-8">
    <h5>Modifier votre mot de passe</h5>
    <form name="connect" method="post" action="../services/ModificationMdp.php" onsubmit="return validerFormulaireRecupMdp()">
        <div class="form-group">
            <input type="password" class="form-control" id="nouveauMdpA" name="nouveauMdpA" placeholder="Nouveau mot de passe">
            <input type="password" class="form-control" id="nouveauMdpB" name="nouveauMdpB" placeholder="Confirmez le mot de passe">
            <div id="reponseNouveauMdp" class="text-danger"></div>
        </div>
        <div class="form-group" id="divNouveauMdp">
            <input type="submit" id="btnNouveauMdp" class="btn btn-primary" value="Valider">
        </div>
    </form>
</div>
</body>

<!-- Scripts utilisés par bootstrap et awesomefont-->

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script src="../js/fontawesome-all.js"></script>
</body>
</html>