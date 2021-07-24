<?php

require_once("../pdo/pdo_connection.php");try {
    $inQuery = implode(',', array_fill(0, count($_POST['ids_mots_cles']), '?'));

    $stmt = $db->prepare("SELECT * FROM mot_cle WHERE id IN (" . $inQuery . ")");
    foreach ($_POST['ids_mots_cles'] as $k => $id)
        $stmt->bindValue(($k+1), $id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}
