<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Belles histoires - Connexion</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include("navbar.php");
if (!isset($_SESSION['admin'])) {
    echo '<div class="content">Vous n\'êtes pas habilités à voir les belles histoires, veuillez vous <a href="identification.php">connecter</a> pour accéder à cette page</div>';
} else {
    ?>

    <div class="content">
        <div class="modal fade" id="modalConfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
        <div>
            <span>Supprimer des mots clés</span>
            <div id="mots_cles_admin" class="container_mots_publics" style="margin-top: 0; margin-bottom: 25px">
            </div>
            <span>Supprimer des publics concernés</span>
            <div id="publics_cibles_admin" class="container_mots_publics" style="margin-top: 0; margin-bottom: 25px">
            </div>
        </div>
    </div>

<?php }

?>
<script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/listeHistoires.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
</body>
</html>