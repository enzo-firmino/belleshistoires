<?php
session_start();


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher une belle histoire</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/daterangepicker.css">

</head>
<body style="overflow-y:scroll;">
<?php
include("navbar.php");
if (!isset($_SESSION['admin'])) {
    echo '<div class="content">Vous n\'êtes pas habilités à voir les belles histoires, veuillez vous <a href="identification.php">connecter</a> pour accéder à cette page</div>';
} else {

    ?>


    <div id="content_recherche">
        <div id="box_recherche">
            <div id="champs_recherche">
                <div class="row">
                    <div class="col">
                        <label>Que recherchez-vous ?</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" id="text" name="text"/>
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" id="btn_vider_text" type="button">Vider</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto" style="display: flex;flex-direction: column;">
                        <label>Cette belle histoire s'est terminé il y a</label>
                        <select class="custom-select" name="periode" id="periode">
                        </select>
                    </div>
                    <div class="col-auto" style="display: flex;flex-direction: column;">
                        <label>Agence de rattachement </label>
                        <select class="custom-select" name="agences" id="agences">
                            <option style="font-weight: 1000; font-size: 1.3rem;" selected value="">Hauts-de-France
                            </option>
                        </select>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 25px">
                    <button class="btn btn-sm btn-danger" id="btn_vider_all">Vider tous les champs</button>
                </div>
                <div style="text-align: center; transform: translateY(20px);">
                    <button class="btn btn-primary" style="font-size: 1.2rem" id="btn_rechercher">Rechercher</button>
                </div>
            </div>
        </div>
        <div id="nbr_histoires_trouve"></div>
        <div id="liste_histoires">
        </div>
    </div>

    <div id="content_detail_histoire"></div>

<?php }

?>


<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jspdf.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/listeHistoires.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/recherche.js"></script>

</body>
</html>