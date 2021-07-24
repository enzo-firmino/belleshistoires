<?php
session_start();
unset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Belles histoires - Connexion</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/identification.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<?php include("navbar.php"); ?>

<div class="container" style="width: 50%">
    <form class="form-signin" id="form-identification">

        <h2 class="form-signin-heading" style="color: white">Identification</h2>
        <label for="mail_user" class="sr-only">Adresse email</label>
        <input type="email" class="form-control" id="mail_user" placeholder="Adresse email"
               value="<?php if(isset($_COOKIE['belleshistoiresemail'])) { echo $_COOKIE['belleshistoiresemail']; } else { echo "@pole-emploi.fr"; } ?>"
               required autofocus>
        <label for="pass_user" class="sr-only">Mot de passe</label>
        <input type="password" class="form-control" id="pass_user" placeholder="Mot de passe"
               value="<?php if(isset($_COOKIE['belleshistoirespassword'])) { echo $_COOKIE['belleshistoirespassword']; } ?>" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit" id="identification">S'identifier</button>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="remember">
            <label class="form-check-label" for="remember" style="color: white">
                Se souvenir de moi
            </label>
        </div>
        <div class="alert alert-danger" role="alert" id="alerte" style="display: none; margin-top:10px;">
            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
            <span> Identifiant / mot de passe erroné.</span>
        </div>
        <br/>
        <span class="btn btn-dark btn-sm pull-left" role="button" id="btrenewpass">Mot de passe oublié ?</span>

    </form>

    <form class="form-signin" id="form-renew" style="display: none;">

        <label style="color: white" for="mail_renew">Renouvellement du mot de passe</label>
        <input type="email" class="form-control" id="mail_renew" placeholder="Indiquez votre email" required>
        <button class="btn btn-sm btn-primary btn-block" type="submit" id="renewpass">Envoyer un nouveau mot de passe
        </button>
        <div class="alert alert-danger" role="alert" id="alert-renew" style="display: none; margin-top:10px;">
            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
            <span>Adresse email inconnue.</span>
        </div>
        <div class="alert alert-success" role="alert" id="success-renew" style="display: none; margin-top:10px;">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <span>Un nouveau mot de passe a été envoyé à l'adresse indiquée.</span>
        </div>
    </form>

</div> <!-- /container -->
<script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="js/identification.js"></script>
</body>
</html>