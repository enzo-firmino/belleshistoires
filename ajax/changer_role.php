<?php

require_once("../pdo/pdo_connection.php");
try {
    $stmt = $db->prepare("UPDATE utilisateur SET super_admin = :super_admin WHERE id = :id");
    $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->bindValue(':super_admin', $_POST['super_admin'], PDO::PARAM_INT);
    $stmt->execute();
} catch (PDOException $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'Line : '.$e->getLine();
}

echo 'success';