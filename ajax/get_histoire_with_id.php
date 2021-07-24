<?php

require_once("../pdo/pdo_connection.php");try {

    $stmt = $db->prepare("SELECT * FROM histoire WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}
