<?php

session_start();

require_once("../pdo/pdo_connection.php");
$today =  date("Y-m-d H:i:s", strtotime('+2 hours'));

try {

    // Si l'id histoire existe on update, sinon on insert
    if (isset($_POST['idHistoire'])) {
        $id_histoire = $_POST['idHistoire'];
        $insert_histoire = $db->prepare('UPDATE histoire SET titre=?, recit=?, evolutions=?, nom_redacteur=?,
                      prenom_redacteur=?, email_redacteur=?, telephone_redacteur=?, fonction_redacteur=?, id_agence=?,
                    date_redaction=?, debut_periode=?, fin_periode=?, id_agence_rattachement=? WHERE id=?');
        $insert_histoire->execute(array($_POST['titre'], $_POST['recit'], $_POST['evolutions'], $_POST['nom_vos_infos'], $_POST['prenom_vos_infos'],
            $_POST['email_vos_infos'], $_POST['telephone_vos_infos'],
            $_POST['fonction_vos_infos'], $_POST['agence_vos_infos'], $today, $_POST['debutPeriode'], $_POST['finPeriode'], $_POST['agence_rattachement'], $id_histoire));

        if (isset($_SESSION['admin'])) {
            $email = $_SESSION['admin']['email'];
        } else {
            $email = $_POST['email_vos_infos'];
        }

        $insert_maj = $db->prepare('INSERT INTO mise_a_jour (date_maj, email, id_histoire) VALUES (?, ?, ?)');
        $insert_maj->execute(array($today, $email, $_POST['idHistoire']));

    } else {
        $id_histoire = sha1(time().$_POST['prenom_vos_infos'].$_POST['nom_vos_infos'].$_POST['email_vos_infos']);
        $insert_histoire = $db->prepare('INSERT INTO histoire (id, titre, recit, evolutions, nom_redacteur,
                      prenom_redacteur, email_redacteur, telephone_redacteur, fonction_redacteur, id_agence, date_redaction, debut_periode, fin_periode, id_agence_rattachement)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $insert_histoire->execute(array($id_histoire ,$_POST['titre'], $_POST['recit'], $_POST['evolutions'], $_POST['nom_vos_infos'], $_POST['prenom_vos_infos'],
            $_POST['email_vos_infos'], $_POST['telephone_vos_infos'],
            $_POST['fonction_vos_infos'], $_POST['agence_vos_infos'], $today, $_POST['debutPeriode'], $_POST['finPeriode'], $_POST['agence_rattachement']));
    }





    // Supprime tous les anciens contacts pour éviter d'avoir a update les anciens et insert les nouveaux (on insert tout)
    if (isset($_POST['contactsPreremplis'])) {
        foreach ($_POST['contactsPreremplis'] as $contactsPrerempli) {
            $delete_contacts = $db->exec('DELETE FROM contact WHERE id = ' . $contactsPrerempli['id']);
        }
    }

    // Insert chaque contact
    foreach ($_POST['contacts'] as &$contact) {
        $insert_mot_cle = $db->prepare('INSERT INTO contact (ordre, nom, prenom, email, telephone, commentaires,
                     en_charge, communiquer_externe, id_type, id_histoire, complement_1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        $insert_mot_cle->execute(array($contact['ordre'], $contact['nom_contact'], $contact['prenom_contact'], $contact['email_contact'],
            $contact['telephone_contact'], $contact['commentaires'], $contact['en_charge'], $contact['communiquer_externe'],
            $contact['type_contact'], $id_histoire, $contact['complement_1']));
    }


    // Supprime tous les anciens histoire_mot_cle pour éviter d'avoir a update les anciens et insert les nouveaux (on insert tout)
    if (isset($_POST['motsClesPreremplis'])) {
        foreach ($_POST['motsClesPreremplis'] as $motClePrerempli){
            $delete = $db->exec('DELETE FROM histoire_mot_cle WHERE id = ' . $motClePrerempli['id']);
        }
    }

    // Même chose pour les publics cibles
    if (isset($_POST['publicsCiblesPreremplis'])) {
        foreach ($_POST['publicsCiblesPreremplis'] as $publicCiblePrerempli){
            $delete = $db->exec('DELETE FROM histoire_public_cible WHERE id = ' . $publicCiblePrerempli['id']);
        }
    }


    // Insert les mots clés perso et récupère l'id
    if (isset($_POST['motsClesPerso'])) {
        foreach ($_POST['motsClesPerso'] as &$mot) {
            $insert_mot_cle = $db->prepare('INSERT INTO mot_cle (mot, supprimer) VALUES (:mot, 0)');
            $insert_mot_cle->bindValue(':mot', $mot);
            $insert_mot_cle->execute();
            $id = (int)$db->lastInsertId();

            $insert_mot_cle_histoire = $db->prepare('INSERT INTO histoire_mot_cle (id_mot_cle, id_histoire) VALUES (:id_mot_cle, :id_histoire)');
            $insert_mot_cle_histoire->bindValue(':id_mot_cle', $id, PDO::PARAM_INT);
            $insert_mot_cle_histoire->bindValue(':id_histoire', $id_histoire, PDO::PARAM_STR);
            $insert_mot_cle_histoire->execute();
        }
    }

    // Insert les publics cibles perso et récupère l'id
    if (isset($_POST['publicsCiblesPerso'])) {
        foreach ($_POST['publicsCiblesPerso'] as &$public_cible) {
            $insert_mot_cle = $db->prepare('INSERT INTO public_cible (public_cible, supprimer) VALUES (:public_cible, 0)');
            $insert_mot_cle->bindValue(':public_cible', $public_cible, PDO::PARAM_STR);
            $insert_mot_cle->execute();
            $id = (int)$db->lastInsertId();

            $insert_mot_cle_histoire = $db->prepare('INSERT INTO histoire_public_cible (id_public_cible, id_histoire) VALUES (:id_public_cible, :id_histoire)');
            $insert_mot_cle_histoire->bindValue(':id_public_cible', $id, PDO::PARAM_INT);
            $insert_mot_cle_histoire->bindValue(':id_histoire', $id_histoire, PDO::PARAM_STR);
            $insert_mot_cle_histoire->execute();
        }
    }

    // Insert les id des mots clés et l'id de l'histoire dans la table 'histoire_mot_cle'
    if (isset($_POST['idMotsCles'])) {
        foreach ($_POST['idMotsCles'] as &$id_mot_cle) {
            $insert_mot_cle_histoire = $db->prepare('INSERT INTO histoire_mot_cle (id_mot_cle, id_histoire) VALUES (:id_mot_cle, :id_histoire)');
            $insert_mot_cle_histoire->bindValue(':id_mot_cle', $id_mot_cle, PDO::PARAM_INT);
            $insert_mot_cle_histoire->bindValue(':id_histoire', $id_histoire, PDO::PARAM_STR);
            $insert_mot_cle_histoire->execute();
        }
    }

    // Insert les id des publics cibles et l'id de l'histoire dans la table 'histoire_public_cible'
    if (isset($_POST['idPublicsCibles'])) {
        foreach ($_POST['idPublicsCibles'] as &$id_public_cible) {
            $insert_mot_cle_histoire = $db->prepare('INSERT INTO histoire_public_cible (id_public_cible, id_histoire) VALUES (:id_public_cible, :id_histoire)');
            $insert_mot_cle_histoire->bindValue(':id_public_cible', $id_public_cible, PDO::PARAM_INT);
            $insert_mot_cle_histoire->bindValue(':id_histoire', $id_histoire, PDO::PARAM_STR);
            $insert_mot_cle_histoire->execute();
        }
    }


} catch  (PDOException $e) {
    echo($e->getMessage());
    echo($e->getLine());
}


if (!isset($_POST['idHistoire'])) {
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
<title>Votre lien de modification Belles histoires</title>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
<h1 style="font-size:1.1em;">Votre lien de modification Belles histoires</h1>
Voici votre lien de modification pour la belle histoire "{$_POST['titre']}"
<br /><a>http://belleshistoires/ajouter.php?id={$id_histoire}</a>

</div>
</body>
</html>
LEMAIL;

    $Mail->setFrom('noreply@xrmca6.dr031.pole-emploi.intra', 'Belles histoires - mail automatique');
    $Mail->addAddress($_POST['email_vos_infos']);
    $Mail->Subject = 'Votre lien de modification Belles histoires';
    $Mail->msgHTML($lemsg);
    $Mail->send();
}

echo json_encode(array('id' => $id_histoire));

