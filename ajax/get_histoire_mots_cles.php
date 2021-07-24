<?php

require_once("../pdo/pdo_connection.php");//Fetch les mots clés where l'id histoire est passé en parametres
try {

    $stmt = $db->prepare('SELECT * FROM histoire_mot_cle WHERE id_histoire = :id_histoire');
    $stmt->bindValue(':id_histoire', $_GET['id_histoire'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e)
{
    var_dump($e->getMessage());
    var_dump($e->getLine());
    echo 'error';
}
