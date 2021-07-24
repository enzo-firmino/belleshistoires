<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accueil belles histoires</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<?php
include("navbar.php");
?>

<div class="content" id="content_recherche">
    <div id="nbr_belles_histoires"></div>
    <div style="text-align: center;">
        <a href="ajouter.php" style="margin-top: 30px;" class="btn btn-lg btn-primary" role="button">Ajouter une belle histoire</a>
    </div>
    <?php
    if (isset($_SESSION['admin'])) {
        echo '<div id="liste_histoires" style="margin-top: 50px">
                <div style="text-align: center; font-size: 1.2rem">Belles histoires récentes</div>
            </div>';
    }
    ?>
</div>
<div id="content_detail_histoire"></div>


<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jspdf.min.js"></script>
<script src="js/index.js"></script>
<script src="js/listeHistoires.js"></script>
</body>
</html>