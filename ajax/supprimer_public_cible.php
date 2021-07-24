<?php

require_once("../pdo/pdo_connection.php");
try {
    $stmt = $db->prepare("UPDATE public_cible
                                SET supprimer = 1
                                WHERE id = :id");
    $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
} catch (PDOException $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'Line : '.$e->getLine();
}

echo 'success';