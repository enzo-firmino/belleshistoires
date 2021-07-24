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
    <div id="users">
        <form class="form-inline" id="form_ajout" style="margin-bottom: 50px">
            <div class="form-group mb-2" style="margin-right: 10px">
                <label for="email" class="sr-only">Email</label>
                <input type="text" class="form-control" id="email" value="@pole-emploi.fr">
            </div>
            <div class="col-auto custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="checkbox_super_admin">
                <label class="custom-control-label" for="checkbox_super_admin">
                    admin ?
                </label>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Ajouter utilisateur</button>
        </form>
        <div style="width: 400px; margin: 20px">
            <input type="text" class="form-control" id="filter_users" placeholder="Chercher...">
        </div>
        <table class="table table-striped" id="utilisateurs">
            <thead>
            <tr>
                <th>Email</th>
                <th>Rôle</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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