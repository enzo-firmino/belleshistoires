<?php


require_once("../pdo/pdo_connection.php");
require_once("../Password.php");

$pass = new Password;
$newpass = $pass->genere_mdp();

// Ajout de l'utilisateur
try {

$insert_histoire = $db->prepare('INSERT INTO utilisateur (email, password, super_admin) VALUES (?, ?, ?)');
$insert_histoire->execute(array($_POST['email'], $pass->salt($newpass), $_POST['super_admin']));

} catch (PDOException $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'Line : '.$e->getLine();
}

//envoi du mail
require_once("../phpmailer/class.phpmailer.php");
require_once("../phpmailer/class.smtp.php");

$Mail = new PHPMailer(true);
//$Mail->IsSMTP(true);
//Create a new PHPMailer instance

$Mail->CharSet = 'UTF-8';
$lemsg = <<< LEMAIL
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Votre compte Belles histoires</title>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
<h1 style="font-size:1.1em;">Votre compte Belles histoires"</h1>
Votre compte pour les belles histoires a été créé, vous pouvez maintenant vous connecter et accéder à la liste des belles histoires.
<br /><b>Voici votre identifiant : </b>{$_POST['email']}
<br /><b>Voici votre nouveau mot de passe : </b>{$newpass}
<br /><br />Pour vous connecter, <a href="http://dw31a2.sip24.pole-emploi.intra/belleshistoires/identification.php">cliquez ici</a>.

</div>
</body>
</html>
LEMAIL;

$Mail->setFrom('noreply@xrmca6.dr031.pole-emploi.intra', 'Belles histoires - mail automatique');
$Mail->addAddress($_POST['email']);
$Mail->Subject = 'Votre compte Belles histoires';
$Mail->msgHTML($lemsg);

if ($Mail->send()) {
    echo 'success';
}
else {
    echo 'fail';
}
