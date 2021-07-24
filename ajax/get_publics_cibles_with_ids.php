<?php

require_once("../pdo/pdo_connection.php");try {
    $inQuery = implode(',', array_fill(0, count($_POST['ids_publics_cibles']), '?'));

    $stmt = $db->prepare("SELECT * FROM public_cible WHERE id IN (" . $inQuery . ")");
    foreach ($_POST['ids_publics_cibles'] as $k => $id)
        $stmt->bindValue(($k+1), $id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $result );
} catch (Exception $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}
