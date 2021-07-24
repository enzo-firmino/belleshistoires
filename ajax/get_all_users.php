<?php

require_once("../pdo/pdo_connection.php");
session_start();
try {
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id != :id ORDER BY email ");
    $stmt->bindValue(':id', $_SESSION['admin']['id']);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}