<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fin du formulaire</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="css/bootstrap.min.css" rel="stylesheet"/>

</head>
<body>

<?php include("navbar.php"); ?>

<h2 class="text-center" style="color: white; margin-top: 50px">Merci d'avoir rempli ce formulaire</h2>
<div class="col text-center" style="background-color: white; width: fit-content; margin: 40px auto auto; border-radius: 5px">
    <p>Ce lien de modification vous a été envoyé par mail : <a href="ajouter.php?id=<?php echo $_GET['id'] ?>" style="margin-top: 30px;">http://belleshistoires/ajouter.php?id=<?php echo $_GET['id'] ?> </a>
    </p>
</div>
<div class="col text-center">
    <a href="index.php" style="margin-top: 30px;" class="btn btn-lg btn-primary text-center" role="button">Retour à
        l'accueil</a>
</div>


</body>
</html>