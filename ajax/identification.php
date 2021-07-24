<?php

session_start();

require_once("../pdo/pdo_connection.php");

require_once("../Password.php");

unset( $_SESSION['admin']);


try {

switch ($_POST['function']) {

    case 'identification':
        identification($_POST,$db);
        break;
    case 'renewpass' :
        renewpass($_POST, $db);
        break;
}


} catch  (PDOException $e) {
    echo($e->getMessage());
    echo($e->getLine());
}


function identification ($data, $db) {

    if($_POST['remember'] == 'true'){
        setcookie("belleshistoiresemail", $_POST['email'], time()+60*60*24*100, "/");
        setcookie("belleshistoirespassword", $_POST['password'], time()+60*60*24*100, "/");
    } else {
        unset($_COOKIE['belleshistoiresemail']);
        setcookie('belleshistoiresemail', null, -1, '/');
        unset($_COOKIE['belleshistoirespassword']);
        setcookie('belleshistoirespassword', null, -1, '/');
    }

    $pass = new Password();

    $rq = $db->prepare("SELECT id, email, super_admin FROM utilisateur WHERE email = :email AND password = :password LIMIT 1");

    $rq->bindValue(':email', $data['email'], PDO::PARAM_STR);

    $rq->bindValue(':password', $pass->salt($data['password']), PDO::PARAM_STR);

    $rq->execute();

    if($rq->rowCount() == 1) {
        $_SESSION['admin'] =$rq->fetch(PDO::FETCH_ASSOC);
        $_SESSION['admin']['valid'] = true;
        $response['valid'] = true;
    }
    else {
        $_SESSION['admin']['valid'] = false;
    }

    if(isset($_SESSION['admin']['super_admin'])){
        $response['admin'] = $_SESSION['admin']['super_admin'];
    }else{
        $response['admin'] = "";
    }

    echo json_encode($response);

    $pass = null;
    unset($pass);
}

function renewpass($data, $db) {

    $pass = new Password();
    $result = array();
    $newpass = $pass->genere_mdp();
    $newpasssalt = $pass->salt($newpass);


    $rq = $db->prepare("SELECT email FROM utilisateur WHERE email = :email LIMIT 1");
    $rq->bindValue(':email', $data['email'], PDO::PARAM_STR);
    $rq->execute();
    if($rq->rowCount() == 1) {
        $result['value'] = true;
    // enregistrement du nouveau mot de passe
        $rq2 = $db->prepare("UPDATE utilisateur SET password = :password WHERE email = :email LIMIT 1");
        $rq2->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $rq2->bindValue(':password', $newpasssalt, PDO::PARAM_STR);

    //envoi du mail
        require_once("../phpmailer/class.phpmailer.php");
        require_once("../phpmailer/class.smtp.php");
        $Mail = new PHPMailer(true);
//Create a new PHPMailer instance

        $Mail->CharSet = 'UTF-8';
        $lemsg= <<< LEMAIL
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Votre mot de passe Belles histoires</title>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
<h1 style="font-size:1.1em;">Votre mot de passe "Belles histoires"</h1>
Une demande de création ou de renouvellement de mot de passe pour l'accès aux Belles histoires a été effectuée.
<br /><b>Voici votre nouveau mot de passe : </b>{$newpass}
<br /><br />Pour vous connecter, <a href="http://dw31a2.sip24.pole-emploi.intra/belleshistoires/identification.php">rendez-vous sur les Belles histoires à la section Connexion</a>.

</div>
</body>
</html>
LEMAIL;

        $Mail->setFrom('noreply@xrmca6.dr031.pole-emploi.intra', 'Belles histoires - mail automatique');
        $Mail->addAddress($data['email']);
        $Mail->Subject = 'Votre mot de passe Belles histoires';
        $Mail->msgHTML($lemsg);
        if($Mail->send()) {
            $result['value']=true;
        }
    }
    else {
        $result['value'] = false;
    }


    $db = null;
    unset($db);
    $pass = null;
    unset($pass);
    echo json_encode( $result);
}
