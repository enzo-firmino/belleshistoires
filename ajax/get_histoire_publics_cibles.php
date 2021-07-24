<?php

require_once("../pdo/pdo_connection.php");
try {

    $stmt = $db->prepare('SELECT * FROM histoire_public_cible WHERE id_histoire = :id_histoire');
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
